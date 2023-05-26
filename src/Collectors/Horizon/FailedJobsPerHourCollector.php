<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\JobRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class FailedJobsPerHourCollector implements Collector
{

    public function register(): void
    {
        Prometheus::addGauge('Failed Jobs Per Hour')
            ->helpText('The number of recently failed jobs')
            ->value(function() {
                return app(JobRepository::class)->countRecentlyFailed();
            });
    }
}
