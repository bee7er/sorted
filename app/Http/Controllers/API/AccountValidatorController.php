<?php

namespace App\Http\Controllers\API;

use App\AccountValidators\AccountValidator;
use Exception;

use App\AccountValidatorFactory;
use App\Http\Controllers\Controller;
use App\Weight;

class AccountValidatorController extends Controller
{
    /**
     * Validate the specified sort code and account number
     *
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return \Illuminate\Http\Response
     */
    public function isValid($sortCode, $accountNumber)
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
                    'numberOfTests' => 0,
                    'class' => null
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
                    'numberOfTests' => 0,
                    'class' => null
                ];
            }

            $result = [];
            // Does the account number pass the modulus checks
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
                'message' => "Error processing sort code: " . $e->getMessage(),
                'original-sortcode' => $sortCode,
                'calculation-sortcode' => $sortCode,
                'original-account-number' => $accountNumber,
                'calculation-account-number' => $accountNumber,
                'eiscd-sortcode' => true,
                'numberOfTests' => 0,
                'class' => null
            ];
        }
    }

    /**
     * Validate sort code, which must be a 6 digit number
     *
     * @param  string  $sortCode
     * @return bool
     */
    public function isValidSortCode($sortCode)
    {

        if (6 !== strlen($sortCode) || !is_numeric($sortCode)) {
            return false;
        }

        return true;
    }
}
