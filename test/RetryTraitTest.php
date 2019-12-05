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
    public function testNoArgumentToRetryAttemptsAnnotation()
    {
        $this->validateRetryAttemptsAnnotation('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryAttemptsAnnotation()
    {
        $this->validateRetryAttemptsAnnotation('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryAttemptsAnnotation()
    {
        $this->validateRetryAttemptsAnnotation('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation must be greater than 0 but got "0"
     */
    public function testNonPositiveIntegerToRetryAttemptsAnnotation()
    {
        $this->validateRetryAttemptsAnnotation(0);
    }

    public function testValidRetryAttemptsAnnotations()
    {
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation(1));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation('1'));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation(1.0));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation('1.0'));
    }

    public function testClassRetryDelaySeconds()
    {
        $this->assertEquals(2, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @retryAttempts 3
     */
    public function testMethodRetryDelaySeconds()
    {
        $this->assertEquals(3, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation requires a positive integer as an argument
     */
    public function testNoArgumentToRetryDelaySecondsAnnotation()
    {
        $this->validateRetryDelaySecondsAnnotation('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryDelaySecondsAnnotation()
    {
        $this->validateRetryDelaySecondsAnnotation('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryDelaySecondsAnnotation()
    {
        $this->validateRetryDelaySecondsAnnotation('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryDelaySecondsAnnotation()
    {
        $this->validateRetryDelaySecondsAnnotation(-1);
    }

    public function testValidRetryDelaySecondsAnnotations()
    {
        $this->assertEquals(0, $this->validateRetryDelaySecondsAnnotation(0));
        $this->assertEquals(0, $this->validateRetryDelaySecondsAnnotation('0'));
        $this->assertEquals(1, $this->validateRetryDelaySecondsAnnotation(1));
        $this->assertEquals(1, $this->validateRetryDelaySecondsAnnotation('1'));
        $this->assertEquals(1, $this->validateRetryDelaySecondsAnnotation(1.0));
        $this->assertEquals(1, $this->validateRetryDelaySecondsAnnotation('1.0'));
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
     * @retryDelaySeconds 1
     * @depends testRetriesOnException
     */
    public function testRetryDelaySeconds()
    {
        if (empty(self::$timeCalled)) {
            self::$timeCalled = time();
            throw new Exception('Intentional Exception');
        }

        $this->assertGreaterThan(self::$timeCalled, time());
    }
}
