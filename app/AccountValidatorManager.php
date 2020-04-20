<?php

namespace App;

use App\AccountValidators\AccountValidator;
use Exception;

class AccountValidatorManager
{
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
                    'message' => AccountValidator::SORT_CODE_INVALID_MESSAGE,
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
                    'message' => AccountValidator::SORT_CODE_NOT_FOUND_MESSAGE,
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
