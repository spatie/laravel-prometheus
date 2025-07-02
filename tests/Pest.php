<?php

use Spatie\Prometheus\Tests\TestCase;

use function Spatie\Snapshots\assertMatchesSnapshot;

uses(TestCase::class)->in(__DIR__);

function assertPrometheusResultsMatchesSnapshot(string $urlName = 'default')
{
    $content = test()
        ->get(route("prometheus.{$urlName}"))
        ->assertSuccessful()
        ->content();

    assertMatchesSnapshot(str($content)->trim()->value());
}
