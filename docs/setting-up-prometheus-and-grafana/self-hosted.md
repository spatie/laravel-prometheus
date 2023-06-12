---
title: Self-hosted
weight: 4
---

To receive metrics from your applications using Prometheus, and visualize them using Grafana, you can use a docker image that is deployed on your server.

Here are the general steps to set up Prometheus and Grafana via Laravel Forge. This isn't meant as a full guide, but rather as a starting point for you to get started.

## 1. Provision a server on Laravel Forge and install Prometheus and Grafana on it
- Connect to your server via SSH.
- Install Prometheus by following [the official installation guide](https://prometheus.io/docs/prometheus/latest/installation/)
- Install Grafana by following [the official installation guide](https://grafana.com/docs/grafana/latest/installation/)

## 2. Configure Prometheus to scrape the metrics from your application and store them in its database
- Edit the Prometheus configuration file `/etc/prometheus/prometheus.yml` and add a new job to scrape metrics from your application. For example:

```yaml
scrape_configs:
- job_name: laravel
  scrape_interval: 10s
  metrics_path: /prometheus
  static_configs:
    - targets: ['your-laravel-app.com']
```

This configuration tells Prometheus to scrape metrics from your application every 10 seconds and store them in its database.

## 3. Configure Grafana to connect to Prometheus and visualize the metrics:
- Open Grafana in your web browser by visiting http://your-server-ip:3000.
- Log in with the default credentials (username: admin, password: admin).
- Add a new Prometheus data source by clicking on the gear icon on the left sidebar, then selecting "Data Sources", then "Add data source".
- Fill in the form with the following details:

Name: Prometheus
Type: Prometheus
URL: http://localhost:9090

This configuration tells Grafana to connect to Prometheus at http://localhost:9090.

- Create a new dashboard by clicking on the plus icon on the left sidebar, then selecting "Dashboard", then "Add new panel".
- Choose a visualization type (e.g. graph, gauge, table) and configure it to display the metrics you want to monitor.
- Save the dashboard and view it to see the metrics in real-time.

That's it! You should now have Prometheus and Grafana set up to monitor your application's metrics.
