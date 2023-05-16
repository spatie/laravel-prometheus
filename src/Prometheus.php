<?php

namespace Spatie\Prometheus;

use Spatie\Prometheus\Actions\RenderCollectorsAction;
use Spatie\Prometheus\MetricTypes\Gauge;
use Spatie\Prometheus\MetricTypes\MetricType;

class Prometheus
{
    /** @var array<\Spatie\Prometheus\MetricTypes\MetricType> */
    protected array $metrics = [];

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
        $this->metrics[] = $collector;

        return $this;

    }

    public function renderCollectors(): string
    {
        return app(RenderCollectorsAction::class)->execute($this->metrics);
    }
}
