<?php

namespace Spatie\Prometheus\Collectors\Queue;

use Exception;
use Illuminate\Contracts\Queue\Factory;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class QueueOldestPendingJobCollector implements Collector
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
        Prometheus::addGauge('Queue oldest pending job age')
            ->name('queue_oldest_pending_job_age')
            ->label('connection')
            ->label('queue')
            ->helpText('The age of the oldest pending job in the queue (in seconds)')
            ->value(function () {
                $manager = app(Factory::class);
                $results = [];

                foreach ($this->queues as $queueName) {
                    try {
                        $queueConnection = $manager->connection($this->connection);

                        if (! method_exists($queueConnection, 'creationTimeOfOldestPendingJob')) {
                            continue;
                        }

                        $oldestJobTime = $queueConnection->creationTimeOfOldestPendingJob($queueName);

                        $ageInSeconds = $oldestJobTime === null
                            ? 0
                            : now()->timestamp - $oldestJobTime;

                        $results[] = [$ageInSeconds, [$this->connection, $queueName]];
                    } catch (Exception) {
                        // Reporting no value at all is better than reporting an age we could not read
                    }
                }

                return $results;
            });
    }
}
