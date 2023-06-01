<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\MetricsRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class JobsPerMinuteCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('jobs per minute')
            ->name('horizon_jobs_per_minute')
            ->helpText('The number of jobs per minute')
            ->value(fn () => app(MetricsRepository::class)->jobsProcessedPerMinute());
    }
}
