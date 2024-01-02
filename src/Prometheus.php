<?php

namespace Spatie\Prometheus;

use Spatie\Prometheus\Actions\RenderCollectorsAction;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\MetricTypes\Gauge;
use Spatie\Prometheus\MetricTypes\MetricType;

class Prometheus
{
    /** @var array<\Spatie\Prometheus\MetricTypes\MetricType> */
    protected array $collectors = [];

    public function addGauge(
        string $label,
        float|callable $value = null,
        string $name = null,
        string $namespace = null,
        string $helpText = null,
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

    public function registerCollectorClasses(array $collectors): self
    {
        collect($collectors)
            ->map(fn (string $collectorClass) => app($collectorClass))
            ->each(fn (Collector $collector) => $collector->register());

        return $this;
    }

    public function renderCollectors(string $urlName = 'default'): string
    {
        $collectorsForUrlName = collect($this->collectors)
            ->filter(fn (MetricType $metricType) => $metricType->getUrlName() === $urlName)
            ->toArray();

        $renderCollectorsClass = config('prometheus.actions.render_collectors');

        return app($renderCollectorsClass)->execute($collectorsForUrlName);
    }
}
