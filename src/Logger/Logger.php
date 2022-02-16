<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger;

interface Logger
{
    public function log(string $message): void;
}
