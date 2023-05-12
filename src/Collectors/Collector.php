<?php

namespace Spatie\Prometheus\Collectors;

use Prometheus\CollectorRegistry;

interface Collector
{
    public function register(CollectorRegistry $registry): Collector;
}
