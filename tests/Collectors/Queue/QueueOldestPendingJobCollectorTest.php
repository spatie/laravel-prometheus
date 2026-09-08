<?php

use Illuminate\Contracts\Queue\Factory;
use Illuminate\Contracts\Queue\Queue;
use Spatie\Prometheus\Collectors\Queue\QueueOldestPendingJobCollector;

function fakeQueueConnection(Closure $creationTimeOfOldestPendingJob): void
{
    $queue = Mockery::mock(Queue::class);
    $queue->shouldReceive('creationTimeOfOldestPendingJob')->andReturnUsing($creationTimeOfOldestPendingJob);

    $factory = Mockery::mock(Factory::class);
    $factory->shouldReceive('connection')->andReturn($queue);

    app()->instance(Factory::class, $factory);
}

it('can register the queue oldest pending job collector with default configuration', function () {
    app(QueueOldestPendingJobCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('can register the queue oldest pending job collector with custom connection and queues', function () {
    $collector = new QueueOldestPendingJobCollector('redis', ['high', 'low']);
    $collector->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('reports the age of the oldest pending job', function () {
    $this->freezeTime();

    fakeQueueConnection(fn () => now()->timestamp - 60);

    (new QueueOldestPendingJobCollector('redis', ['high']))->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('reports a zero age for a queue that has drained', function () {
    fakeQueueConnection(fn () => null);

    (new QueueOldestPendingJobCollector('redis', ['high', 'low']))->register();

    assertPrometheusResultsMatchesSnapshot();
});

it('does not report an age for a queue that could not be read', function () {
    $this->freezeTime();

    fakeQueueConnection(fn (string $queueName) => $queueName === 'unreachable'
        ? throw new Exception('Could not connect to the queue')
        : now()->timestamp - 30);

    (new QueueOldestPendingJobCollector('redis', ['high', 'unreachable']))->register();

    assertPrometheusResultsMatchesSnapshot();
});
