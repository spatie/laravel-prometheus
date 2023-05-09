<?php

namespace Spatie\Prometheus\Commands;

use Illuminate\Console\Command;

class PrometheusCommand extends Command
{
    public $signature = 'laravel-prometheus';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
