<?php

namespace Spatie\Prometheus;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Prometheus\Commands\PrometheusCommand;

class PrometheusServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-prometheus')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-prometheus_table')
            ->hasCommand(PrometheusCommand::class);
    }
}
