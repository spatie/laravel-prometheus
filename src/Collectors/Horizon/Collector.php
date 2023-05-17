<?php

namespace Spatie\Prometheus\Collectors\Horizon;

interface Collector
{
    public function register(): void;
}
