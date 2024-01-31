<?php

use Spatie\Prometheus\Facades\Prometheus;
use Spatie\Prometheus\Tests\TestSupport\Actions\TestRenderCollectorsAction;

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

it('can use a custom namespace', function () {
    config()->set('prometheus.default_namespace', 'custom_namespace');

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

it('will render the gauges on the default url', closure: function () {
    config()->set('prometheus.urls', [
        'default' => '/prometheus',
        'alternative' => '/my-alternative-route',
    ]);

    $this->reloadServiceProvider();

    Prometheus::addGauge('my default gauge', 123.45);
    Prometheus::addGauge('my alternative gauge', 123.45)->urlName('alternative');

    assertPrometheusResultsMatchesSnapshot();
});

it('will render the gauges on the alternative url', closure: function () {
    config()->set('prometheus.urls', [
        'default' => '/prometheus',
        'alternative' => '/my-alternative-route',
    ]);

    $this->reloadServiceProvider();

    Prometheus::addGauge('my default gauge', 123.45);
    Prometheus::addGauge('my alternative gauge', 123.45)->urlName('alternative');

    assertPrometheusResultsMatchesSnapshot('alternative');
});

it('will convert the gauge name to snake case', closure: function () {
    Prometheus::addGauge('My Gauge', 123.45);

    assertPrometheusResultsMatchesSnapshot();
});

it('can replace default render collectors action', closure: function () {
    config()->set('prometheus.actions.render_collectors', TestRenderCollectorsAction::class);

    Prometheus::addGauge('my gauge', 123.45);

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a simple counter with initial value', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Counter $counter */
    Prometheus::addCounter('my counter');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a counter with all options', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Counter $counter */
    Prometheus::addCounter('my counter')
        ->namespace('other_namespace')
        ->helpText('This is the help text')
        ->name('alternative_name')
        ->setInitialValue(123);

    assertPrometheusResultsMatchesSnapshot();
});

it('can increment a counter by one as default', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Counter $counter */
    Prometheus::addCounter('my counter')
        ->setInitialValue(1)
        ->inc();

    assertPrometheusResultsMatchesSnapshot();
});

it('can increment a counter by a custom value', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Counter $counter */
    $counter = Prometheus::addCounter('my counter');
    $counter->inc(2);

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a counter that returns a single result with labels in the closure', closure: function () {
    Prometheus::addCounter('my counter', function () {
        return [1, ['label_value']];
    })->label('label_name');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a counter that returns a multiple results with labels in the closure', closure: function () {
    Prometheus::addCounter('my counter', function () {
        return [
            [123, ['label_value']],
            [456, ['label_value_2']],
        ];
    })->label('label_name');

    assertPrometheusResultsMatchesSnapshot();
});

it('can render a counter with labels', function () {
    /** @var \Spatie\Prometheus\MetricTypes\Gauge $counter */
    $counter = Prometheus::addCounter('my counter');

    $counter
        ->namespace('other_namespace')
        ->labels(['label_name_1', 'label_name_2'])
        ->setInitialValue(123, ['label_value_1', 'label_value_2'])
        ->setInitialValue(124, ['label_value_3', 'label_value_4']);

    assertPrometheusResultsMatchesSnapshot();
});

it('will render the counters on the default url', closure: function () {
    config()->set('prometheus.urls', [
        'default' => '/prometheus',
        'alternative' => '/my-alternative-route',
    ]);

    $this->reloadServiceProvider();

    Prometheus::addCounter('my default counter', 123);
    Prometheus::addCounter('my alternative counter', 123)->urlName('alternative');

    assertPrometheusResultsMatchesSnapshot();
});

it('will render the counters on the alternative url', closure: function () {
    config()->set('prometheus.urls', [
        'default' => '/prometheus',
        'alternative' => '/my-alternative-route',
    ]);

    $this->reloadServiceProvider();

    Prometheus::addCounter('my default counter', 123);
    Prometheus::addCounter('my alternative counter', 123)->urlName('alternative');

    assertPrometheusResultsMatchesSnapshot('alternative');
});

it('will convert the counter name to snake case', closure: function () {
    Prometheus::addCounter('My Counter', 123);

    assertPrometheusResultsMatchesSnapshot();
});
