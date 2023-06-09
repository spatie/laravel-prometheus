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
