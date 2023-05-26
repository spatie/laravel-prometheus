<?php

use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Collectors\Horizon\CurrentWorkloadCollector;
use Spatie\Prometheus\Facades\Prometheus;

class PrometheusServiceProvider extends ServiceProvider
{
    public function register()
    {
        /*
         * Here you can register all the exporters that you
         * want to export to prometheus
         */
        Prometheus::addGauge('my_gauge', function () {
            return 123.45;
        });

        /*
         * Uncomment this line if you want to export
         * all Horizon metrics to prometheus
         */
        // $this ->registerHorizonCollectors();
    }

    public function registerHorizonCollectors(): self
    {
        Prometheus::registerCollectorClasses([
            CurrentWorkloadCollector::class,
        ]);

        return $this;
    }
}
