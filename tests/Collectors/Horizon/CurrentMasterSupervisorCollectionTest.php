<?php

use Spatie\Prometheus\Collectors\Horizon\CurrentMasterSupervisorCollector;
use Spatie\Prometheus\Collectors\Horizon\CurrentWorkloadCollector;

it('can register the current master supervisor collector', function() {
   app(CurrentMasterSupervisorCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
