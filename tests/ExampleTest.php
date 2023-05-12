<?php

use Spatie\Prometheus\Facades\Prometheus;
use function Spatie\Snapshots\assertMatchesSnapshot;

it('can render prometheus collectors', function () {
    Prometheus::addGauge('my gauge', function () {
        return 123.45;
    });

    $content = $this
        ->get('prometheus')
        ->assertSuccessful()
        ->content();

    assertMatchesSnapshot($content);
});
