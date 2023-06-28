<?php

namespace Spatie\Prometheus\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\Prometheus\MetricTypes\MetricType;

/**
 * @see \Spatie\Prometheus\Prometheus
 *
 * @method static \Spatie\Prometheus\MetricTypes\Gauge addGauge(string $name)
 * @method static \Spatie\Prometheus\Prometheus registerCollector(MetricType $collector)
 * @method static \Spatie\Prometheus\Prometheus registerCollectorClasses(array $collectors)
 * @method static string renderCollectors(string $urlName = 'default')
 */
class Prometheus extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Spatie\Prometheus\Prometheus::class;
    }
}
