<?php

namespace Spatie\Prometheus\Actions;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Spatie\Prometheus\Collectors\Collector;

class RenderCollectorsAction
{
    public function __construct(protected CollectorRegistry $registry)
    {
    }

    public function execute(array $collectors): string
    {
        collect($collectors)
            ->each(fn (Collector $gauge) => $gauge->register($this->registry));

        return $this->renderRegistry();
    }

    protected function renderRegistry(): string
    {
        $renderer = new RenderTextFormat();

        return $renderer->render($this->registry->getMetricFamilySamples());
    }
}
