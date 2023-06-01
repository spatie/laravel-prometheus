<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\WorkloadRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class CurrentWorkloadCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Current workload')
            ->name('horizon_current_workload')
            ->label('queue')
            ->helpText('Current workload of all queues')
            ->value(function () {
                return collect(app(WorkloadRepository::class)->get())
                    ->sortBy('name')
                    ->values()
                    ->map(fn (array $workload) => [$workload['length'], [$workload['name']]])
                    ->toArray();
            });
    }
}
