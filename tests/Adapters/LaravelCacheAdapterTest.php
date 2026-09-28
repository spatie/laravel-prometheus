<?php

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;
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
