<?php

namespace Spatie\Prometheus\Tests;

use Laravel\Horizon\HorizonServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Prometheus\PrometheusServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        ray()->newScreen($this->name());
    }

    protected function getPackageProviders($app)
    {
        return [
            PrometheusServiceProvider::class,
            HorizonServiceProvider::class,
        ];
    }

    public function usingIp(?string $ip): self
    {
        if (! $ip) {
            return $this;
        }

        return $this->withServerVariables(['REMOTE_ADDR' => $ip]);
    }
}
