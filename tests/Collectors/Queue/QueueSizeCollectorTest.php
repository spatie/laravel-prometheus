<?php

use Spatie\Prometheus\Collectors\Queue\QueueSizeCollector;

it('can register the queue size collector with default configuration', function () {
    app(QueueSizeCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue size collector with custom connection and queues', function () {
    $collector = new QueueSizeCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});
