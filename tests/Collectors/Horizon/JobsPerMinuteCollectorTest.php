<?php

use Spatie\Prometheus\Collectors\Horizon\JobsPerMinuteCollector;

it('can register the jobs per minute collector', function () {
    app(JobsPerMinuteCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
