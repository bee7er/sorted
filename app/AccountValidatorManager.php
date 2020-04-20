<?php

namespace App;

use Exception;

/**
 * Class AccountValidatorManager
 * @package App
 */
class AccountValidatorManager
{
    // Modulus checks
    const MOD_CHECK_DBLAL = 'DBLAL';
    const MOD_CHECK_MOD10 = 'MOD10';
    const MOD_CHECK_MOD11 = 'MOD11';
    // Application messages
    const ACCOUNT_NUMBER_INVALID_MESSAGE = "The account number is invalid";
    const FAIL_MESSAGE = "The account number failed the modulus check";
    const PASS_MESSAGE = "Sort code and account number combination is valid";
    const SORT_CODE_INVALID_MESSAGE = "Sort code is invalid";
    const SORT_CODE_NOT_FOUND_MESSAGE = "Sort code not found";

    /**
     * Validate the specified sort code and account number
     *
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return \Illuminate\Http\Response
     */
    public function validateSortCodeAccountNumber($sortCode, $accountNumber)
    {
        try {
            // Strip out white space from both fields
            $sortCode = preg_replace('/\s+/', '', $sortCode);
            $accountNumber = preg_replace('/\s+/', '', $accountNumber);

            if (!$this->isValidSortCode($sortCode)) {
                return [
                    'valid' => false,
                    'message' => self::SORT_CODE_INVALID_MESSAGE,
                    'original-sortcode' => $sortCode,
                    'calculation-sortcode' => $sortCode,
                    'original-account-number' => $accountNumber,
                    'calculation-account-number' => $accountNumber,
                    'eiscd-sortcode' => false,
                    'numberOfTests' => 0
                ];
            }

            // Get the EISCD (Extended Industry Sorting Code Directory) weightings record
            $weights = Weight::query()
                ->whereNull('inactivated_at')
                ->where('start', '<=', $sortCode)
                ->where('end', '>=', $sortCode)
                ->orderBy('id')
                ->get()->all();

            if (null === $weights || 0 >= count($weights)) {
                return [
                    'valid' => true,
                    'message' => self::SORT_CODE_NOT_FOUND_MESSAGE,
                    'original-sortcode' => $sortCode,
                    'calculation-sortcode' => $sortCode,
                    'original-account-number' => $accountNumber,
                    'calculation-account-number' => $accountNumber,
                    'eiscd-sortcode' => false,
                    'numberOfTests' => 0
                ];
            }

            $result = [];
            // Does the account number pass all the modulus checks
            foreach ($weights as $weight) {

                // Instantiate a validator object to continue processing the sort code and account number combination
                $validator = (new AccountValidatorFactory())->getInstance(
                    $weight,
                    $weights,
                    $sortCode,
                    $accountNumber
                );

                $result = $validator->isValid();

                if (!$result['valid'] && $validator->repeatTest()) {
                    // Just run the same test again, nb we will have adjusted the parameters for the second check
                    $result = $validator->isValid();
                }

                if ($result['valid'] && !$validator->passAllTests()) {
                    // This test has passed and we don't need to perform the second test, if there
                    // is one, so exit with passed result
                    break;
                }

                if (!$result['valid'] && $validator->passAllTests()) {
                    // This test failed and we have to pass all tests, exit with failed test
                    break;
                }
            }

            return $result;

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => "Error processing sort code and account number: " . $e->getMessage(),
                'original-sortcode' => $sortCode,
                'calculation-sortcode' => $sortCode,
                'original-account-number' => $accountNumber,
                'calculation-account-number' => $accountNumber,
                'eiscd-sortcode' => true,
                'numberOfTests' => 'unknown'
            ];
        }
    }

    /**
     * Validate sort code, which must be a 6 digit number
     *
     * @param  string  $sortCode
     * @return bool
     */
    private function isValidSortCode($sortCode)
    {

        if (6 !== strlen($sortCode) || !is_numeric($sortCode)) {
            return false;
        }

        return true;
    }
}
