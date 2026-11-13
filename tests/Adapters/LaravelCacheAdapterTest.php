<?php

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Sleep;
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

it('requires the metric lock only when cache_lock is enabled', function (string $type, string $registerMethod, string $updateMethod, string $sampleName, bool $locking) {
    Sleep::fake(syncWithCarbon: true);

    $cache = new Repository(new ArrayStore);
    Cache::extend('array', fn () => $cache);

    config([
        'prometheus.cache' => 'array',
        'prometheus.cache_lock' => $locking,
    ]);

    $registry = app(CollectorRegistry::class);
    $metric = $registry->$registerMethod('app', 'test_metric', 'help');
    $metric->$updateMethod(7);

    $lock = $cache->lock("PROMETHEUS_{$type}_METRICS_LOCK", 60);
    $lock->get();

    $locking
        ? expect(fn () => $metric->$updateMethod(3))->toThrow(LockTimeoutException::class)
        : $metric->$updateMethod(3);

    $samples = $registry->getMetricFamilySamples();
    $sample = collect($samples[0]->getSamples())->first(fn ($sample) => $sample->getName() === $sampleName);

    expect($sample->getValue())->toEqual($locking ? 7 : 10);

    $lock->release();
    $metric->$updateMethod(3);

    $samples = $registry->getMetricFamilySamples();
    $sample = collect($samples[0]->getSamples())->first(fn ($sample) => $sample->getName() === $sampleName);

    expect($sample->getValue())->toEqual($locking ? 10 : 13);
})->with([
    'counter' => ['counter', 'getOrRegisterCounter', 'incBy', 'app_test_metric'],
    'gauge' => ['gauge', 'getOrRegisterGauge', 'incBy', 'app_test_metric'],
    'histogram' => ['histogram', 'getOrRegisterHistogram', 'observe', 'app_test_metric_sum'],
    'summary' => ['summary', 'getOrRegisterSummary', 'observe', 'app_test_metric_sum'],
])->with([true, false]);

it('preserves another writer update while waiting for the lock', function () {
    Sleep::fake(syncWithCarbon: true);

    $cache = new Repository(new ArrayStore);
    $registry = new CollectorRegistry(new LaravelCacheAdapter($cache, true), false);

    $lock = $cache->getStore()->lock('PROMETHEUS_counter_METRICS_LOCK', 60);
    $lock->get();

    Sleep::whenFakingSleep(function () use ($cache, $lock) {
        (new CollectorRegistry(new LaravelCacheAdapter($cache), false))
            ->getOrRegisterCounter('app', 'test_counter', 'help')->incBy(7);

        $lock->release();
    });

    $registry->getOrRegisterCounter('app', 'test_counter', 'help')->incBy(3);

    expect($registry->getMetricFamilySamples()[0]->getSamples()[0]->getValue())->toEqual(10);
});

it('rejects stores without lock support when locking is enabled', function () {
    $cache = new Repository(Mockery::mock(Store::class));
    $registry = new CollectorRegistry(new LaravelCacheAdapter($cache, true), false);

    expect(fn () => $registry->getOrRegisterCounter('app', 'test_counter', 'help')->inc())
        ->toThrow(InvalidArgumentException::class, 'The configured Prometheus cache store does not support locks.');
});

it('releases the lock when a cache write fails without retrying the update', function () {
    $store = new class extends ArrayStore
    {
        public bool $failNextWrite = true;

        public function forever($key, $value): bool
        {
            if ($this->failNextWrite) {
                $this->failNextWrite = false;

                throw new LockTimeoutException('Cache write failed.');
            }

            return parent::forever($key, $value);
        }
    };
    $cache = new Repository($store);
    $registry = new CollectorRegistry(new LaravelCacheAdapter($cache, true), false);
    $counter = $registry->getOrRegisterCounter('app', 'test_counter', 'help');

    expect(fn () => $counter->incBy(7))->toThrow(LockTimeoutException::class, 'Cache write failed.');
    expect($registry->getMetricFamilySamples())->toBeEmpty();

    $counter->incBy(3);

    expect($registry->getMetricFamilySamples()[0]->getSamples()[0]->getValue())->toEqual(3);
});
