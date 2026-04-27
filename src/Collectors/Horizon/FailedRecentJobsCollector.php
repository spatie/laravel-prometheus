<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\JobRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class FailedRecentJobsCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Failed Recent Jobs')
            ->name('horizon_failed_recent_jobs')
            ->helpText('The number of recently failed jobs')
            ->value(fn () => app(JobRepository::class)->countRecentlyFailed());
    }
}
