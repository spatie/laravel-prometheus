---
title: v1
slogan: Export Laravel metrics to Prometheus
githubUrl: https://github.com/spatie/laravel-prometheus
branch: main
---

This packages make it easy to export the key metrics of your Laravel application with [Prometheus](https://prometheus.io/docs/introduction/overview/). Typically, you would use this package in combination with Prometheus / Grafana to monitor the health of your application.

Exporting data to Prometheus is very straightforward.

```php
// typically in a service provider
use \Spatie\Prometheus\Facades\Prometheus;

Prometheus::addGauge('Number of users')
    ->value(fn() =>  User::count());
```

This will expose a metric named `Number of users` with the current number of users as value. By default, the metric will be exposed on the `/prometheus` endpoint. 

```php 

Additionally, this package can export Horizon metrics, so you can easily build a graph that shows the number of jobs in the queue.

// TODO insert screenshot
