<?php

declare(strict_types=1);

namespace PHPUnitRetry\Tests\Utils;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use PHPUnitRetry\Util\Config;

class ConfigTest extends TestCase
{
    /** @dataProvider provideValidRetriesConfigFiles */
    public function testRetriesCountFromFile(string $fileName, int $expectedRetriesCount): void
    {
        $file = dirname(__FILE__) . \DIRECTORY_SEPARATOR . '_data' . \DIRECTORY_SEPARATOR . $fileName;
        $config = Config::getInstance($file);
        $this->assertSame($expectedRetriesCount, $config->getRetryCount());
    }

    public function provideValidRetriesConfigFiles(): \Generator
    {
        yield '0 retries count' => [
            'config-0-retries.xml',
            0
        ];

        yield '1 retries count' => [
            'config-1-retries.xml',
            1
        ];

        yield '3 retries count' => [
            'config-3-retries.xml',
            3
        ];

        yield 'missing baseRetryCount should return default counts (3)' => [
            'config-missing-count.xml',
            3
        ];
    }

    public function testMissingFile(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not read "missing-file.xml".');
        Config::getInstance('missing-file.xml');
    }
}
