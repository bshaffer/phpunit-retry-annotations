<?php

namespace PHPUnitRetry\Test;

use PHPUnit\Framework\TestCase;
use PHPUnitRetry\RetryTrait;
use Exception;

/**
 * A class for testing RetryTrait.
 *
 * @retry 2
 */
class RetryTraitTest extends TestCase
{
    use RetryTrait;

    private static $timesCalled = 0;

    public function testClassRetries()
    {
        $this->assertEquals(2, $this->getNumberOfRetries());
    }

    /**
     * @retry 3
     */
    public function testMethodRetries()
    {
        $this->assertEquals(3, $this->getNumberOfRetries());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retry annotation requires a positive integer as an argument
     */
    public function testNoArgumentToRetryAnnotation()
    {
        $this->validateRetries('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retry annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryAnnotation()
    {
        $this->validateRetries('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retry annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryAnnotation()
    {
        $this->validateRetries('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The $retries must be greater than 0 but got "0"
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

    /**
     * @retry 3
     */
    public function testRetriesOnException()
    {
        self::$timesCalled++;
        $numRetries = $this->getNumberOfRetries();
        if (self::$timesCalled < $numRetries) {
            throw new Exception('Intentional Exception');
        }
        $this->assertEquals($numRetries, self::$timesCalled);
    }
}
