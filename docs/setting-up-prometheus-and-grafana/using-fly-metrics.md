---
title: Using fly.io
weight: 4
---

Applications hosted on [fly.io](https://fly.io) get a Grafana dashboard automatically (available at [fly-metrics.com](https://fly-metrics.net)).

You can publish your custom metrics to Fly, and view them in this hosted Grafana dashboard.

## Configuring Your Fly App

Fly.io will scrape prometheus metrics automatically - we just need to tell it where to find them.

Assuming your application is outputing prometheus metrics at the default `/prometheus` endpoint, you can add the following [`[metrics]` configuring](https://fly.io/docs/reference/metrics/#custom-metrics) to your app's `fly.toml` file:

```toml
[metrics]
port = 8080 # Match your "internal_port" config
path = "/prometheus" # default for this package
```

After you make this configuration change, you'll need to deploy your app for it to take effect. Run `fly deploy`, and Fly will begin scraping metrics.

Metrics will be available at [fly-metrics.com](https://fly-metrics.net), where you can create new dashboards/graphs using your custom metrics via the "Prometheus on Fly" data source.

## Creating a Dashboard

From within [fly-metrics.com](https://fly-metrics.net), you can choose to create a new Dashboard. From there, you can add a new panel.

![fly-metrics.net dashboard](/docs/laravel-prometheus/v1/images/add-dashboard-fly.png)

To find your metrics, choose the "Prometheus on Fly" data source.

![fly-metrics.net graph](/docs/laravel-prometheus/v1/images/add-dashboard-fly.png)

From here on, you can create your dashboard as you would normally do in Grafana. For more information on how to create dashboards, please refer to the [Grafana documentation](https://grafana.com/docs/grafana/latest/guides/getting_started/).
