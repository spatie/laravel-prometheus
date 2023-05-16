<?php

use Spatie\Prometheus\Facades\Prometheus;

it('can render a simple gauge', closure: function () {
    Prometheus::addGauge('my gauge', function () {
        return 123.45;
    });

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge with a plain value', closure: function () {
    Prometheus::addGauge('my gauge', 123.45);

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge with all options', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Gauge $gauge */
    $gauge = Prometheus::addGauge('my gauge');

    $gauge
        ->namespace('other_namespace')
        ->helpText('This is the help text')
        ->name('alternative_name')
        ->value(123.45);

    assertPrometheusResultsMatchesSnapshot();
});

it('will not fail with no metric types registered', function () {
    assertPrometheusResultsMatchesSnapshot();
});
