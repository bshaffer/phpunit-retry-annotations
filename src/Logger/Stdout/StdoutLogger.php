<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger\Stdout;

use PHPUnitRetry\Logger\Logger;

class StdoutLogger implements Logger
{
    public function log(string $message): void
    {
        echo $message;
    }
}
