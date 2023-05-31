<?php

namespace Spatie\Prometheus;

use Spatie\Prometheus\Actions\RenderCollectorsAction;
use Spatie\Prometheus\MetricTypes\Gauge;
use Spatie\Prometheus\MetricTypes\MetricType;

class Prometheus
{
    /** @var array<\Spatie\Prometheus\MetricTypes\MetricType> */
    protected array $collectors = [];

    public function addGauge(
        string $label,
        null|float|callable $value = null,
        ?string $name = null,
        ?string $namespace = null,
        ?string $helpText = null,
    ): Gauge {
        $collector = new Gauge($label, $value, $name, $namespace, $helpText);

        $this->registerCollector($collector);

        return $collector;
    }

    public function registerCollector(MetricType $collector): self
    {
        $this->collectors[] = $collector;

        return $this;
    }

    public function renderCollectors(string $urlName = 'default'): string
    {
        $collectorsForUrlName = collect($this->collectors)
            ->filter(fn(MetricType $metricType) => $metricType->getUrlName() === $urlName)
            ->toArray();

        return app(RenderCollectorsAction::class)->execute($collectorsForUrlName);
    }
}
