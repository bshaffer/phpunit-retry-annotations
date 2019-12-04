<?php

namespace PHPUnitRetry\Test;

use PHPUnit\Framework\TestCase;
use PHPUnitRetry\RetryTrait;
use Exception;

/**
 * A class for testing RetryTrait.
 *
 * @retryAttempts 2
 */
class RetryTraitTest extends TestCase
{
    use RetryTrait;

    private static $timesCalled = 0;
    private static $timeCalled = null;

    public function testClassRetries()
    {
        $this->assertEquals(2, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @retryAttempts 3
     */
    public function testMethodRetries()
    {
        $this->assertEquals(3, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation requires a positive integer as an argument
     */
    public function testNoArgumentToRetryAnnotation()
    {
        $this->validateRetries('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryAnnotation()
    {
        $this->validateRetries('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryAnnotation()
    {
        $this->validateRetries('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be greater than 0 but got "0"
     */
    public function testNonPositiveIntegerToRetryAnnotation()
    {
        $this->validateRetries(0);
    }

    public function testValidRetryAnnotations()
    {
        $this->assertEquals(1, $this->validateRetries(1));
        $this->assertEquals(1, $this->validateRetries('1'));
        $this->assertEquals(1, $this->validateRetries(1.0));
        $this->assertEquals(1, $this->validateRetries('1.0'));
    }

    public function testClassRetrySleepSeconds()
    {
        $this->assertEquals(2, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @retryAttempts 3
     */
    public function testMethodRetrySleepSeconds()
    {
        $this->assertEquals(3, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retrySleepSeconds annotation requires a positive integer as an argument
     */
    public function testNoArgumentToRetrySleepSecondsAnnotation()
    {
        $this->validateSleepSeconds('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retrySleepSeconds annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetrySleepSecondsAnnotation()
    {
        $this->validateSleepSeconds('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retrySleepSeconds annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetrySleepSecondsAnnotation()
    {
        $this->validateSleepSeconds('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retrySleepSeconds annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetrySleepSecondsAnnotation()
    {
        $this->validateSleepSeconds(-1);
    }

    public function testValidRetrySleepSecondsAnnotations()
    {
        $this->assertEquals(0, $this->validateSleepSeconds(0));
        $this->assertEquals(0, $this->validateSleepSeconds('0'));
        $this->assertEquals(1, $this->validateSleepSeconds(1));
        $this->assertEquals(1, $this->validateSleepSeconds('1'));
        $this->assertEquals(1, $this->validateSleepSeconds(1.0));
        $this->assertEquals(1, $this->validateSleepSeconds('1.0'));
    }

    /**
     * @retryAttempts 3
     */
    public function testRetriesOnException()
    {
        self::$timesCalled++;
        $numRetries = $this->getRetryAttemptsAnnotation();
        if (self::$timesCalled < $numRetries) {
            throw new Exception('Intentional Exception');
        }
        $this->assertEquals($numRetries, self::$timesCalled);
    }

    /**
     * @retryAttempts 2
     * @retrySleepSeconds 1
     * @depends testRetriesOnException
     */
    public function testRetrySleepSeconds()
    {
        if (empty(self::$timeCalled)) {
            self::$timeCalled = time();
            throw new Exception('Intentional Exception');
        }

        $this->assertGreaterThan(self::$timeCalled, time());
    }
}
