<?php

use Spatie\Prometheus\Collectors\Horizon\CurrentMasterSupervisorCollector;

it('can register the current master supervisor collector', function () {
    app(CurrentMasterSupervisorCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
