<?php

namespace PHPUnitRetry\Test;

use PHPUnit\Framework\TestCase;
use PHPUnitRetry\RetryAnnotationTrait;

/**
 * A class for testing RetryAnnotationTrait.
 *
 * @retryAttempts 2
 * @retryForSeconds 1
 * @retryDelaySeconds 1
 * @retryDelayMethod fakeDelayMethod1
 */
class RetryAnnotationTraitTest extends TestCase
{
    use RetryAnnotationTrait;

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
     * @retryAttempts
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryAttemptsAnnotation()
    {
        $this->getRetryAttemptsAnnotation();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryAttempts annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryAttemptsAnnotation()
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
     * @expectedExceptionMessage The @retryAttempts annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryAttemptsAnnotation()
    {
        $this->validateRetryAttemptsAnnotation(-1);
    }

    public function testValidRetryAttemptsAnnotations()
    {
        $this->assertEquals(0, $this->validateRetryAttemptsAnnotation(0));
        $this->assertEquals(0, $this->validateRetryAttemptsAnnotation('0'));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation(1));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation('1'));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation(1.0));
        $this->assertEquals(1, $this->validateRetryAttemptsAnnotation('1.0'));
    }

    public function testClassRetryDelaySeconds()
    {
        $this->assertEquals(1, $this->getRetryDelaySecondsAnnotation());
    }

    /**
     * @retryDelaySeconds 2
     */
    public function testMethodRetryDelaySeconds()
    {
        $this->assertEquals(2, $this->getRetryDelaySecondsAnnotation());
    }

    /**
     * @retryDelaySeconds
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryDelaySecondsAnnotation()
    {
        $this->getRetryDelaySecondsAnnotation();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelaySeconds annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryDelaySecondsAnnotation()
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

    public function testClassRetryDelayMethod()
    {
        $this->assertEquals(
            ['fakeDelayMethod1', []],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod fakeDelayMethod2
     */
    public function testMethodRetryDelayMethod()
    {
        $this->assertEquals(
            ['fakeDelayMethod2', []],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod fakeDelayMethod2 foo1 foo2
     */
    public function testMethodRetryDelayMethodWithArguments()
    {
        $this->assertEquals(
            ['fakeDelayMethod2', ['foo1', 'foo2']],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelayMethod annotation requires a callable as an argument
     */
    public function testNoArgumentToRetryDelayMethodAnnotation()
    {
        $this->getRetryDelayMethodAnnotation();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelayMethod annotation requires a callable as an argument
     */
    public function testEmptyStringToRetryDelayMethodAnnotation()
    {
        $this->validateRetryDelayMethodAnnotation('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryDelayMethod annotation must be a method in your test class but got "nonexistantDelayMethod"
     */
    public function testInvalidCallableArgumentTypeToRetryDelayMethodAnnotation()
    {
        $this->validateRetryDelayMethodAnnotation('nonexistantDelayMethod');
    }

    private function fakeDelayMethod1()
    {
    }

    private function fakeDelayMethod2()
    {
    }

    public function testClassRetryForSeconds()
    {
        $this->assertEquals(1, $this->getRetryForSecondsAnnotation());
    }

    /**
     * @retryForSeconds 2
     */
    public function testMethodRetryForSeconds()
    {
        $this->assertEquals(2, $this->getRetryForSecondsAnnotation());
    }

    /**
     * @retryForSeconds
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryForSeconds annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryForSecondsAnnotation()
    {
        $this->getRetryForSecondsAnnotation();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryForSeconds annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryForSecondsAnnotation()
    {
        $this->validateRetryForSecondsAnnotation('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryForSeconds annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryForSecondsAnnotation()
    {
        $this->validateRetryForSecondsAnnotation('foo');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryForSeconds annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryForSecondsAnnotation()
    {
        $this->validateRetryForSecondsAnnotation('1.2');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryForSeconds annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryForSecondsAnnotation()
    {
        $this->validateRetryForSecondsAnnotation(-1);
    }

    public function testValidRetryForSecondsAnnotations()
    {
        $this->assertEquals(0, $this->validateRetryForSecondsAnnotation(0));
        $this->assertEquals(0, $this->validateRetryForSecondsAnnotation('0'));
        $this->assertEquals(1, $this->validateRetryForSecondsAnnotation(1));
        $this->assertEquals(1, $this->validateRetryForSecondsAnnotation('1'));
        $this->assertEquals(1, $this->validateRetryForSecondsAnnotation(1.0));
        $this->assertEquals(1, $this->validateRetryForSecondsAnnotation('1.0'));
    }

    /**
     * @retryIfMethod fakeIfMethod2
     */
    public function testMethodRetryIfMethod()
    {
        $this->assertEquals(
            ['fakeIfMethod2', []],
            $this->getRetryIfMethodAnnotation()
        );
    }

    /**
     * @retryIfMethod fakeIfMethod2 foo1 foo2
     */
    public function testMethodRetryIfMethodWithArguments()
    {
        $this->assertEquals(
            ['fakeIfMethod2', ['foo1', 'foo2']],
            $this->getRetryIfMethodAnnotation()
        );
    }

    /**
     * @retryIfMethod
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryIfMethod annotation requires a callable as an argument
     */
    public function testNoArgumentToRetryIfMethodAnnotation()
    {
        $this->getRetryIfMethodAnnotation();
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryIfMethod annotation requires a callable as an argument
     */
    public function testEmptyStringToRetryIfMethodAnnotation()
    {
        $this->validateRetryIfMethodAnnotation('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryIfMethod annotation must be a method in your test class but got "nonexistantIfMethod"
     */
    public function testInvalidCallableArgumentTypeToRetryIfMethodAnnotation()
    {
        $this->validateRetryIfMethodAnnotation('nonexistantIfMethod');
    }

    private function fakeIfMethod1()
    {
    }

    private function fakeIfMethod2()
    {
    }

    /**
     * @retryIfException InvalidArgumentException
     */
    public function testRetryIfException()
    {
        $this->assertEquals(
            ['InvalidArgumentException'],
            $this->getRetryIfExceptionAnnotations()
        );
    }

    /**
     * @retryIfException LogicException
     * @retryIfException InvalidArgumentException
     */
    public function testMultipleRetryIfException()
    {
        $this->assertEquals(
            ['LogicException', 'InvalidArgumentException'],
            $this->getRetryIfExceptionAnnotations()
        );
    }

    /**
     * @retryIfException
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryIfException annotation requires a class name as an argument
     */
    public function testNoArgumentToRetryIfExceptionAnnotation()
    {
        $this->getRetryIfExceptionAnnotations();
    }

    /**
     * @retryIfException ThisClassDoesNotExist
     * @expectedException LogicException
     * @expectedExceptionMessage The @retryIfException annotation must be an instance of Exception but got "ThisClassDoesNotExist"
     */
    public function testRetryIfExceptionWithInvalidClassname()
    {
        $this->getRetryIfExceptionAnnotations();
    }
}
