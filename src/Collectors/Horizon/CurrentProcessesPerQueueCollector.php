<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\WorkloadRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class CurrentProcessesPerQueueCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Horizon Current Processes')
            ->helpText('Current processes of all queues')
            ->label('queue')
            ->value(function () {
                return collect(app(WorkloadRepository::class)->get())
                    ->sortBy('name')
                    ->values()
                    ->map(function (array $workload) {
                        return [$workload['processes'], [$workload['name']]];
                    })
                    ->toArray();
            });
    }
}
