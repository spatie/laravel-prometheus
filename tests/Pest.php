<?php

use Spatie\Prometheus\Tests\TestCase;
use function Spatie\Snapshots\assertMatchesSnapshot;

uses(TestCase::class)->in(__DIR__);

function assertPrometheusResultsMatchesSnapshot()
{
    $content = test()
        ->get('prometheus')
        ->assertSuccessful()
        ->content();

    assertMatchesSnapshot($content);
}
