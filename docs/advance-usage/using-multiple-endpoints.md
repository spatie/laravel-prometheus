---
title: Creating multiple endpoints
weight: 1
---

By default, all metrics are exported at the default `/prometheus` endpoint. You can configure Prometheus to periodically scrape metrics from this endpoint using a specified frequency.

If you want to export metrics that should be exported with an alternative frequency, you can create a new endpoint and configure Prometheus to scrape metrics from this endpoint using an alternative frequency.

To use multiple endpoints, you first need to configure them in the `prometheus.php` config file. Here's an example, where we configure an alternative endpoint

```php
// in config/prometheus.php
    'urls' => [
        'default' => 'prometheus',
        'alternative' => 'alternative-endpoint',
    ],
```

To expose a metric at the alternative endpoint, you can use the `urlName` method on the metric. Here's an example:

```php
use Spatie\Prometheus\Facades\Prometheus;

Prometheus::addGauge('User count')
    ->urlName('alternative')
    ->value(fn() => User::count();
```
