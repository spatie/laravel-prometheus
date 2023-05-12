<?php

namespace Spatie\Prometheus\Http\Controllers;

use Prometheus\RenderTextFormat;
use Spatie\Prometheus\Prometheus;

class PrometheusMetricsController
{
    public function __invoke(Prometheus $prometheus)
    {
        $renderedCollectors = $prometheus->renderCollectors();

        return response($renderedCollectors, headers: ['Content-Type' => RenderTextFormat::MIME_TYPE]);
    }
}
