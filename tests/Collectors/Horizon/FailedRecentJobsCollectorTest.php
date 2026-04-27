<?php

use Spatie\Prometheus\Collectors\Horizon\FailedRecentJobsCollector;

it('can register the failed recent jobs collector', function () {
    app(FailedRecentJobsCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
