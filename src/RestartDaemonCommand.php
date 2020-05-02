<?php

namespace Daemon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\InteractsWithTime;

class RestartDaemonCommand extends Command
{
    use InteractsWithTime;

    protected $name = 'daemon:restart';

    /**
     * @return void
     */
    public function handle()
    {
        Cache::forever('daemon:restart', $this->currentTime());

        $this->info('Broadcasting daemon restart signal.');
    }
}