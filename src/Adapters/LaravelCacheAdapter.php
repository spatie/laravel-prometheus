<?php

namespace Spatie\Prometheus\Adapters;

use Illuminate\Contracts\Cache\Repository;
use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;
use Prometheus\Storage\InMemory;
use Prometheus\Summary;

class LaravelCacheAdapter extends InMemory
{
    private const CACHE_KEY_PREFIX = 'PROMETHEUS_';

    private const CACHE_KEY_SUFFIX = '_METRICS';

    private const STORES = [Gauge::TYPE, Counter::TYPE, Histogram::TYPE, Summary::TYPE];

    public function __construct(private readonly Repository $cache)
    {
    }

    public function collect(bool $sortMetrics = true): array
    {
        foreach (self::STORES as $store) {
            $this->fetch($store);
        }

        return parent::collect($sortMetrics);
    }

    public function updateSummary(array $data): void
    {
        $this->summaries = $this->fetch(Summary::TYPE);
        parent::updateSummary($data);
        $this->update(Summary::TYPE, $this->summaries);
    }

    public function updateHistogram(array $data): void
    {
        $this->histograms = $this->fetch(Histogram::TYPE);
        parent::updateHistogram($data);
        $this->update(Histogram::TYPE, $this->histograms);
    }

    public function updateGauge(array $data): void
    {
        $this->counters = $this->fetch(Gauge::TYPE);
        parent::updateGauge($data);
        $this->update(Gauge::TYPE, $this->counters);
    }

    public function updateCounter(array $data): void
    {
        $this->counters = $this->fetch(Counter::TYPE);
        parent::updateCounter($data);
        $this->update(Counter::TYPE, $this->counters);
    }

    public function wipeStorage(): void
    {
        $this->cache->deleteMultiple(
            array_map(fn ($store) => $this->cacheKey($store), self::STORES)
        );
    }

    private function fetch(string $type): array
    {
        return $this->cache->get($this->cacheKey($type), []);
    }

    private function update(string $type, $data): void
    {
        $this->cache->put($this->cacheKey($type), $data);
    }

    private function cacheKey(string $type): string
    {
        return self::CACHE_KEY_PREFIX.$type.self::CACHE_KEY_SUFFIX;
    }
}
