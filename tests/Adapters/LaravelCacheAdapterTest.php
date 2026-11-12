<?php

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
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

it('locks concurrent updates only when cache_lock is enabled', function (bool $lock, int $expected) {
    // A store that lets another process write right after the first one has read the stored metrics. That process
    // waits while a lock is held, as two processes on the same cache would, and writes at once when none is.
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

    $store->otherProcess = function () use ($lock) {
        (new CollectorRegistry(new LaravelCacheAdapter(Cache::store('interleaving'), $lock), false))
            ->getOrRegisterCounter('app', 'test_counter', 'help')->inc();
    };

    app(CollectorRegistry::class)->getOrRegisterCounter('app', 'test_counter', 'help')->inc();

    if ($store->waiting !== null) {
        ($store->waiting)();
    }

    $samples = (new CollectorRegistry(new LaravelCacheAdapter(Cache::store('interleaving')), false))->getMetricFamilySamples();

    expect($samples[0]->getSamples()[0]->getValue())->toEqual($expected);
})->with([
    'enabled' => [true, 2],
    'disabled, the default' => [false, 1],
]);

it('still updates the metric when the lock is not released in time', function () {
    $cache = new Repository(new ArrayStore);
    $cache->getStore()->lock('PROMETHEUS_counter_METRICS_LOCK', 60)->get();

    $adapter = new class($cache, true) extends LaravelCacheAdapter
    {
        protected int $lockWaitSeconds = 0;
    };
    (new CollectorRegistry($adapter, false))->getOrRegisterCounter('app', 'test_counter', 'help')->inc();

    $samples = (new CollectorRegistry(new LaravelCacheAdapter($cache), false))->getMetricFamilySamples();

    expect($samples[0]->getSamples()[0]->getValue())->toEqual(1);
});
