<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger\File;

use PHPUnitRetry\Logger\Logger;
use PHPUnitRetry\Logger\LoggerFactory;

class FileLoggerFactory implements LoggerFactory
{
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function createLogger(): Logger
    {
        return new FileLogger($this->filePath);
    }
}