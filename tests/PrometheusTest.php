<?php

use Spatie\Prometheus\Collectors\Horizon\HorizonStatusCollector;
use Spatie\Prometheus\Facades\Prometheus;

it('can register collector classes', function () {
    Prometheus::registerCollectorClasses([
        HorizonStatusCollector::class,
    ]);

    assertPrometheusResultsMatchesSnapshot();
});
