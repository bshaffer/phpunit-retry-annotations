<?php

namespace PHPUnitRetry;

use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\SkippedTestError;
use Exception;
use function array_unshift;
use function call_user_func_array;
use function printf;
use function sleep;
use function time;

/**
 * Trait for adding @retry annotations to retry flakey tests.
 */
trait RetryTrait
{
    use RetryAnnotationTrait;

    private static $timeOfFirstRetry;

    public function runBare() : void
    {
        $retryAttempt = 0;
        self::$timeOfFirstRetry = null;

        do {
            try {
                parent::runBare();
                return;
            } catch (IncompleteTestError $e) {
                throw $e;
            } catch (SkippedTestError $e) {
                throw $e;
            } catch (Exception $e) {
            }
            if (!$this->checkShouldRetryForException($e)) {
                throw $e;
            }
            $retryAttempt++;
        } while ($this->checkShouldRetryAgain($retryAttempt));

        throw $e;
    }

    private function checkShouldRetryAgain(int $retryAttempt): bool
    {
        if ($retryAttempts = $this->getRetryAttemptsAnnotation()) {
            // Maximum retry attempts exceeded
            if ($retryAttempt > $retryAttempts) {
                return false;
            }

            // Log retry
            printf(
                '[RETRY] Retrying %s of %s' . PHP_EOL,
                $retryAttempt,
                $retryAttempts
            );
        } elseif ($retryFor = $this->getRetryForSecondsAnnotation()) {
            if (self::$timeOfFirstRetry === null) {
                self::$timeOfFirstRetry = time();
            }

            // Maximum retry duration exceeded
            $secondsRemaining = self::$timeOfFirstRetry + $retryFor - time();
            if ($secondsRemaining < 0) {
                return false;
            }

            // Log retry
            printf(
                '[RETRY] Retrying %s (%s %s remaining)' . PHP_EOL,
                $retryAttempt,
                $secondsRemaining,
                $secondsRemaining === 1 ? 'second' : 'seconds'
            );
        }

        // Execute delay function
        $this->executeRetryDelayFunction($retryAttempt);

        return true;
    }

    private function checkShouldRetryForException(Exception $e): bool
    {
        if ($retryIfExceptions = $this->getRetryIfExceptionAnnotations()) {
            foreach ($retryIfExceptions as $retryIfException) {
                if ($e instanceof $retryIfException) {
                    return true;
                }
            }
            return false;
        }

        if ($retryIfMethodAnnotation = $this->getRetryIfMethodAnnotation()) {
            [$retryIfMethod, $retryIfMethodArgs] = $retryIfMethodAnnotation;

            array_unshift($retryIfMethodArgs, $e);
            call_user_func_array([$this, $retryIfMethod], $retryIfMethodArgs);
        }

        // Retry all exceptions by default
        return true;
    }

    private function executeRetryDelayFunction(int $retryAttempt): ?int
    {
        if ($delaySeconds = $this->getRetryDelaySecondsAnnotation()) {
            sleep($delaySeconds);
        } elseif ($delayMethodAnnotation = $this->getRetryDelayMethodAnnotation()) {
            [$delayMethod, $delayMethodArgs] = $delayMethodAnnotation;
            array_unshift($delayMethodArgs, $retryAttempt);
            call_user_func_array([$this, $delayMethod], $delayMethodArgs);
        }

        return null;
    }
}
