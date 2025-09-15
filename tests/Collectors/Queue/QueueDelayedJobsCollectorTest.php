<?php

use Spatie\Prometheus\Collectors\Queue\QueueDelayedJobsCollector;

it('can register the queue delayed jobs collector with default configuration', function () {
    app(QueueDelayedJobsCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue delayed jobs collector with custom connection and queues', function () {
    $collector = new QueueDelayedJobsCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});
