<?php

namespace PHPUnitRetry;

use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\SkippedTestError;
use Exception;

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

    /**
     * @param int $attemept
     * @return bool
     */
    private function checkShouldRetryAgain($retryAttempt)
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
            if (is_null(self::$timeOfFirstRetry)) {
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
                $secondsRemaining == 1 ? 'second' : 'seconds'
            );
        }

        // Execute delay function
        $this->executeRetryDelayFunction($retryAttempt);

        return true;
    }

    /**
     * @param int $attemept
     * @return bool
     */
    private function checkShouldRetryForException(Exception $e)
    {
        if ($retryIfExceptions = $this->getRetryIfExceptionAnnotations()) {
            foreach ($retryIfExceptions as $retryIfException) {
                if ($e instanceof $retryIfException) {
                    return true;
                }
            }
            return false;
        } elseif ($retryIfMethodAnnotation = $this->getRetryIfMethodAnnotation()) {
            [$retryIfMethod, $retryIfMethodArgs] = $retryIfMethodAnnotation;

            array_unshift($retryIfMethodArgs, $e);
            call_user_func_array([$this, $retryIfMethod], $retryIfMethodArgs);
        }

        // Retry all exceptions by default
        return true;
    }

    private function executeRetryDelayFunction($retryAttempt)
    {
        if ($delaySeconds = $this->getRetryDelaySecondsAnnotation()) {
            sleep($delaySeconds);
        } elseif ($delayMethodAnnotation = $this->getRetryDelayMethodAnnotation()) {
            [$delayMethod, $delayMethodArgs] = $delayMethodAnnotation;
            array_unshift($delayMethodArgs, $retryAttempt);
            call_user_func_array([$this, $delayMethod], $delayMethodArgs);
        }
    }

    private function exponentialBackoff($retryAttempt, $maxDelaySeconds = 60)
    {
        $sleep = min(
            mt_rand(0, 1000000) + (pow(2, $retryAttempt) * 1000000),
            $maxDelaySeconds * 1000000
        );
        usleep($sleep);
    }
}
