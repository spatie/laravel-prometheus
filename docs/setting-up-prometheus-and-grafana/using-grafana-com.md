---
title: Using grafana.com
weight: 2
---

The easiest way to get started using Prometheus and visualizing data via Grafana is by creating a free account on [grafana.com](https://grafana.com) and using the hosted Grafana instance.

During this process, you'll install the Grafana agent, which will read the metrics on your `/prometheus` endpoint, and push them to Grafana.com.

## Configuring Grafana

Once your account has been created, you'll be on your account dashboard. There, you should launch your Grafana instance by clicking the "Launch" button.

![Grafana.com dashboard](/docs/images/launch-grafana.jpg)

At this point, you'll be redirected to your Grafana instance. There, you must go to "Connections" and add a new connection of type "Hosted prometheus Metrics".

![Grafana.com new connection](/docs/images/new-connection.jpg)

When creating a new connection, choose "Via Grafana Agent".

![Grafana.com agent](/docs/images/grafana-agent.jpg).

Next, follow the wizard, install the agent, and create a new config.

![Grafana.com config](/docs/images/new-config.jpg).

Follow, the steps to create the config file, and start the agent on your server. To keep the agent running, you might use something like [Supervisord](http://supervisord.org) (Laravel Forge users can just create [a daemon](https://forge.laravel.com/docs/1.0/resources/daemons.html))

In the `scrape_configs` key of the config, you should add a job to scrape the `/prometheus` endpoint of your Laravel application. For example:

```yaml
  global:
      scrape_interval: 10s
  configs:
      - name: hosted-prometheus
        scrape_configs:
            - job_name: laravel
              scrape_interval: 10s
              metrics_path: /prometheus
              static_configs:
                  - targets: ['your-app.com']
        remote_write:
            - url: <filled in by the wizard>
              basic_auth:
                  username: <filled in by the wizard>
                  password: <filled in by the wizard>
```

Of course, you should replace `your-app.com` with the domain of your application.

## Creating a dashboard

Once you've configured the agent, you can create a new dashboard. Head over to "Dashboards" and create a new dashboard.

On that screen, click "+ Add Visualization"

![Grafana.com visualization](/docs/images/add-dashboard.jpg).

Next, click your hosted prometheus instance as the source.

![Grafana.com visualization](/docs/images/prometheus-source.jpg).

In the metric dropdown, you should see all the metrics that are being scraped from your Laravel application.

![Grafana.com metrics](/docs/images/metrics.jpg).

From here on, you can create your dashboard as you would normally do in Grafana. For more information on how to create dashboards, please refer to the [Grafana documentation](https://grafana.com/docs/grafana/latest/guides/getting_started/).
