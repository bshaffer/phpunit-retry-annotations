<?php

namespace PHPUnitRetry;

use LogicException;
use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\SkippedTestError;
use Throwable;
use Exception;
use Error;

/**
 * Trait for validating @retry annotations.
 */
trait RetryAnnotationTrait
{
    /**
     * @return int
     */
    private function getRetryAttemptsAnnotation()
    {
        $annotations = $this->getAnnotations();
        $retries = 1;

        if (isset($annotations['class']['retryAttempts'][0])) {
            $retries = $annotations['class']['retryAttempts'][0];
        }
        if (isset($annotations['method']['retryAttempts'][0])) {
            $retries = $annotations['method']['retryAttempts'][0];
        }

        return $this->validateRetries($retries);
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateRetries($retries)
    {
        if ('' === $retries) {
            throw new LogicException(
                'The @retryAttempts annotation requires a positive integer as an argument'
            );
        }
        if (false === is_numeric($retries)) {
            throw new LogicException(sprintf(
                'The @retryAttempts annotation must be an integer but got "%s"',
                var_export($retries, true)
            ));
        }
        if (floatval($retries) != intval($retries)) {
            throw new LogicException(sprintf(
                'The @retryAttempts annotation must be an integer but got "%s"',
                floatval($retries)
            ));
        }
        $retries = (int) $retries;
        if ($retries <= 0) {
            throw new LogicException(sprintf(
                'The @retryAttempts annotation must be greater than 0 but got "%s".',
                $retries
            ));
        }
        return $retries;
    }

    /**
     * @return int
     */
    private function getRetrySleepSecondsAnnotation()
    {
        $annotations = $this->getAnnotations();
        $retrySleepSeconds = 0;

        if (isset($annotations['class']['retrySleepSeconds'][0])) {
            $retrySleepSeconds = $annotations['class']['retrySleepSeconds'][0];
        }
        if (isset($annotations['method']['retrySleepSeconds'][0])) {
            $retrySleepSeconds = $annotations['method']['retrySleepSeconds'][0];
        }

        return $this->validateSleepSeconds($retrySleepSeconds);
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateSleepSeconds($retrySleepSeconds)
    {
        if ('' === $retrySleepSeconds) {
            throw new LogicException(
                'The @retrySleepSeconds annotation requires a positive integer as an argument'
            );
        }
        if (false === is_numeric($retrySleepSeconds)) {
            throw new LogicException(sprintf(
                'The @retrySleepSeconds annotation must be an integer but got "%s"',
                var_export($retrySleepSeconds, true)
            ));
        }
        if (floatval($retrySleepSeconds) != intval($retrySleepSeconds)) {
            throw new LogicException(sprintf(
                'The @retrySleepSeconds annotation must be an integer but got "%s"',
                floatval($retrySleepSeconds)
            ));
        }
        $retrySleepSeconds = (int) $retrySleepSeconds;
        if ($retrySleepSeconds < 0) {
            throw new LogicException(sprintf(
                'The @retrySleepSeconds annotation must be 0 or greater but got "%s".',
                $retrySleepSeconds
            ));
        }
        return $retrySleepSeconds;
    }

    /**
     * @return string|null
     */
    private function getRetryExceptionAnnotation()
    {
        $annotations = $this->getAnnotations();
        $retries = 1;

        if (isset($annotations['method']['retryException'][0])) {
            $retryException = $annotations['method']['retryException'][0];
            if ('' === $retryException) {
                throw new LogicException(
                    'The @retrySleepSeconds annotation requires a positive integer as an argument'
                );
            }
            return $retryException;
        }
    }
}
