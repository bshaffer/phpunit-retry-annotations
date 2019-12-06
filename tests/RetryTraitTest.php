<?php

namespace PHPUnitRetry\Tests;

use DomainException;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnitRetry\RetryTrait;
use function func_get_args;
use function time;

/**
 * A class for testing RetryTrait.
 */
class RetryTraitTest extends TestCase
{
    use RetryTrait;

    private static $timesCalled = 0;
    private static $timestampCalled;
    private static $customDelayMethodCalled = false;
    private static $customRetryIfMethodCalled = false;

    /**
     * @retryAttempts 3
     */
    public function testRetryAttempts(): void
    {
        self::$timesCalled++;
        $retryAttempts = $this->getRetryAttemptsAnnotation();
        if (self::$timesCalled <= $retryAttempts) {
            throw new Exception('Intentional Exception');
        }
        $this->assertEquals($retryAttempts + 1, self::$timesCalled);
        self::$timesCalled = 0;
    }

    /**
     * @retryAttempts 2
     * @retryDelaySeconds 1
     * @depends testRetryAttempts
     */
    public function testRetryDelaySeconds(): void
    {
        $currentTimestamp = time();
        if (empty(self::$timestampCalled)) {
            self::$timestampCalled = $currentTimestamp;
            throw new Exception('Intentional Exception');
        }

        $this->assertGreaterThan(self::$timestampCalled, $currentTimestamp);
        self::$timestampCalled = null;
    }

    /**
     * @retryAttempts 2
     * @retryDelayMethod customDelayMethod foo
     * @depends testRetryAttempts
     */
    public function testCustomRetryDelayMethod(): void
    {
        self::$timesCalled++;
        if (self::$timesCalled === 1) {
            throw new Exception('Intentional Exception');
        }

        $this->assertTrue(self::$customDelayMethodCalled);
        self::$customDelayMethodCalled = false;
        self::$timesCalled = 0;
    }

    /**
     * @retryForSeconds 2
     * @retryDelaySeconds 1
     * @depends testCustomRetryDelayMethod
     */
    public function testRetryForSeconds(): void
    {
        $currentTimestamp = time();
        if (empty(self::$timestampCalled)) {
            self::$timestampCalled = $currentTimestamp;
        }
        if ($currentTimestamp < self::$timestampCalled + 3) {
            throw new Exception('Intentional Exception');
        }
        $this->assertGreaterThan(self::$timestampCalled, $currentTimestamp);
        self::$timestampCalled = null;
    }

    /**
     * @retryAttempts 1
     * @retryIfException InvalidArgumentException
     * @depends testCustomRetryDelayMethod
     */
    public function testRetryIfException(): void
    {
        self::$timesCalled++;
        if (self::$timesCalled === 1) {
            throw new InvalidArgumentException('Intentional Exception');
        }

        $this->assertEquals(2, self::$timesCalled);
        self::$timesCalled = 0;
    }

    /**
     * @retryAttempts 1
     * @retryIfException InvalidArgumentException
     * @retryIfException DomainException
     * @depends testRetryIfException
     */
    public function testRetryIfExceptionMultiple(): void
    {
        self::$timesCalled++;
        if (self::$timesCalled === 1) {
            throw new DomainException('Intentional Exception');
        }

        $this->assertEquals(2, self::$timesCalled);
        self::$timesCalled = 0;
    }

    /**
     * @retryAttempts 1
     * @retryIfMethod customRetryIfMethod foo
     * @depends testRetryIfExceptionMultiple
     */
    public function testRetryIfMethod(): void
    {
        self::$timesCalled++;
        if (self::$timesCalled === 1) {
            throw new Exception('Intentional Exception');
        }

        $this->assertTrue(self::$customRetryIfMethodCalled);
        self::$customRetryIfMethodCalled = false;
        self::$timesCalled = 0;
    }

    /**
     * @var int $attempt
     */
    private function customDelayMethod($attempt): void
    {
        $this->assertIsInt($attempt);
        $this->assertEquals(1, $attempt);

        // Test the custom arg
        $this->assertCount(2, $args = func_get_args());
        $this->assertEquals('foo', $args[1]);
        self::$customDelayMethodCalled = true;
    }

    /**
     * @var Exception $e
     */
    private function customRetryIfMethod($e): void
    {
        $this->assertInstanceOf('Exception', $e);

        // Test the custom arg
        $this->assertCount(2, $args = func_get_args());
        $this->assertEquals('foo', $args[1]);
        self::$customRetryIfMethodCalled = true;
    }
}
