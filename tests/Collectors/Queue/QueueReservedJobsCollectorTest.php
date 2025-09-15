<?php

use Spatie\Prometheus\Collectors\Queue\QueueReservedJobsCollector;

it('can register the queue reserved jobs collector with default configuration', function () {
    app(QueueReservedJobsCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue reserved jobs collector with custom connection and queues', function () {
    $collector = new QueueReservedJobsCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});
