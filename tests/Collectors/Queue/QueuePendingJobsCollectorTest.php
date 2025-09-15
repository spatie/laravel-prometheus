<?php

use Spatie\Prometheus\Collectors\Queue\QueuePendingJobsCollector;

it('can register the queue pending jobs collector with default configuration', function () {
    app(QueuePendingJobsCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue pending jobs collector with custom connection and queues', function () {
    $collector = new QueuePendingJobsCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});
