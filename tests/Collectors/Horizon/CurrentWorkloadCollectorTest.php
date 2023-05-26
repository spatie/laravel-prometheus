<?php

use Spatie\Prometheus\Collectors\Horizon\CurrentWorkloadCollector;

it('can register the current workload collector', function () {
    app(CurrentWorkloadCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
