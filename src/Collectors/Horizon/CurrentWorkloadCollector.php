<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\WorkloadRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class CurrentWorkloadCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Horizon current workload')
            ->label('queue')
            ->helpText('Current workload of all queues')
            ->value(function () {
                return collect(app(WorkloadRepository::class)->get())
                    ->sortBy('name')
                    ->values()
                    ->map(fn(array $workload) => [$workload['processes'], [$workload['name']]])
                    ->toArray();
            });
    }
}
