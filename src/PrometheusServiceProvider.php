<?php

namespace Spatie\Prometheus;

use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Spatie\Health\Health;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Prometheus\Commands\PrometheusCommand;
use Spatie\Prometheus\Controllers\PrometheusMetricsController;
use \Illuminate\Support\Facades\Route;
use Spatie\Prometheus\Middleware\AllowIps;

class PrometheusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prometheus')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->scoped(Prometheus::class);
        $this->app->alias(Prometheus::class, 'prometheus');

        $this->app->scoped(CollectorRegistry::class, function() {
            return new CollectorRegistry(new InMemory());
        });
    }

    public function packageBooted()
    {
        $this->registerEndpoint();
    }

    protected function registerEndpoint(): self
    {
        if (! config('prometheus.enabled')) {
            return $this;
        }

        if (! $url = config('prometheus.url')) {
            return $this;
        }

        Route::get($url, PrometheusMetricsController::class)
            ->middleware(AllowIps::class);

        return $this;
    }
}
