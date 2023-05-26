<?php

use Spatie\Prometheus\Collectors\Horizon\FailedJobsPerHourCollector;

it('can register the failed jobs per hour collector', function () {
    app(FailedJobsPerHourCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
