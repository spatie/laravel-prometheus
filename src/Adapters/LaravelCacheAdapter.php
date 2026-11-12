<?php

namespace Spatie\Prometheus\Adapters;

use Closure;
use Illuminate\Cache\Lock;
use Illuminate\Contracts\Cache\LockProvider;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Cache\Repository;
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

    /** How long an update holds the lock at most, so that a process that dies holding it does not block others for long. */
    protected int $lockSeconds = 5;

    /** How long an update waits for the lock before it updates without it. */
    protected int $lockWaitSeconds = 3;

    /**
     * @param  bool  $lock  Whether to update under a cache lock, so that concurrent updates from other processes are not lost
     */
    public function __construct(protected readonly Repository $cache, protected readonly bool $lock = false) {}

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

    /**
     * Runs the read-modify-write of one metric type, under a cache lock when locking is enabled, so that an update
     * made by another process at the same time is not overwritten. Without a store that supports locks, or when the
     * lock is not acquired in time, the update runs without it rather than fail the caller.
     */
    protected function locked(string $type, Closure $update): void
    {
        $store = $this->cache->getStore();

        if (! $this->lock || ! $store instanceof LockProvider) {
            $update();

            return;
        }

        $lock = $store->lock($this->cacheKey($type).'_LOCK', $this->lockSeconds);

        if ($lock instanceof Lock) {
            $lock->betweenBlockedAttemptsSleepFor(10);
        }

        try {
            $lock->block($this->lockWaitSeconds, $update);
        } catch (LockTimeoutException) {
            $update();
        }
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
