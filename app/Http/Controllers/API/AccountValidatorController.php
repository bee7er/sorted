<?php

namespace App\Http\Controllers\API;

use Exception;

use App\AccountValidatorFactory;
use App\Http\Controllers\Controller;
use App\Substitute;
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
            // Does the sort code appear in the substitution table
            $substituteSortCode = Substitute::query()
                ->whereNull('inactivated_at')
                ->where('original_sort_code', '=', $sortCode)
                ->get();

            $originalSortCode = $sortCode;
            if (!$substituteSortCode->isEmpty()) {
                $sortCode = $substituteSortCode->first()->substitute_sort_code;
            }

            //todo Create a service to manage the validators
            //todo Validate the length of the sort code
            //todo Validate the length of the account number
            //todo See non-standard account numbers

            // Get the EISCD (Extended Industry Sorting Code Directory) weightings record
            $weights = Weight::query()
                ->whereNull('inactivated_at')
                ->where('start', '<=', $sortCode)
                ->where('end', '>=', $sortCode)
                ->get()->all();

            if (null === $weights || 0 >= count($weights)) {
                return [
                    'valid' => true,
                    'message' => "Sort code not found in the EISCD table and cannot be checked",
                    'original-sortcode' => $sortCode,
                    'calculation-sortcode' => $sortCode,
                    'eiscd-sortcode' => false
                ];
            }

            $result = [];
            $testCounter = 1;
            // Does the account number pass the modulus checks
            foreach ($weights as $weight) {

                // Instantiate a validator object to continue processing the sort code and account number combination
                $validator = (new AccountValidatorFactory())->getInstance(
                    $testCounter,
                    $weight,
                    $weights,
                    $originalSortCode,
                    $sortCode,
                    $accountNumber
                );

                $result = $validator->isValid();

                if (!$result && $validator->passAllTests()) {
                    // This test has failed and we must pass all of them, so exit with failed result
                    break;
                } elseif ($result && !$validator->passAllTests()) {
                    // This test has passed and we only need either test, so exit with passed result
                    break;
                }

                ++$testCounter;
            }

            return $result;

        } catch (Exception $e) {
            return [
                'valid' => false,
                'message' => "Error processing sort code: " . $e->getMessage(),
                'original-sortcode' => $sortCode,
                'calculation-sortcode' => $sortCode,
                'eiscd-sortcode' => true
            ];
        }
    }
}
