<?php

namespace Spatie\Prometheus\Facades;

use Illuminate\Support\Facades\Facade;
use Spatie\Prometheus\MetricTypes\MetricType;

/**
 * @see \Spatie\Prometheus\Prometheus
 *
 * @method static \Spatie\Prometheus\MetricTypes\Gauge addGauge(string $label, float|callable|null $value = null, ?string $name = null, ?string $namespace = null, ?string $helpText = null)
 * @method static \Spatie\Prometheus\MetricTypes\Counter addCounter(string $label, int|callable|null $initialValue = null, ?string $name = null, ?string $namespace = null, ?string $helpText = null)
 * @method static self registerCollector(MetricType $collector)
 * @method static self registerCollectorClasses(array $collectors, array $constructorParameters = [])
 * @method static string renderCollectors(string $urlName = 'default')
 */
class Prometheus extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Spatie\Prometheus\Prometheus::class;
    }
}
