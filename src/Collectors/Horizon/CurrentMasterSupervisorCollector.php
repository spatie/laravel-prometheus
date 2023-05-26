<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\JobRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class CurrentMasterSupervisorCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Number of master supervisors')
            ->helpText('The number of recently failed jobs')
            ->value(fn () => app(JobRepository::class)->countRecentlyFailed());
    }
}
