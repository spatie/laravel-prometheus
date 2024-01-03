<?php

namespace Spatie\Prometheus\Tests\Actions;

use Prometheus\CollectorRegistry;
use Spatie\Prometheus\Actions\RenderCollectorsAction;

it('does not wipe storage by default', closure: function () {
    $mock = $this->mock(CollectorRegistry::class, function (\Mockery\MockInterface $mock) {
        $mock
            ->shouldReceive('getMetricFamilySamples')
            ->once()
            ->andReturn([]);

        $mock->shouldNotReceive('wipeStorage');
    });

    $subject = new RenderCollectorsAction($mock);
    $subject->execute([]);
});

it('wipes storage if config is set', closure: function () {
    config()->set('prometheus.wipe_storage_after_rendering', true);

    $mock = $this->mock(CollectorRegistry::class, function (\Mockery\MockInterface $mock) {
        $mock
            ->shouldReceive('getMetricFamilySamples')
            ->once()
            ->andReturn([]);

        $mock
            ->shouldReceive('wipeStorage')
            ->once();
    });

    $subject = new RenderCollectorsAction($mock);
    $subject->execute([]);
});
