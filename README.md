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
            // retries for 60 seconds
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

There are two main ways to configure delays between retries:

1. Sleep a number of seconds between each retry:
    ```php
    /**
     * @retryAttempts 3
     * @retrySleepSeconds 10
     */
```

1. Sleep a number of seconds which increases exponentially based on the retry attempt:
    ```php
    /**
     * @retryAttempts 3
     * @retryBackoff
     */
```

**Note:** The defalt to `retryBackoff`  is `exponentialBackoffDelay`, which can be defined
in your test, or you can provide a custom method for your delay:

```php
/**
 * @retryAttempts 3
 * @retryBackoff customDelayFunction
 */
```

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
