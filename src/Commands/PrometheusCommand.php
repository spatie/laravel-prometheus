<?php

namespace Spatie\Prometheus\Commands;

use Illuminate\Console\Command;

class PrometheusCommand extends Command
{
    public $signature = 'prometheus:list-collectors';

    public $description = 'List all collectors registered to Prometheus';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
