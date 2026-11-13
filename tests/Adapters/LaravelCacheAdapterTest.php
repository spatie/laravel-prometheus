<?php

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Cache;
use Prometheus\CollectorRegistry;
use Prometheus\MetricFamilySamples;
use Spatie\Prometheus\Adapters\LaravelCacheAdapter;

it('collects metrics that were written by another adapter instance', function () {
    $cache = new Repository(new ArrayStore);

    $writingRegistry = new CollectorRegistry(new LaravelCacheAdapter($cache), false);
    $writingRegistry->getOrRegisterCounter('app', 'test_counter', 'help')->inc();
    $writingRegistry->getOrRegisterGauge('app', 'test_gauge', 'help')->set(5);
    $writingRegistry->getOrRegisterHistogram('app', 'test_histogram', 'help')->observe(0.5);
    $writingRegistry->getOrRegisterSummary('app', 'test_summary', 'help')->observe(0.5);

    $collectingRegistry = new CollectorRegistry(new LaravelCacheAdapter($cache), false);

    $metricNames = collect($collectingRegistry->getMetricFamilySamples())
        ->map(fn (MetricFamilySamples $samples) => $samples->getName())
        ->sort()
        ->values()
        ->all();

    expect($metricNames)->toBe([
        'app_test_counter',
        'app_test_gauge',
        'app_test_histogram',
        'app_test_summary',
    ]);
});

it('does not collect metrics after the storage was wiped', function () {
    $cache = new Repository(new ArrayStore);

    $writingRegistry = new CollectorRegistry(new LaravelCacheAdapter($cache), false);
    $writingRegistry->getOrRegisterCounter('app', 'test_counter', 'help')->inc();
    $writingRegistry->wipeStorage();

    $collectingRegistry = new CollectorRegistry(new LaravelCacheAdapter($cache), false);

    expect($collectingRegistry->getMetricFamilySamples())->toBeEmpty();
});

it('locks concurrent updates only when cache_lock is enabled', function (string $registerMethod, string $updateMethod, string $sampleName, bool $lock, int $expected) {
    $store = new class extends ArrayStore
    {
        public ?Closure $otherProcess = null;

        public ?Closure $waiting = null;

        public function get($key): mixed
        {
            $value = parent::get($key);

            if ($this->otherProcess !== null) {
                $otherProcess = $this->otherProcess;
                $this->otherProcess = null;

                $this->locks === [] ? $otherProcess() : $this->waiting = $otherProcess;
            }

            return $value;
        }
    };
    Cache::extend('interleaving', fn () => Cache::repository($store));
    config([
        'cache.stores.interleaving' => ['driver' => 'interleaving'],
        'prometheus.cache' => 'interleaving',
        'prometheus.cache_lock' => $lock,
    ]);

    $write = fn (CollectorRegistry $registry, int $value) => $registry->$registerMethod('app', 'test_metric', 'help')->$updateMethod($value);

    $store->otherProcess = fn () => $write(new CollectorRegistry(new LaravelCacheAdapter(Cache::store('interleaving'), $lock), false), 7);

    $write(app(CollectorRegistry::class), 3);

    if ($store->waiting !== null) {
        ($store->waiting)();
    }

    $samples = (new CollectorRegistry(new LaravelCacheAdapter(Cache::store('interleaving')), false))->getMetricFamilySamples();

    $sample = collect($samples[0]->getSamples())->first(fn ($sample) => $sample->getName() === $sampleName);

    expect($sample->getValue())->toEqual($expected);
})->with([
    'counter' => ['getOrRegisterCounter', 'incBy', 'app_test_metric'],
    'gauge' => ['getOrRegisterGauge', 'incBy', 'app_test_metric'],
    'histogram' => ['getOrRegisterHistogram', 'observe', 'app_test_metric_sum'],
    'summary' => ['getOrRegisterSummary', 'observe', 'app_test_metric_sum'],
])->with([
    'enabled' => [true, 10],
    'disabled, the default' => [false, 3],
]);

it('does not write without a lock when acquisition times out', function () {
    $cache = new Repository(new ArrayStore);
    $registry = new CollectorRegistry(new LaravelCacheAdapter($cache), false);
    $registry->getOrRegisterCounter('app', 'test_counter', 'help')->incBy(7);

    $lock = $cache->getStore()->lock('PROMETHEUS_counter_METRICS_LOCK', 60);
    $lock->get();

    $adapter = new class($cache, true) extends LaravelCacheAdapter
    {
        protected int $lockWaitSeconds = 0;
    };
    $counter = (new CollectorRegistry($adapter, false))->getOrRegisterCounter('app', 'test_counter', 'help');

    expect(fn () => $counter->incBy(3))->toThrow(LockTimeoutException::class);

    $samples = (new CollectorRegistry(new LaravelCacheAdapter($cache), false))->getMetricFamilySamples();

    expect($samples[0]->getSamples()[0]->getValue())->toEqual(7);

    $lock->release();
    $counter->incBy(3);

    expect($registry->getMetricFamilySamples()[0]->getSamples()[0]->getValue())->toEqual(10);
});

it('rejects stores without lock support when locking is enabled', function () {
    $cache = new Repository(Mockery::mock(Store::class));
    $registry = new CollectorRegistry(new LaravelCacheAdapter($cache, true), false);

    expect(fn () => $registry->getOrRegisterCounter('app', 'test_counter', 'help')->inc())
        ->toThrow(InvalidArgumentException::class, 'The configured Prometheus cache store does not support locks.');
});
