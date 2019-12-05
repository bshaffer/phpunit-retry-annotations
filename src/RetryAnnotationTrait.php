<?php

namespace PHPUnitRetry;

use LogicException;

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

        if (isset($annotations['method']['retryAttempts'][0])) {
            $retries = $annotations['method']['retryAttempts'][0];
        } elseif (isset($annotations['class']['retryAttempts'][0])) {
            $retries = $annotations['class']['retryAttempts'][0];
        }

        return $this->validateRetryAttemptsAnnotation($retries);
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateRetryAttemptsAnnotation($retries)
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
    private function getRetryDelaySecondsAnnotation()
    {
        $annotations = $this->getAnnotations();
        $retryDelaySeconds = 0;

        if (isset($annotations['method']['retryDelaySeconds'][0])) {
            $retryDelaySeconds = $annotations['method']['retryDelaySeconds'][0];
        } elseif (isset($annotations['class']['retryDelaySeconds'][0])) {
            $retryDelaySeconds = $annotations['class']['retryDelaySeconds'][0];
        }

        return $this->validateRetryDelaySecondsAnnotation($retryDelaySeconds);
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateRetryDelaySecondsAnnotation($retryDelaySeconds)
    {
        if ('' === $retryDelaySeconds) {
            throw new LogicException(
                'The @retryDelaySeconds annotation requires a positive integer as an argument'
            );
        }
        if (false === is_numeric($retryDelaySeconds)) {
            throw new LogicException(sprintf(
                'The @retryDelaySeconds annotation must be an integer but got "%s"',
                var_export($retryDelaySeconds, true)
            ));
        }
        if (floatval($retryDelaySeconds) != intval($retryDelaySeconds)) {
            throw new LogicException(sprintf(
                'The @retryDelaySeconds annotation must be an integer but got "%s"',
                floatval($retryDelaySeconds)
            ));
        }
        $retryDelaySeconds = (int) $retryDelaySeconds;
        if ($retryDelaySeconds < 0) {
            throw new LogicException(sprintf(
                'The @retryDelaySeconds annotation must be 0 or greater but got "%s".',
                $retryDelaySeconds
            ));
        }
        return $retryDelaySeconds;
    }

    /**
     * @return int
     */
    private function getRetryDelayMethodAnnotation()
    {
        $annotations = $this->getAnnotations();

        if (isset($annotations['method']['retryDelayMethod'][0])) {
            $retryDelayMethod = $annotations['method']['retryDelayMethod'][0];
        } elseif (isset($annotations['class']['retryDelayMethod'][0])) {
            $retryDelayMethod = $annotations['class']['retryDelayMethod'][0];
        } else {
            return;
        }

        return $this->validateRetryDelayMethodAnnotation($retryDelayMethod);
    }

    /**
     * @return string
     * @throws LogicException
     */
    private function validateRetryDelayMethodAnnotation($retryDelayMethod)
    {
        if ('' === $retryDelayMethod) {
            throw new LogicException(
                'The @retryDelayMethod annotation requires a callable as an argument'
            );
        }
        if (false === is_callable([$this, $retryDelayMethod])) {
            throw new LogicException(sprintf(
                'The @retryDelayMethod annotation must be a method in your test class but got "%s"',
                $retryDelayMethod
            ));
        }
        return $retryDelayMethod;
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
                    'The @retryDelaySeconds annotation requires a positive integer as an argument'
                );
            }
            return $retryException;
        }
    }
}
