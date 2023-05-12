<?php

namespace Spatie\Prometheus\Collectors;

use Prometheus\CollectorRegistry;
use Prometheus\Gauge as PrometheusGauge;

interface Collector
{
    public function register(CollectorRegistry $registry): Collector;
}
