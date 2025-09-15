<?php

use Spatie\Prometheus\Collectors\Queue\QueueOldestPendingJobCollector;

it('can register the queue oldest pending job collector with default configuration', function () {
    app(QueueOldestPendingJobCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue oldest pending job collector with custom connection and queues', function () {
    $collector = new QueueOldestPendingJobCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});