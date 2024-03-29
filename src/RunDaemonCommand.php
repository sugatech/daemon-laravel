<?php

namespace Daemon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RunDaemonCommand extends Command
{
    protected $signature = 'daemon:run {worker} {--worker_count=1} {--worker_id=1} {--sleep=1000}';

    /**
     * @return void
     */
    public function handle()
    {
        $worker = $this->argument('worker');
        $workerCount = intval($this->option('worker_count'));
        $workerId = intval($this->option('worker_id'));
        $sleep = intval($this->option('sleep'));

        $instance = new $worker($workerCount, $workerId);
        if (!($instance instanceof Worker)) {
            $this->stop(1);
        }

        $lastRestart = $this->getTimestampOfLastRestart();

        while (true) {
            $instance->handle();

            if ($this->shouldRestart($lastRestart)) {
                $this->stop();
            }

            usleep($sleep * 1000);
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
     * @param int|null $lastRestart
     * @return bool
     */
    protected function shouldRestart($lastRestart)
    {
        return $this->getTimestampOfLastRestart() != $lastRestart;
    }
}