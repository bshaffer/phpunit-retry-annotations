# PHPUnit Retry

Traits for retrying test methods and classes in PHPUnit.

## Installation

```
composer require --dev bshaffer/phpunit-retry-annotations
```

## Configuring retries

### Retry using a specified number of retries

```php
/**
 * @retryAttempts 2
 */
class MyTest extends PHPUnit\Framework\TestCase
{
    use PHPUnitRetry\RetryTrait;

    public function testSomethingFlakeyTwice()
    {
        // Retry a flakey test up to two times
    }

    /**
     * @retryAttempts 3
     */
    public function testSomethingFlakeyThreeTimes()
    {
        // Retry a flakey test up to three times
    }
}
```

**NOTE:** "Attempts" represents the number of times a test is retried.
Providing "@retryAttempts" a value of 0 has no effect, and would not retry.

### Retry until a specific duration has passed

```php
/**
 * @retryForSeconds 90
 */
class MyTest extends PHPUnit\Framework\TestCase
{
    use PHPUnitRetry\RetryTrait;

    public function testSomethingFlakeyFor90Seconds()
    {
        // retries for 90 seconds
    }

    /**
     * @retryForSeconds 1800
     */
    public function testSomethingFlakeyFor30Minutes()
    {
        // retries for 30 minutes
    }
}
```

## Configuring retry conditions


### Retry only for certain exceptions

By default, retrying happens when any exception other than
`PHPUnit\Framework\IncompleteTestError` and `PHPUnit\Framework\SkippedTestError`
is thrown.

Because you may not always want to retry, you can configure your test to only
retry under certain conditions. For example, you can only retry if your tests
throw a certain exception.

```php
/**
 * @retryAttempts 3
 * @retryIfException MyApi\ResourceExhaustedException
 */
```

You can retry for multiple exceptions.

```php
/**
 * @retryAttempts 3
 * @retryIfException MyApi\RateLimitExceededException
 * @retryIfException ServiceUnavailableException
 */
```

### Retry based on a custom method

For more complex logic surrounding whether you should retry, define a custom
retry method:

```php
/**
 * @retryAttempts 3
 * @retryIfMethod isRateLimitExceededException
 */
public function testWithCustomRetryMethod()
{
    // retries only if the method `isRateLimitExceededException` returns true.
}

/**
 * @param Exception $e
 */
private function isRateLimitExceededException(Exception $e)
{
    // Check if HTTP Status code is 429 "Too many requests"
    return ($e instanceof HttpException && $e->getStatusCode() == 429);
}
```

Define arbitrary arguments for your retry method by passing them into the
annotation:

```php
/**
 * @retryAttempts 3
 * @retryIfMethod exceptionStatusCode 429
 */
public function testWithCustomRetryMethod()
{
    // retries only if the method `exceptionStatusCode` returns true.
}

/**
 * @param Exception $e
 */
private function exceptionStatusCode(Exception $e, $statusCode)
{
    // Check if HTTP status code is $statusCode
    return ($e instanceof HttpException && $e->getStatusCode() == $statusCode);
}
```
## Configuring delay

### Delay for a duration between each retry

```php
/**
 * @retryAttempts 3
 * @retryDelaySeconds 10
 */
```

### Delay for an amount increasing exponentially based on the retry attempt

```php
/**
 * @retryAttempts 3
 * @retryDelayMethod exponentialBackoff
 */
```

The behavior of the `exponentialBackoff` method is to start at 1
second and increase to a maximum of 60 seconds. The maximum delay can be
customized by supplying a second argument to the annotation

```php
/**
 * This test will delay with exponential backoff, with a maximum delay of 10 minutes.
 *
 * @retryAttempts 30
 * @retryDelayMethod exponentialBackoff 600
 */
```

### Define a custom delay method

```php
/**
 * @retryAttempts 3
 * @retryDelayMethod myCustomDelay
 */
public function testWithCustomDelay()
{
    // retries using the method `myCustomDelay`.
}

/**
 * @param int $attempt The current test attempt
 */
private function myCustomDelay($attempt)
{
    // Doubles the sleep each attempt, but not longer than 10 seconds.
    sleep(min($attempt * 2, 10));
}
```

Define arbitrary arguments for your delay function by passing them into the
annotation:

```php
/**
 * @retryAttempts 3
 * @retryDelayMethod myCustomDelay 10 60
 */
public function testWithCustomDelay()
{
    // retries using the method `myCustomDelay`.
}

/**
 * @param int $attempt The current test attempt.
 * @param int $multiplier Rate of exponential backoff delay.
 * @param int $maxDelay Maximum time to wait regardless of retry attempt.
 */
private function myCustomDelay($attempt, $multiplier, $maxDelay)
{
    // Increases exponentially
    sleep(min($attempt * $multiplier, $max));
}
```
