<?php

namespace Daemon;

abstract class Worker
{
    /**
     * @var int
     */
    protected $workerCount;

    /**
     * @var int
     */
    protected $workerId;

    /**
     * @param int $workerCount
     * @param int $workerId
     */
    public function __construct(int $workerCount, int $workerId)
    {
        $this->workerCount = $workerCount;
        $this->workerId = $workerId;
    }

    /**
     * @return void
     */
    abstract public function handle();
}