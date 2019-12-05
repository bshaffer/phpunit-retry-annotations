<?php

namespace PHPUnitRetry;

use LogicException;
use PHPUnit\Framework\IncompleteTestError;
use PHPUnit\Framework\SkippedTestError;
use Throwable;
use Exception;
use Error;

/**
 * Trait for adding a @retry annotation to retry flakey tests.
 *
 * @see https://blog.forma-pro.com/retry-an-erratic-test-fc4d928c57fb
 */
trait RetryTrait
{
    use RetryAnnotationTrait;

    public function runBare()
    {
        $e = null;
        $retries = $this->getRetryAttemptsAnnotation();
        for ($i = 0; $i < $retries; ++$i) {
            if ($i > 0) {
                // Log retry
                $this->logRetryAttempt($i + 1, $retries);
                // Execute delay function
                $this->executeRetryDelayFunction($i + 1);
            }
            try {
                return parent::runBare();
            } catch (IncompleteTestError $e) {
                throw $e;
            } catch (SkippedTestError $e) {
                throw $e;
            } catch (Throwable $e) {
            } catch (Exception $e) {
            } catch (Error $e) {
            }
        }
        if ($e) {
            throw $e;
        }
    }

    private function logRetryAttempt($attempt, $maxAttempts)
    {
        printf('[RETRY] Attempt %s of %s' . PHP_EOL, $attempt, $maxAttempts);
    }

    private function executeRetryDelayFunction($attempt)
    {
        $delaySeconds = $this->getRetryDelaySecondsAnnotation();
        $delayMethod = $this->getRetryDelayMethodAnnotation();

        if ($delaySeconds && $delayMethod) {
            throw new LogicException('Cannot set both @retryDelaySeconds and @retryDelayMethod');
        }

        if ($delaySeconds) {
            sleep($delaySeconds);
        } elseif ($delayMethod) {
            call_user_func([$this, $delayMethod], $attempt);
        }
    }
}
