<?php

declare(strict_types=1);

namespace PHPUnitRetry\Logger\File;

use PHPUnitRetry\Logger\Logger;

class FileLogger implements Logger
{
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }
    
    public function log(string $message): void
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}
