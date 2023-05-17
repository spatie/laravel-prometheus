<?php

namespace Spatie\Prometheus\Collectors;

interface Collector
{
    public function register(): void;
}
