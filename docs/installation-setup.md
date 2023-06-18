---
title: Installation & setup
weight: 4
---

You can install the package via composer:

```bash
composer require spatie/laravel-prometheus
```

Next, you should run the `prometheus:install` command.

```bash
php artisan prometheus:install
```

This will publish a config file named `prometheus.php` in your config with the following contents:

```php
return [
    'enabled' => true,

    /*
     * The urls that will return metrics.
     */
    'urls' => [
        'default' => 'prometheus',
    ],

    /*
     * Only these IP's will be allowed to visit the above urls.
     * When set to `null` all IP's are allowed.
     */
    'allowed_ips' => [
        // '1.2.3.4',
    ],

    /*
     * This is the default namespace that will be
     * used by all metrics
     */
    'default_namespace' => 'app',

    /*
     * The middleware that will be applied to the urls above
     */
    'middleware' => [
        Spatie\Prometheus\Http\Middleware\AllowIps::class,
    ],

    /*
     * You can override these classes to customize low-level behaviour of the package.
     * In most cases, you can just use the defaults.
     */
    'actions' => [
        'render_collectors' => Spatie\Prometheus\Actions\RenderCollectorsAction::class,
    ],
];
```

It will also create and register a service provider called `PrometheusServiceProvider`, where you can register your own collectors.

This is the default content of the `PrometheusServiceProvider`:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Prometheus\Collectors\Horizon\CurrentMasterSupervisorCollector;
use Spatie\Prometheus\Collectors\Horizon\CurrentProcessesPerQueueCollector;
use Spatie\Prometheus\Collectors\Horizon\CurrentWorkloadCollector;
use Spatie\Prometheus\Collectors\Horizon\FailedJobsPerHourCollector;
use Spatie\Prometheus\Collectors\Horizon\HorizonStatusCollector;
use Spatie\Prometheus\Collectors\Horizon\JobsPerMinuteCollector;
use Spatie\Prometheus\Collectors\Horizon\RecentJobsCollector;
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
        // $this->registerHorizonCollectors();
    }

    public function registerHorizonCollectors(): self
    {
        Prometheus::registerCollectorClasses([
            CurrentMasterSupervisorCollector::class,
            CurrentProcessesPerQueueCollector::class,
            CurrentWorkloadCollector::class,
            FailedJobsPerHourCollector::class,
            HorizonStatusCollector::class,
            JobsPerMinuteCollector::class,
            RecentJobsCollector::class,
        ]);

        return $this;
    }
}
```

### Configuring the metrics endpoint

By default, the metrics endpoint will be available at `/prometheus`. You can change this by changing the default url in the `prometheus.php` config file.

```php
// in config/prometheus.php
'urls' => [
    'default' => 'alternative-url',
],
```

### Securing the metrics endpoint

You probably don't want the endpoint that exposes your metrics to be publicly accessible. By adding an ip address to the `allowed_ips` key of the `prometheus.php` config file, you can restrict access to the endpoint to only that ip address.

In this example only requests from 1.2.3.4 can see the metrics. Requests from other IPs will get a 403 response.

```php
// in config/prometheus.php

'allowed_ips' => [
    '1.2.3.4',
],
```

### Exporting Horizon metrics

To export the Horizon metrics, uncomment this line in the default service provider:

```php
$this->registerHorizonCollectors();
```
