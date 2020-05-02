<?php

namespace Daemon;

abstract class Worker
{
    /**
     * @return void
     */
    abstract public function handle();
}