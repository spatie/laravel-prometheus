<?php

namespace Spatie\Prometheus;

use Illuminate\Support\Facades\Route;
use Prometheus\CollectorRegistry;
use Prometheus\Storage\InMemory;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Prometheus\Http\Controllers\PrometheusMetricsController;
use Spatie\Prometheus\Http\Middleware\AllowIps;

class PrometheusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prometheus')
            ->hasInstallCommand(function (InstallCommand $installer) {
                $installer
                    ->publishConfigFile()
                    ->copyAndRegisterServiceProviderInApp()
                    ->askToStarRepoOnGitHub('spatie/laravel-prometheus');
            })
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        $this->app->scoped(Prometheus::class);
        $this->app->alias(Prometheus::class, 'prometheus');

        $this->app->scoped(CollectorRegistry::class, function () {
            return new CollectorRegistry(new InMemory());
        });
    }

    public function packageBooted()
    {
        $this->registerUrls();
    }

    protected function registerUrls(): self
    {
        if (! config('prometheus.enabled')) {
            return $this;
        }

        foreach(config('prometheus.urls') as $name => $url) {
            Route::get($url, PrometheusMetricsController::class)
                ->middleware(AllowIps::class)
                ->name("prometheus.{$name}");
        }

        return $this;
    }
}
