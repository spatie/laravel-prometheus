<?php

use Spatie\Prometheus\Collectors\Horizon\CurrentProcessesPerQueueCollector;

it('can register the current processes per queue collector', function () {
    app(CurrentProcessesPerQueueCollector::class)->register();

    assertPrometheusResultsMatchesSnapshot();
});
