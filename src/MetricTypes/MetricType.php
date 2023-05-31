<?php

namespace Spatie\Prometheus\MetricTypes;

use Prometheus\CollectorRegistry;

interface MetricType
{
    public function register(CollectorRegistry $registry): MetricType;

    public function getUrlName(): string;
}
