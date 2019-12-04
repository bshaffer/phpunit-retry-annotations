# PHPUnit Retry

Traits for retrying test methods and classes in PHPUnit.

## Installation

```
composer require --dev bshaffer/phpunit-retry-annotations
```

## Usage

### Types of retries

There are **two** main ways to configure retries:

1. Retry using a specified number of retries
    ```php
    /**
     * @retryAttempts 2
     */
    class MyTest extends PHPUnit\Framework\TestCase
    {
        use Google\Cloud\TestUtils\RetryTrait;

        public function testSomethingFlakeyTwice()
        {
            // test something flakey twice
        }

        /**
         * @retryAttempts 3
         */
        public function testSomethingFlakeyThreeTimes()
        {
            // test something flakey three times
        }
    }
    ```

1. Retry until a specific duration has passed
    ```php
    /**
     * @retryFor 90s
     */
    class MyTest extends PHPUnit\Framework\TestCase
    {
        use Google\Cloud\TestUtils\RetryTrait;

        public function testSomethingFlakeyFor90Seconds()
        {
            // retries for 90 seconds
        }

        /**
         * @retryFor 30m
         */
        public function testSomethingFlakeyFor30Minutes()
        {
            // retries for 30 minutes
        }
    }
    ```

### Configuring delay

There are **two** main ways to configure delays between retries:

1. Sleep for a duration between each retry:
    ```php
    /**
     * @retryAttempts 3
     * @retrySleep 10s
     */
    ```

1. Sleep for an amount which increases exponentially based on the retry attempt:
    ```php
    /**
     * @retryAttempts 3
     * @retryBackoffExponential
     */
    ```

You can also customize your backoff with a function

```php
/**
 * @retryAttempts 3
 * @retryDelayFunction customDelayFunction
 */
```

**Note:** The defalt to `@retryBackoffExponential` is to start at 1s and
increase to a maximum of 60s over the course of 10 retries. You can define
`exponentialBackoffDelay` in your test to customize this behavior.

### Configuring retry conditions

By default, retrying happens when any exception other than
`PHPUnit\Framework\IncompleteTestError` and `PHPUnit\Framework\SkippedTestError`
is thrown.

Because you may not always want to retry, you can configure your test to only
retry under certain conditions. For example, you can only retry if your tests
throw a certain exception.

```php
/**
 * @retryAttempts 3
 * @retryException MyApi\ResourceExhaustedException
 */
```

Or retry for certain exception messages.

```php
/**
 * @retryAttempts 3
 * @retryException MyApi\ServiceException
 * @retryExceptionMessage rate limit exceeded
 */
```

Or, if you want to retry for multiple exceptions, define a custom function

```php
/**
 * @retryAttempts 3
 * @retryMethod shouldRetryException
 */
```
