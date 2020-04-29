<?php

namespace Daemon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class DaemonCommand extends Command
{
    protected $signature = 'daemon:run {class} {--sleep=1}';

    /**
     * @return void
     */
    public function handle()
    {
        $class = $this->argument('class');

        $worker = new $class;

        if ($worker instanceof Worker) {
            $lastRestart = $this->getTimestampOfLastRestart();

            while (true) {
                $worker->handle();

                if ($this->shouldRestart($lastRestart)) {
                    $this->stop();
                }

                usleep($this->option('sleep') * 1000);
            }
        }
    }

    /**
     * @param int $status
     */
    private function stop($status = 0)
    {
        exit($status);
    }

    /**
     * @return int|null
     */
    private function getTimestampOfLastRestart()
    {
        return Cache::get('daemon:restart');
    }

    /**
     * @param  int|null  $lastRestart
     * @return bool
     */
    protected function shouldRestart($lastRestart)
    {
        return $this->getTimestampOfLastRestart() != $lastRestart;
    }
}