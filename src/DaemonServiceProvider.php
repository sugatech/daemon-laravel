<?php

namespace Daemon;

use Illuminate\Support\ServiceProvider;

class DaemonServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                RunDaemonCommand::class,
                RestartDaemonCommand::class,
            ]);
        }
    }
}