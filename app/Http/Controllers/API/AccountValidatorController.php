<?php

namespace App\Http\Controllers\API;

use Exception;

use App\AccountValidatorManager;
use App\Http\Controllers\Controller;

class AccountValidatorController extends Controller
{
    /**
     * Manage the process of validating the specified sort code and account number
     *
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return \Illuminate\Http\Response
     */
    public function isValid($sortCode, $accountNumber)
    {
        try {
            $accountValidatorManager = new AccountValidatorManager();

            return $accountValidatorManager->validateSortCodeAccountNumber($sortCode, $accountNumber);

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => "Unexpected error: " . $e->getMessage(),
                'original-sortcode' => $sortCode,
                'original-account-number' => $accountNumber
            ];
        }
    }
}
