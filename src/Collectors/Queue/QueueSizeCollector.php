<?php

namespace Spatie\Prometheus\Collectors\Queue;

use Illuminate\Contracts\Queue\Factory;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class QueueSizeCollector implements Collector
{
    protected string $connection;

    protected array $queues;

    public function __construct(?string $connection = null, array $queues = [])
    {
        $this->connection = $connection ?? config('queue.default');
        $this->queues = empty($queues)
            ? [config("queue.connections.{$this->connection}.queue", 'default')]
            : $queues;
    }

    public function register(): void
    {
        Prometheus::addGauge('Queue size')
            ->name('queue_size')
            ->label('connection')
            ->label('queue')
            ->helpText('The total number of jobs in the queue')
            ->value(function () {
                $manager = app(Factory::class);
                $results = [];

                foreach ($this->queues as $queueName) {
                    try {
                        $queueConnection = $manager->connection($this->connection);
                        $size = $queueConnection->size($queueName);

                        $results[] = [$size, [$this->connection, $queueName]];
                    } catch (\Exception $e) {
                        $results[] = [0, [$this->connection, $queueName]];
                    }
                }

                return $results;
            });
    }
}
