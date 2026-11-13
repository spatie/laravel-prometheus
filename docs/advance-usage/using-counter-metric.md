---
title: Using counter type metric
weight: 3
---

## Using Counter Type Metric

**Overview**

A Counter is a metric that only increases in value. It is typically used to count events, such as the number of requests received or tasks completed.

**Creating a Counter**

You can create a Counter metric using the ``addCounter`` method:

```php
$counter = Prometheus::addCounter('my_counter');
```

**Setting an Initial Value**

You can also define an initial value for your counter when creating it. This is useful if you want to start counting from a specific number:

```php
$counter = Prometheus::addCounter('my counter')->setInitialValue(100);
```

**Incrementing the Counter**

To increment the counter, you can use the ``inc`` method:

```php
$counter->inc();
```

This will increase the counter by one.

If you need to increment the counter by a value greater than one, you can pass the value as an argument to the ``inc()`` method:

```php
$counter->inc(2);
```

**Best Practices**

* **Naming Conventions:** Use descriptive names for your counters to make it clear what they are tracking. For example, ``user_registration_total`` or ``api_request_count``.
* **Atomic Increments:** Ensure that increments are atomic, especially in multi-threaded or multi-process environments, to avoid race conditions.

## Cache Configuration

By default, the cache setting is set to ``null``, which means that the metric will be stored in memory without using Laravel's caching system. This is suitable for simple setups or testing environments.

### Configuring Cache

You can configure the cache in the config/prometheus.php file:

```php
/**
* Select a cache to store gauges, counters, summaries and histograms between requests.
* In a multi node setup you should ensure that each node writes to its own
* cache instance or uses a node specific prefix.
* Configure the cache store in config/cache.php.
*
* to use an in memory adapter for testing use array or null as your store
* or remove the cache entry all together:
*  'cache' => null       // InMemory implementation without laravel cache
*  'cache' => 'array'    // InMemory implementation using laravel cache
*/
'cache' => null,
```

### Cache Options

* **In-Memory Cache:** If you want to use an in-memory cache without relying on Laravel's cache system, keep the cache option as null.
* **Laravel Cache:** To store metrics in Laravel's cache, set the cache option to a valid cache driver defined in your ``config/cache.php`` file:
  ```php
  'cache' => 'redis', // Using Redis for caching metrics
  ```

### Concurrent updates

Cached metrics are read, changed and written back. Concurrent requests, queue workers or Octane workers can overwrite each other's updates, even when they update different metrics of the same type.

Enable `cache_lock` in `config/prometheus.php` to serialize updates per metric type:

```php
'cache_lock' => true,
```

All writers sharing the metrics must enable this option and use the same shared cache and lock store. Array stores only coordinate within one process; file stores require a shared filesystem. Acquiring and releasing a lock adds cache operations to each update.

When enabled, an update waits up to 3 seconds to acquire the lock. If it times out, Laravel's `Illuminate\Contracts\Cache\LockTimeoutException` is thrown and the metric is not updated. Stores without lock support throw `InvalidArgumentException`. There is no unlocked fallback. Leave `cache_lock` disabled if you prefer the previous best-effort behavior.

Locks expire after 5 seconds to recover from terminated workers. Updates must finish within that lease; slower updates can still overlap after it expires. Locking does not make collecting and wiping metrics atomic, so avoid `wipe_storage_after_rendering` when concurrent writers must retain every update.

### Conclusion
By properly configuring the cache, you can ensure that your metrics are persisted and shared across requests, making them more reliable in production environments.
