<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger;

interface LoggerFactory
{
    public function createLogger(): Logger;
}
