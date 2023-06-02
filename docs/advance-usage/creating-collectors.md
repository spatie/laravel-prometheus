---
title: Creating collectors
weight: 2
---

When you create a package that contains metrics that should be easily be registered by your package users, you can create a collector.

A collector is a class that implements the `Spatie\Prometheus\Collectors` interface and is responsible for registering the metrics.

This is how the interface looks like.

```php
namespace Spatie\Prometheus\Collectors;

interface Collector
{
    public function register(): void;
}
```

In the `register` method of your collector, you should can register metrics, such as gauges. Here's an example collector take from our own package, that will register a gauge to export an horizon metric.

```php
namespace Spatie\Prometheus\Collectors\Horizon;

use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Spatie\Prometheus\Collectors\Collector;
use Spatie\Prometheus\Facades\Prometheus;

class CurrentMasterSupervisorCollector implements Collector
{
    public function register(): void
    {
        Prometheus::addGauge('Number of master supervisors')
            ->name('horizon_master_supervisors')
            ->helpText('The number of master supervisors')
            ->value(fn () => app(MasterSupervisorRepository::class)->all());
    }
}
```

Users of your package can register your collector by calling the `registerCollectorClasses` method on the `Prometheus` facade.

```php
// in a service provider

use Spatie\Prometheus\Facades\Prometheus;
use Spatie\Prometheus\Collectors\Horizon\CurrentMasterSupervisorCollector;

Prometheus::registerCollectorClasses([
    CurrentMasterSupervisorCollector::class,
]);
```
