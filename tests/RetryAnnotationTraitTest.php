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
     */
    public function testNoArgumentToRetryAttemptsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryAttempts annotation requires an integer as an argument');
        $this->getRetryAttemptsAnnotation();
    }

    public function testEmptyStringToRetryAttemptsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryAttempts annotation requires an integer as an argument');
        $this->parseRetryAttemptsAnnotation('');
    }

    public function testInvalidStringArgumentTypeToRetryAttemptsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryAttempts annotation must be an integer but got "\'foo\'"');
        $this->parseRetryAttemptsAnnotation('foo');
    }

    public function testInvalidFloatArgumentTypeToRetryAttemptsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryAttempts annotation must be an integer but got "1.2"');
        $this->parseRetryAttemptsAnnotation('1.2');
    }

    public function testNonPositiveIntegerToRetryAttemptsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryAttempts annotation must be 0 or greater but got "-1"');
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
     */
    public function testNoArgumentToRetryDelaySecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelaySeconds annotation requires an integer as an argument');
        $this->getRetryDelaySecondsAnnotation();
    }

    public function testEmptyStringToRetryDelaySecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelaySeconds annotation requires an integer as an argument');
        $this->parseRetryDelaySecondsAnnotation('');
    }

    public function testInvalidStringArgumentTypeToRetryDelaySecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelaySeconds annotation must be an integer but got "\'foo\'"');
        $this->parseRetryDelaySecondsAnnotation('foo');
    }

    public function testInvalidFloatArgumentTypeToRetryDelaySecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelaySeconds annotation must be an integer but got "1.2"');
        $this->parseRetryDelaySecondsAnnotation('1.2');
    }

    public function testNonPositiveIntegerToRetryDelaySecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelaySeconds annotation must be 0 or greater but got "-1"');
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
     */
    public function testNoArgumentToRetryDelayMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelayMethod annotation requires a callable as an argument');
        $this->getRetryDelayMethodAnnotation();
    }

    public function testEmptyStringToRetryDelayMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelayMethod annotation requires a callable as an argument');
        $this->parseRetryDelayMethodAnnotation('');
    }

    public function testInvalidCallableArgumentTypeToRetryDelayMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryDelayMethod annotation must be a method in your test class but got "nonexistantDelayMethod"');
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
     */
    public function testNoArgumentToRetryForSecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryForSeconds annotation requires an integer as an argument');
        $this->getRetryForSecondsAnnotation();
    }

    public function testEmptyStringToRetryForSecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryForSeconds annotation requires an integer as an argument');
        $this->parseRetryForSecondsAnnotation('');
    }

    public function testInvalidStringArgumentTypeToRetryForSecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryForSeconds annotation must be an integer but got "\'foo\'"');
        $this->parseRetryForSecondsAnnotation('foo');
    }

    public function testInvalidFloatArgumentTypeToRetryForSecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryForSeconds annotation must be an integer but got "1.2"');
        $this->parseRetryForSecondsAnnotation('1.2');
    }

    public function testNonPositiveIntegerToRetryForSecondsAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryForSeconds annotation must be 0 or greater but got "-1"');
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
     */
    public function testNoArgumentToRetryIfMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryIfMethod annotation requires a callable as an argument');
        $this->getRetryIfMethodAnnotation();
    }

    public function testEmptyStringToRetryIfMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryIfMethod annotation requires a callable as an argument');
        $this->validateRetryIfMethodAnnotation('');
    }

    public function testInvalidCallableArgumentTypeToRetryIfMethodAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryIfMethod annotation must be a method in your test class but got "nonexistantIfMethod"');
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
     */
    public function testNoArgumentToRetryIfExceptionAnnotation(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryIfException annotation requires a class name as an argument');
        $this->getRetryIfExceptionAnnotations();
    }

    /**
     * @retryIfException ThisClassDoesNotExist
     */
    public function testRetryIfExceptionWithInvalidClassname(): void
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The @retryIfException annotation must be an instance of Exception but got "ThisClassDoesNotExist"');
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
