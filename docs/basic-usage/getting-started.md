---
title: Getting started
weight: 1
---

To export your first metric to Prometheus, you should call `Prometheus::addGauge` method. This can be done anywhere in your code, but typically it's done in the `app/Providers/PrometheusServiceProvider.php` file that was published when installing the package.

```php
Prometheus::addGauge('my_gauge', function () {
    return 123.45;
});
```
