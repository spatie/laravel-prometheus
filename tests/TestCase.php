<?php

namespace Spatie\Prometheus\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
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
