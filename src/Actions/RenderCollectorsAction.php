<?php

namespace Spatie\Prometheus\Actions;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Spatie\Prometheus\MetricTypes\MetricType;

class RenderCollectorsAction
{
    public function __construct(protected CollectorRegistry $registry)
    {
    }

    public function execute(array $collectors): string
    {
        collect($collectors)
            ->each(fn (MetricType $gauge) => $gauge->register($this->registry));

        return $this->renderRegistry();
    }

    protected function renderRegistry(): string
    {
        $renderer = new RenderTextFormat();

        $result = $renderer->render($this->registry->getMetricFamilySamples());

        $this->registry->wipeStorage();

        return $result;
    }
}
