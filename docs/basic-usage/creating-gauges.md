---
title: Creating gauges
weight: 1
---

To export your first metric to Prometheus, you should call `Prometheus::addGauge` method. This can be done anywhere in your code, but typically it's done in the `app/Providers/PrometheusServiceProvider.php` file that was published when installing the package.

```php
Prometheus::addGauge('My gauge')
    ->value(fn() => 123.45);
```

This will create a gauge metric named `my_gauge` with the value of `123.45`. The metric will be present on the `/prometheus` endpoint.

You can add as many gauges as you want. Here's an example where we export the user count.

```php
Prometheus::addGauge('User count')
    ->value(fn() => User::count();
```

## Adding a help text

You can add a help text to your metric by chaining the `helpText` method.

```php
Prometheus::addGauge('User count')
    ->helpText('This is the number of users in our app')
    ->value(fn() => User::count();
```

## Setting a namespace

When exporting the metrics, a namespace value will be prefixed to the metric name. By default, the namespace is set to `app`. So, when you export a gauge named `User count`, the metric name will be `app_user_count`.

You can change the default namespace in the `namespace` key of the `config/prometheus.php` file.

To change the namespace of a specific gauge, you can chain the `namespace` method.

```php
Prometheus::addGauge('User count')
    ->namespace('My custom namespace')
    ->value(fn() => User::count();
```

The above gauge will be exported as `my_custom_namespace_user_count`.

## Using labels

Labels are a powerful feature of Prometheus. They allow you to add additional dimensions to your metrics. For example, you can add a label to the `User count` gauge to distinguish between active and inactive users.

To start using a label, you should call the `label` method on the gauge and pass the label name.

The callable passed to `value` should return an array of tuples. Each tuple should contain the value and an array of labels. The number of labels should match the number of labels defined on the gauge.

```php
Prometheus::addGauge('User count')
    ->label('status')
    ->value(function() {
        return [
            [User::where('status', 'active')->count(), ['active']],
            [User::where('status', 'inactive')->count(), ['inactive']],
        ];
    });
```

## Alternative syntax

Instead of using multiple methods, you can also use named arguments to set the gauge properties.

```php
Prometheus::addGauge(
    name: 'User count',
    helpText: 'This is the number of users in our app',
    namespace: 'My custom namespace',
    labels: ['status'],
    value: function() {
        return [
            [User::where('status', 'active')->count(), ['active']],
            [User::where('status', 'inactive')->count(), ['inactive']],
        ];
    }
);
```
