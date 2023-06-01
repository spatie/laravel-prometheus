<?php

namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class HorizonStatusCollector implements Collector
{
    protected const INACTIVE = -1;

    protected const PAUSED = 0;

    protected const RUNNING = 1;

    public function register(): void
    {
        Prometheus::addGauge('Horizon status')
            ->helpText('The status of Horizon, -1 = inactive, 0 = paused, 1 = running')
            ->value(function () {
                if (! $masters = app(MasterSupervisorRepository::class)->all()) {
                    return self::INACTIVE;
                }

                $isPaused = collect($masters)
                    ->contains(fn ($master) => $master->status === 'paused');

                return $isPaused
                    ? self::PAUSED
                    : self::RUNNING;
            });
    }
}
