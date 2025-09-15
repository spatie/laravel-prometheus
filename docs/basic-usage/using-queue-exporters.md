---
title: Using Queue exporters
weight: 3
---

> **Important**: If you are using Laravel Horizon, you should use the [Horizon exporters](./using-horizon-exporters.md) instead of these queue exporters. Horizon provides its own comprehensive queue monitoring and these two exporters should not be used together as they may conflict or provide duplicate metrics.

We can export key metrics from Laravel's built-in queue system to Prometheus. To enable this feature, uncomment this line in the `app/Providers/PrometheusServiceProvider.php` file.

```php
$this->registerQueueCollectors(['default']);
```

This will register the following collectors for monitoring your Laravel queues:

- `queue_size`: exports the total number of jobs in each queue
- `queue_pending_jobs`: exports the number of pending jobs per queue
- `queue_delayed_jobs`: exports the number of delayed jobs per queue (supported drivers)
- `queue_reserved_jobs`: exports the number of reserved jobs per queue
- `queue_oldest_pending_job_age`: exports the age of the oldest pending job in seconds (supported drivers)

## Configuration

### Basic Usage

Register collectors for the default connection and default queue:

```php
$this->registerQueueCollectors(['default']);
```

### Custom Connection

Monitor queues on a specific connection:

```php
$this->registerQueueCollectors(['high', 'low'], 'redis');
```

## Prometheus Metrics

All metrics include `connection` and `queue` labels for filtering and aggregation:

```
# HELP app_queue_size The total number of jobs in the queue
# TYPE app_queue_size gauge
app_queue_size{connection="redis",queue="high"} 45
app_queue_size{connection="redis",queue="low"} 12

# HELP app_queue_delayed_jobs The number of delayed jobs in the queue
# TYPE app_queue_delayed_jobs gauge
app_queue_delayed_jobs{connection="redis",queue="high"} 3
app_queue_delayed_jobs{connection="redis",queue="low"} 0
```

## Individual Collectors

You can also register collectors individually with custom parameters in your `PrometheusServiceProvider`.

```php
use Spatie\Prometheus\Collectors\Queue\QueueSizeCollector;
use Spatie\Prometheus\Collectors\Queue\QueueDelayedJobsCollector;

Prometheus::registerCollectorClasses([
    QueueSizeCollector::class,
    QueueDelayedJobsCollector::class,
], ['connection' => 'redis', 'queues' => ['critical', 'high', 'normal']]);
```
