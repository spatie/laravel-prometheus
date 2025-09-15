<?php

use Spatie\Prometheus\Collectors\Horizon\HorizonStatusCollector;
use Spatie\Prometheus\Collectors\Queue\QueueSizeCollector;
use Spatie\Prometheus\Facades\Prometheus;

it('can register collector classes', function () {
    Prometheus::registerCollectorClasses([
        HorizonStatusCollector::class,
    ]);

    assertPrometheusResultsMatchesSnapshot();
});

it('can register collector classes with constructor parameters', function () {
    Prometheus::registerCollectorClasses([
        QueueSizeCollector::class,
    ], ['connection' => 'redis', 'queues' => ['high', 'low']]);

    assertPrometheusResultsMatchesSnapshot();
});

it('can register multiple queue collector classes with constructor parameters', function () {
    Prometheus::registerCollectorClasses([
        QueueSizeCollector::class,
    ], ['connection' => 'database', 'queues' => ['critical', 'normal', 'low']]);

    assertPrometheusResultsMatchesSnapshot();
});
