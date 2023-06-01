<?php

use Spatie\Prometheus\Collectors\Horizon\HorizonStatusCollector;

it('can register the horizon status collector', function () {
    app(HorizonStatusCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
