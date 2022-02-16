<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger\Stdout;

use PHPUnitRetry\Logger\LoggerFactory;

class StdoutLoggerFactory implements LoggerFactory
{
    public function createLogger(): Logger
    {
        return new StdoutLogger();
    }
}
