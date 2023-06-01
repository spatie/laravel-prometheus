<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\JobRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class RecentJobsCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Recent jobs')
            ->name('horizon_recent_jobs')
            ->helpText('The number of recent jobs')
            ->value(fn () => app(JobRepository::class)->countRecent());
    }
}
