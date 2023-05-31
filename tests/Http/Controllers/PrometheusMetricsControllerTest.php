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
    Prometheus::addGauge('my gauge')
        ->namespace('other_namespace')
        ->helpText('This is the help text')
        ->name('alternative_name')
        ->value(123.45);

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge that returns a single result with labels in the closure', closure: function () {
    Prometheus::addGauge('my gauge', function () {
        return [123, ['label_value']];
    })->label('label_name');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge that returns a multiple results with labels in the closure', closure: function () {
    Prometheus::addGauge('my gauge', function () {
        return [
            [123, ['label_value']],
            [456, ['label_value_2']],
        ];
    })->label('label_name');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge that returns a multiple results without labels in the closure', closure: function () {
    Prometheus::addGauge('my gauge', function () {
        return [
            [123, ['label_value']],
            [456, ['label_value_2']],
        ];
    })->label('label_name');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a gauge with labels', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Gauge $gauge */
    $gauge = Prometheus::addGauge('my gauge');

    $gauge
        ->namespace('other_namespace')
        ->labels(['label_name_1', 'label_name_2'])
        ->value(123, ['label_value_1', 'label_value_2'])
        ->value(124, ['label_value_3', 'label_value_4']);

    assertPrometheusResultsMatchesSnapshot();
});

it('will not fail with no metric types registered', function () {
    assertPrometheusResultsMatchesSnapshot();
});
