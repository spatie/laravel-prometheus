<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Collectors\Horizon\CurrentMasterSupervisorCollector;
use Spatie\Prometheus\Collectors\Horizon\CurrentProcessesPerQueueCollector;
use Spatie\Prometheus\Collectors\Horizon\CurrentWorkloadCollector;
use Spatie\Prometheus\Collectors\Horizon\FailedJobsPerHourCollector;
use Spatie\Prometheus\Collectors\Horizon\HorizonStatusCollector;
use Spatie\Prometheus\Collectors\Horizon\JobsPerMinuteCollector;
use Spatie\Prometheus\Collectors\Horizon\RecentJobsCollector;
use Spatie\Prometheus\Collectors\Queue\QueueDelayedJobsCollector;
use Spatie\Prometheus\Collectors\Queue\QueueOldestPendingJobCollector;
use Spatie\Prometheus\Collectors\Queue\QueuePendingJobsCollector;
use Spatie\Prometheus\Collectors\Queue\QueueReservedJobsCollector;
use Spatie\Prometheus\Collectors\Queue\QueueSizeCollector;
use Spatie\Prometheus\Facades\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        /*
         * Here you can register all the exporters that you
         * want to export to prometheus
         */
        Prometheus::addGauge('My gauge')
            ->value(function() {
                return 123.45;
            });

        /*
         * Uncomment this line if you want to export
         * all Horizon metrics to prometheus
         */
        // $this->registerHorizonCollectors();

        /*
         * Uncomment this line if you want to export queue metrics to Prometheus.
         * You need to pass an array of queues to monitor.
         */
        // $this->registerQueueCollectors(['default']);
    }

    public function registerHorizonCollectors(): self
    {
        Prometheus::registerCollectorClasses([
            CurrentMasterSupervisorCollector::class,
            CurrentProcessesPerQueueCollector::class,
            CurrentWorkloadCollector::class,
            FailedJobsPerHourCollector::class,
            HorizonStatusCollector::class,
            JobsPerMinuteCollector::class,
            RecentJobsCollector::class,
        ]);

        return $this;
    }

    public function registerQueueCollectors(array $queues = [], ?string $connection = null): self
    {
        Prometheus::registerCollectorClasses([
            QueueSizeCollector::class,
            QueuePendingJobsCollector::class,
            QueueDelayedJobsCollector::class,
            QueueReservedJobsCollector::class,
            QueueOldestPendingJobCollector::class,
        ], compact('connection', 'queues'));

        return $this;
    }
}
