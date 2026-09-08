<?php

namespace Spatie\Prometheus\Tests\TestSupport\Queue;

use Closure;

class FakeQueue
{
    public function __construct(
        protected Closure $creationTimeOfOldestPendingJob,
    ) {}

    public function creationTimeOfOldestPendingJob(string $queueName): ?int
    {
        return ($this->creationTimeOfOldestPendingJob)($queueName);
    }
}
