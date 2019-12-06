<?php

namespace PHPUnitRetry\Tests;

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

    public function testClassRetries(): void
    {
        $this->assertEquals(2, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @retryAttempts 3
     */
    public function testMethodRetries(): void
    {
        $this->assertEquals(3, $this->getRetryAttemptsAnnotation());
    }

    /**
     * @retryAttempts
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryAttempts annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryAttemptsAnnotation(): void
    {
        $this->getRetryAttemptsAnnotation();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryAttempts annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryAttemptsAnnotation(): void
    {
        $this->parseRetryAttemptsAnnotation('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryAttemptsAnnotation(): void
    {
        $this->parseRetryAttemptsAnnotation('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryAttempts annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryAttemptsAnnotation(): void
    {
        $this->parseRetryAttemptsAnnotation('1.2');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryAttempts annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryAttemptsAnnotation(): void
    {
        $this->parseRetryAttemptsAnnotation(-1);
    }

    public function testValidRetryAttemptsAnnotations(): void
    {
        $this->assertEquals(0, $this->parseRetryAttemptsAnnotation('0'));
        $this->assertEquals(1, $this->parseRetryAttemptsAnnotation('1'));
        $this->assertEquals(1, $this->parseRetryAttemptsAnnotation('1.0'));
    }

    public function testClassRetryDelaySeconds(): void
    {
        $this->assertEquals(1, $this->getRetryDelaySecondsAnnotation());
    }

    /**
     * @retryDelaySeconds 2
     */
    public function testMethodRetryDelaySeconds(): void
    {
        $this->assertEquals(2, $this->getRetryDelaySecondsAnnotation());
    }

    /**
     * @retryDelaySeconds
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelaySeconds annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryDelaySecondsAnnotation(): void
    {
        $this->getRetryDelaySecondsAnnotation();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelaySeconds annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryDelaySecondsAnnotation(): void
    {
        $this->parseRetryDelaySecondsAnnotation('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryDelaySecondsAnnotation(): void
    {
        $this->parseRetryDelaySecondsAnnotation('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryDelaySecondsAnnotation(): void
    {
        $this->parseRetryDelaySecondsAnnotation('1.2');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelaySeconds annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryDelaySecondsAnnotation(): void
    {
        $this->parseRetryDelaySecondsAnnotation(-1);
    }

    public function testValidRetryDelaySecondsAnnotations(): void
    {
        $this->assertEquals(0, $this->parseRetryDelaySecondsAnnotation('0'));
        $this->assertEquals(1, $this->parseRetryDelaySecondsAnnotation('1'));
        $this->assertEquals(1, $this->parseRetryDelaySecondsAnnotation('1.0'));
    }

    public function testClassRetryDelayMethod(): void
    {
        $this->assertEquals(
            ['fakeDelayMethod1', []],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod fakeDelayMethod2
     */
    public function testMethodRetryDelayMethod(): void
    {
        $this->assertEquals(
            ['fakeDelayMethod2', []],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod fakeDelayMethod2 foo1 foo2
     */
    public function testMethodRetryDelayMethodWithArguments(): void
    {
        $this->assertEquals(
            ['fakeDelayMethod2', ['foo1', 'foo2']],
            $this->getRetryDelayMethodAnnotation()
        );
    }

    /**
     * @retryDelayMethod
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelayMethod annotation requires a callable as an argument
     */
    public function testNoArgumentToRetryDelayMethodAnnotation(): void
    {
        $this->getRetryDelayMethodAnnotation();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelayMethod annotation requires a callable as an argument
     */
    public function testEmptyStringToRetryDelayMethodAnnotation(): void
    {
        $this->parseRetryDelayMethodAnnotation('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryDelayMethod annotation must be a method in your test class but got "nonexistantDelayMethod"
     */
    public function testInvalidCallableArgumentTypeToRetryDelayMethodAnnotation(): void
    {
        $this->parseRetryDelayMethodAnnotation('nonexistantDelayMethod');
    }

    public function testClassRetryForSeconds(): void
    {
        $this->assertEquals(1, $this->getRetryForSecondsAnnotation());
    }

    /**
     * @retryForSeconds 2
     */
    public function testMethodRetryForSeconds(): void
    {
        $this->assertEquals(2, $this->getRetryForSecondsAnnotation());
    }

    /**
     * @retryForSeconds
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryForSeconds annotation requires an integer as an argument
     */
    public function testNoArgumentToRetryForSecondsAnnotation(): void
    {
        $this->getRetryForSecondsAnnotation();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryForSeconds annotation requires an integer as an argument
     */
    public function testEmptyStringToRetryForSecondsAnnotation(): void
    {
        $this->parseRetryForSecondsAnnotation('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryForSeconds annotation must be an integer but got "'foo'"
     */
    public function testInvalidStringArgumentTypeToRetryForSecondsAnnotation(): void
    {
        $this->parseRetryForSecondsAnnotation('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryForSeconds annotation must be an integer but got "1.2"
     */
    public function testInvalidFloatArgumentTypeToRetryForSecondsAnnotation(): void
    {
        $this->parseRetryForSecondsAnnotation('1.2');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryForSeconds annotation must be 0 or greater but got "-1"
     */
    public function testNonPositiveIntegerToRetryForSecondsAnnotation(): void
    {
        $this->parseRetryForSecondsAnnotation(-1);
    }

    public function testValidRetryForSecondsAnnotations(): void
    {
        $this->assertEquals(0, $this->parseRetryForSecondsAnnotation(0));
        $this->assertEquals(0, $this->parseRetryForSecondsAnnotation('0'));
        $this->assertEquals(1, $this->parseRetryForSecondsAnnotation(1));
        $this->assertEquals(1, $this->parseRetryForSecondsAnnotation('1'));
        $this->assertEquals(1, $this->parseRetryForSecondsAnnotation(1.0));
        $this->assertEquals(1, $this->parseRetryForSecondsAnnotation('1.0'));
    }

    /**
     * @retryIfMethod fakeIfMethod2
     */
    public function testMethodRetryIfMethod(): void
    {
        $this->assertEquals(
            ['fakeIfMethod2', []],
            $this->getRetryIfMethodAnnotation()
        );
    }

    /**
     * @retryIfMethod fakeIfMethod2 foo1 foo2
     */
    public function testMethodRetryIfMethodWithArguments(): void
    {
        $this->assertEquals(
            ['fakeIfMethod2', ['foo1', 'foo2']],
            $this->getRetryIfMethodAnnotation()
        );
    }

    /**
     * @retryIfMethod
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryIfMethod annotation requires a callable as an argument
     */
    public function testNoArgumentToRetryIfMethodAnnotation(): void
    {
        $this->getRetryIfMethodAnnotation();
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryIfMethod annotation requires a callable as an argument
     */
    public function testEmptyStringToRetryIfMethodAnnotation(): void
    {
        $this->validateRetryIfMethodAnnotation('');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryIfMethod annotation must be a method in your test class but got "nonexistantIfMethod"
     */
    public function testInvalidCallableArgumentTypeToRetryIfMethodAnnotation(): void
    {
        $this->validateRetryIfMethodAnnotation('nonexistantIfMethod');
    }

    /**
     * @retryIfException InvalidArgumentException
     */
    public function testRetryIfException(): void
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
    public function testMultipleRetryIfException(): void
    {
        $this->assertEquals(
            ['LogicException', 'InvalidArgumentException'],
            $this->getRetryIfExceptionAnnotations()
        );
    }

    /**
     * @retryIfException
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryIfException annotation requires a class name as an argument
     */
    public function testNoArgumentToRetryIfExceptionAnnotation(): void
    {
        $this->getRetryIfExceptionAnnotations();
    }

    /**
     * @retryIfException ThisClassDoesNotExist
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The @retryIfException annotation must be an instance of Exception but got "ThisClassDoesNotExist"
     */
    public function testRetryIfExceptionWithInvalidClassname(): void
    {
        $this->getRetryIfExceptionAnnotations();
    }

    private function fakeDelayMethod1(): void
    {
    }

    private function fakeDelayMethod2(): void
    {
    }

    private function fakeIfMethod1(): void
    {
    }

    private function fakeIfMethod2(): void
    {
    }
}
