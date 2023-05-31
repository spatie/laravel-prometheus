<?php

namespace Spatie\Prometheus\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Prometheus\RenderTextFormat;
use Spatie\Prometheus\Prometheus;

class PrometheusMetricsController
{
    public function __invoke(Request $request, Prometheus $prometheus)
    {
        if (! config('prometheus.enabled')) {
            abort(403);
        }

        $routeName = $request->route()->getName();

        $prometheusUrlName = Str::after($routeName, 'prometheus.');

        $renderedCollectors = $prometheus->renderCollectors($prometheusUrlName);

        return response(
            $renderedCollectors,
            headers: ['Content-Type' => RenderTextFormat::MIME_TYPE]
        );
    }
}
