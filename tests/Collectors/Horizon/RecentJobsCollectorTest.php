<?php

use Spatie\Prometheus\Collectors\Horizon\RecentJobsCollector;

it('can register the recent jobs collector', function () {
    app(RecentJobsCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
