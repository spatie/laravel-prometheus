---
title: Using Horizon exporters
weight: 2
---

We can export key metrics from Horizon to Prometheus. To enable this feature, uncomment this line in the `app/Providers/PrometheusServiceProvider.php` file.

```php
$this->registerHorizonCollectors();
```

This will register the following collectors:

- `horizon_master_supervisors`: exports the number of master supervisors.
- `horizon_current_processes`: exports the number of processes currently running per queue
- `horizon_current_workload`: exports the number of jobs currently waiting per queue.
- `horizon_failed_jobs_per_hour jobs`: exports the number of failed jobs in the past hour
- `horizon_status`: exports if the Horizon is running, paused, or inactive
- `horizon_jobs_per_minute`: exports the number of jobs processed in the last minute
- `horizon_recent_jobs`: exports the number of recent jobs
