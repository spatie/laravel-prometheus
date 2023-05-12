<?php

namespace Spatie\Prometheus;

use Spatie\Prometheus\Actions\RenderCollectorsAction;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Collectors\Gauge;

class Prometheus
{
    /** @var array<\Spatie\Prometheus\Collectors\Collector> */
    protected array $collectors;

    public function addGauge(
        string $label,
        ?string $name = null,
        ?string $namespace = null,
        ?string $helpText = null,
        mixed $value = null,
    ): Gauge {
        $collector = new Gauge($label, $name, $namespace, $helpText, $value);

        $this->registerCollector($collector);

        return $collector;
    }

    public function registerCollector(Collector $collector): self
    {
        $this->collectors[] = $collector;

        return $this;

    }

    public function renderCollectors(): string
    {
        return app(RenderCollectorsAction::class)->execute($this->collectors);
    }
}
