<?php

namespace Spatie\Prometheus\Collectors\Queue;

use Illuminate\Contracts\Queue\Factory;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class QueuePendingJobsCollector implements Collector
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
        Prometheus::addGauge('Queue pending jobs')
            ->name('queue_pending_jobs')
            ->label('connection')
            ->label('queue')
            ->helpText('The number of pending jobs in the queue')
            ->value(function () {
                $manager = app(Factory::class);
                $results = [];

                foreach ($this->queues as $queueName) {
                    try {
                        $queueConnection = $manager->connection($this->connection);

                        if (method_exists($queueConnection, 'pendingSize')) {
                            $pendingSize = $queueConnection->pendingSize($queueName);
                        } else {
                            $pendingSize = 0;
                        }

                        $results[] = [$pendingSize, [$this->connection, $queueName]];
                    } catch (\Exception $e) {
                        // Skip this queue if there's an error
                    }
                }

                return $results;
            });
    }
}
