<?php

namespace App;

use App\AccountValidators\AccountValidatorException6;
use RuntimeException;

use App\AccountValidators\AccountValidator;
use App\AccountValidators\AccountValidatorException1;
use App\AccountValidators\AccountValidatorException2;
use App\AccountValidators\AccountValidatorException3;
use App\AccountValidators\AccountValidatorException4;
use App\AccountValidators\AccountValidatorException5;
use App\AccountValidators\AccountValidatorException9;
use App\AccountValidators\AccountValidatorException12;

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
     * @param  Weight  $weight
     * @param  array   $weights
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return \App\AccountValidators\AccountValidator
     */
    public static function getInstance(Weight $weight, $weights, $sortCode, $accountNumber)
    {
        $cnt = count($weights);
        if (2 < $cnt) {
            throw new RuntimeException(sprintf("Unexpected number of sort code weighting records %d", $cnt));
        }

        $exception = (int)$weight->exception;

        switch ($exception) {
            case (1):
                return new AccountValidatorException1($weight, $weights, $sortCode, $accountNumber);

            case (2):
                return new AccountValidatorException2($weight, $weights, $sortCode, $accountNumber);

            case (3):
                return new AccountValidatorException3($weight, $weights, $sortCode, $accountNumber);

            case (4):
                return new AccountValidatorException4($weight, $weights, $sortCode, $accountNumber);

            case (5):
                return new AccountValidatorException5($weight, $weights, $sortCode, $accountNumber);

            case (6):
                return new AccountValidatorException6($weight, $weights, $sortCode, $accountNumber);

            case (9):
                return new AccountValidatorException9($weight, $weights, $sortCode, $accountNumber);

            case (12):
                return new AccountValidatorException12($weight, $weights, $sortCode, $accountNumber);

            default:
                // Not an exceptional sort code; just use the base class
                return new AccountValidator($weight, $weights, $sortCode, $accountNumber);
        }
    }
}
