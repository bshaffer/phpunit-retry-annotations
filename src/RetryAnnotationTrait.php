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
        $retries = 0;

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
                'The @retryAttempts annotation requires an integer as an argument'
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
        if ($retries < 0) {
            throw new LogicException(sprintf(
                'The @retryAttempts annotation must be 0 or greater but got "%s".',
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
                'The @retryDelaySeconds annotation requires an integer as an argument'
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
     * @return string
     */
    private function getRetryDelayMethodAnnotation()
    {
        $annotations = $this->getAnnotations();

        if (isset($annotations['method']['retryDelayMethod'][0])) {
            $delayAnnotation = $annotations['method']['retryDelayMethod'];
        } elseif (isset($annotations['class']['retryDelayMethod'][0])) {
            $delayAnnotation = $annotations['class']['retryDelayMethod'];
        } else {
            return;
        }

        $delayAnnotations = explode(' ', $delayAnnotation[0]);
        $delayMethod = $delayAnnotations[0];
        $delayMethodArgs = array_slice($delayAnnotations, 1);

        return [
            $this->validateRetryDelayMethodAnnotation($delayMethod),
            $delayMethodArgs,
        ];
    }

    /**
     * @return string
     * @throws LogicException
     */
    private function validateRetryDelayMethodAnnotation($delayMethod)
    {
        if ('' === $delayMethod) {
            throw new LogicException(
                'The @retryDelayMethod annotation requires a callable as an argument'
            );
        }
        if (false === is_callable([$this, $delayMethod])) {
            throw new LogicException(sprintf(
                'The @retryDelayMethod annotation must be a method in your test class but got "%s"',
                $delayMethod
            ));
        }
        return $delayMethod;
    }

    /**
     * @return int
     */
    private function getRetryForSecondsAnnotation()
    {
        $annotations = $this->getAnnotations();

        if (isset($annotations['method']['retryForSeconds'][0])) {
            $retryForSeconds = $annotations['method']['retryForSeconds'][0];
        } elseif (isset($annotations['class']['retryForSeconds'][0])) {
            $retryForSeconds = $annotations['class']['retryForSeconds'][0];
        } else {
            return;
        }

        return $this->validateRetryForSecondsAnnotation($retryForSeconds);
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateRetryForSecondsAnnotation($retryForSeconds)
    {
        if ('' === $retryForSeconds) {
            throw new LogicException(
                'The @retryForSeconds annotation requires an integer as an argument'
            );
        }
        if (false === is_numeric($retryForSeconds)) {
            throw new LogicException(sprintf(
                'The @retryForSeconds annotation must be an integer but got "%s"',
                var_export($retryForSeconds, true)
            ));
        }
        if (floatval($retryForSeconds) != intval($retryForSeconds)) {
            throw new LogicException(sprintf(
                'The @retryForSeconds annotation must be an integer but got "%s"',
                floatval($retryForSeconds)
            ));
        }
        $retryForSeconds = (int) $retryForSeconds;
        if ($retryForSeconds < 0) {
            throw new LogicException(sprintf(
                'The @retryForSeconds annotation must be 0 or greater but got "%s".',
                $retryForSeconds
            ));
        }
        return $retryForSeconds;
    }

    /**
     * @return array|null
     */
    private function getRetryIfExceptionAnnotations()
    {
        $annotations = $this->getAnnotations();

        if (isset($annotations['method']['retryIfException'][0])) {
            $retryIfExceptions = [];
            foreach ($annotations['method']['retryIfException'] as $retryIfException) {
                $retryIfExceptions[] = $this->validateRetryIfExceptionAnnotation($retryIfException);
            }
            return $retryIfExceptions;
        }
    }

    /**
     * @return int
     * @throws LogicException
     */
    private function validateRetryIfExceptionAnnotation($retryIfException)
    {
        if ('' === $retryIfException) {
            throw new LogicException(
                'The @retryIfException annotation requires a class name as an argument'
            );
        }

        if (!class_exists($retryIfException)) {
            throw new LogicException(sprintf(
                'The @retryIfException annotation must be an instance of Exception but got "%s"',
                $retryIfException
            ));
        }

        return $retryIfException;
    }

    /**
     * @return string
     */
    private function getRetryIfMethodAnnotation()
    {
        $annotations = $this->getAnnotations();

        if (!isset($annotations['method']['retryIfMethod'][0])) {
            return;
        }

        $retryIfMethodAnnotation = explode(' ', $annotations['method']['retryIfMethod'][0]);
        $retryIfMethod = $retryIfMethodAnnotation[0];
        $retryIfMethodArgs = array_slice($retryIfMethodAnnotation, 1);

        return [
            $this->validateRetryIfMethodAnnotation($retryIfMethod),
            $retryIfMethodArgs,
        ];
    }

    /**
     * @return string
     * @throws LogicException
     */
    private function validateRetryIfMethodAnnotation($retryIfMethod)
    {
        if ('' === $retryIfMethod) {
            throw new LogicException(
                'The @retryIfMethod annotation requires a callable as an argument'
            );
        }
        if (false === is_callable([$this, $retryIfMethod])) {
            throw new LogicException(sprintf(
                'The @retryIfMethod annotation must be a method in your test class but got "%s"',
                $retryIfMethod
            ));
        }
        return $retryIfMethod;
    }
}
