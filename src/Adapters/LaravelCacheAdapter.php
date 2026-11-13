<?php

namespace Spatie\Prometheus\Adapters;

use Closure;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\Repository;
use InvalidArgumentException;
use Prometheus\Counter;
use Prometheus\Gauge;
use Prometheus\Histogram;
use Prometheus\MetricFamilySamples;
use Prometheus\Storage\InMemory;
use Prometheus\Summary;

class LaravelCacheAdapter extends InMemory
{
    protected string $cacheKeyPrefix = 'PROMETHEUS_';

    protected string $cacheKeySuffix = '_METRICS';

    /** @var string[] */
    private array $stores = [Gauge::TYPE, Counter::TYPE, Histogram::TYPE, Summary::TYPE];

    public function __construct(
        protected readonly Repository $cache,
        protected readonly bool $lock = false,
    ) {}

    /**
     * @return MetricFamilySamples[]
     */
    public function collect(bool $sortMetrics = true): array
    {
        $this->gauges = $this->fetch(Gauge::TYPE);
        $this->counters = $this->fetch(Counter::TYPE);
        $this->histograms = $this->fetch(Histogram::TYPE);
        $this->summaries = $this->fetch(Summary::TYPE);

        return parent::collect($sortMetrics);
    }

    public function updateSummary(array $data): void
    {
        $this->locked(Summary::TYPE, function () use ($data) {
            $this->summaries = $this->fetch(Summary::TYPE);
            parent::updateSummary($data);
            $this->update(Summary::TYPE, $this->summaries);
        });
    }

    public function updateHistogram(array $data): void
    {
        $this->locked(Histogram::TYPE, function () use ($data) {
            $this->histograms = $this->fetch(Histogram::TYPE);
            parent::updateHistogram($data);
            $this->update(Histogram::TYPE, $this->histograms);
        });
    }

    public function updateGauge(array $data): void
    {
        $this->locked(Gauge::TYPE, function () use ($data) {
            $this->gauges = $this->fetch(Gauge::TYPE);
            parent::updateGauge($data);
            $this->update(Gauge::TYPE, $this->gauges);
        });
    }

    public function updateCounter(array $data): void
    {
        $this->locked(Counter::TYPE, function () use ($data) {
            $this->counters = $this->fetch(Counter::TYPE);
            parent::updateCounter($data);
            $this->update(Counter::TYPE, $this->counters);
        });
    }

    public function wipeStorage(): void
    {
        $this->cache->deleteMultiple(
            array_map(fn ($store) => $this->cacheKey($store), $this->stores)
        );
    }

    protected function locked(string $type, Closure $update): void
    {
        if (! $this->lock) {
            $update();

            return;
        }

        $store = $this->cache->getStore();

        if (! $store instanceof LockProvider) {
            throw new InvalidArgumentException('The configured Prometheus cache store does not support locks.');
        }

        $store->lock($this->cacheKey($type).'_LOCK', 5)->block(3, $update);
    }

    protected function fetch(string $type): array
    {
        return $this->cache->get($this->cacheKey($type), []);
    }

    protected function update(string $type, $data): void
    {
        $this->cache->put($this->cacheKey($type), $data);
    }

    protected function cacheKey(string $type): string
    {
        return $this->cacheKeyPrefix.$type.$this->cacheKeySuffix;
    }
}
