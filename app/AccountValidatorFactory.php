<?php

namespace App;

use RuntimeException;

use App\AccountValidators\AccountValidator;
use App\AccountValidators\AccountValidatorException1;
use App\AccountValidators\AccountValidatorException2and9;
use App\AccountValidators\AccountValidatorException12and13;

/**
 * Class AccountValidatorFactory
 * Instantiates and returns an object capable of handling the sort code / account number combination
 * @package App
 */
class AccountValidatorFactory
{
    /**
     * Checks the incoming details and returns an appropriate account number validator object
     *
     * @param  int     $testCounter
     * @param  Weight  $weight
     * @param  array   $weights
     * @param  string  $originalSortCode
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return \App\AccountValidators\AccountValidator
     */
    public static function getInstance($testCounter, $weight, $weights, $originalSortCode, $sortCode, $accountNumber)
    {
        $cnt = count($weights);
        if ($cnt !== 1 && $cnt !== 2) {
            throw new RuntimeException(sprintf("Unexpected number of sort code weighting records %d", $cnt));
        }

        $exception1 = $weights[0]->exception;
        $exception2 = isset($weights[1]) ? $weights[1]->exception: null;

        switch (1) {
            case ($exception1 == 1):
                return new AccountValidatorException1($testCounter, $weight, $weights, $originalSortCode, $sortCode,
                    $accountNumber);

            case ($exception1 == 2 && $exception2 == 9):
                return new AccountValidatorException2and9($testCounter, $weight, $weight, $weights, $originalSortCode, $sortCode, $accountNumber);

            case ($exception1 == 12 && $exception2 == 13):
                return new AccountValidatorException12and13($testCounter, $weight, $weights, $originalSortCode, $sortCode, $accountNumber);

            default:
                // Not an exceptional sort code; just use the base class
                return new AccountValidator($testCounter, $weight, $weights, $originalSortCode, $sortCode, $accountNumber);
        }
    }
}
