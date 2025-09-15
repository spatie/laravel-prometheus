---
title: Introduction
weight: 1
---

This package can export key metrics of your app to [Prometheus](https://prometheus.io). It does this by providing an easy way to register metrics. Here's an example where we are going to export the user count to Prometheus.

```php
Prometheus::addGauge('User count')
    ->value(fn() => User::count());
```

These metrics will be exposed at the `/prometheus` endpoint. The package offers a way to add a security layer, so your key metrics don't become public.

You can configure your Prometheus instance to periodically crawl and import the metrics at the `/prometheus` endpoint of your app. Using [Grafana](https://grafana.com), you can visualize the data points that are stored in Prometheus.

## Built-in Collectors

The package includes several built-in collectors for common Laravel functionality:

### Queue Metrics
Monitor your Laravel queues with comprehensive metrics including queue sizes, pending jobs, delayed jobs, and more. Supports all Laravel queue drivers (Redis, Database, SQS, etc.) with graceful fallbacks. If you're using Horizon, please use the Horizon collectors listed below instead!

```php
// Enable all queue collectors
$this->registerQueueCollectors(['high', 'normal', 'low'], 'redis');
```

### Horizon Metrics
Export Laravel Horizon metrics including supervisor status, workload distribution, job throughput, and failure rates.

```php
// Enable all Horizon collectors
$this->registerHorizonCollectors();
```
