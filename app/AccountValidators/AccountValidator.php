<?php

namespace App\AccountValidators;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

use App\Weight;

class AccountValidator extends Model
{
    // Modulus checks
    const MOD_CHECK_DBLAL = 'DBLAL';
    const MOD_CHECK_MOD10 = 'MOD10';
    const MOD_CHECK_MOD11 = 'MOD11';

    const ACCOUNT_NUMBER_INVALID_MESSAGE = "The account number is invalid";
    const FAIL_MESSAGE = "The account number failed the modulus check";
    const PASS_MESSAGE = "Sort code and account number combination is valid";
    const SORT_CODE_INVALID_MESSAGE = "Sort code is invalid";
    const SORT_CODE_NOT_FOUND_MESSAGE = "Sort code not found";

    protected $weight;
    protected $weights = [];
    protected $originalSortCode;
    protected $sortCode;
    protected $originalAccountNumber;
    protected $accountNumber;
    protected $testWasRun;
    protected $testCounter;

    /**
     * AccountValidator constructor
     *
     * @param Weight $weight
     * @param array $weights
     * @param $sortCode
     * @param $accountNumber
     */
    public function __construct(
        Weight $weight, array $weights, $sortCode, $accountNumber
    ) {
        parent::__construct();

        $this->weight = $weight;
        $this->weights = $weights;
        $this->originalSortCode = $sortCode;
        $this->sortCode = $sortCode;
        $this->originalAccountNumber = $accountNumber;
        $this->accountNumber = $accountNumber;
    }

    /**
     * Validates a sort code and account number combination
     *
     * @return array
     */
    public function isValid()
    {
        // If the account number is non-standard there are things we can do
        $checkDetails = $this->checkAccountNumber($this->sortCode, $this->accountNumber);
        if (false === $checkDetails) {
            return [
                'valid' => false,
                'message' => AccountValidator::ACCOUNT_NUMBER_INVALID_MESSAGE,
                'original-sortcode' => $this->sortCode,
                'calculation-sortcode' => $this->originalSortCode,
                'original-account-number' => $this->accountNumber,
                'calculation-account-number' => $this->originalAccountNumber,
                'eiscd-sortcode' => true,
                'numberOfTests' => 0,
                'class' => $this->getClassName()
            ];
        }

        if (is_array($checkDetails)) {
            // We have adjusted one or both parameters
            $this->sortCode = $checkDetails['sortCode'];
            $this->accountNumber = $checkDetails['accountNumber'];
        }

        $result = $this->processWeight();
        $numberOfTests = count(array_filter($this->weights, function ($weight) { return $weight->testWasRun; }));

        if (!$result) {
            return [
                'valid' => $result,
                'message' => self::FAIL_MESSAGE,
                'original-sortcode' => $this->originalSortCode,
                'calculation-sortcode' => $this->sortCode,
                'original-account-number' => $this->originalAccountNumber,
                'calculation-account-number' => $this->accountNumber,
                'numberOfTests' => $numberOfTests,
                'eiscd-sortcode' => true,
                'class' => $this->getClassName()
            ];
        }

        // Success
        return [
            'valid' => $result,
            'message' => self::PASS_MESSAGE,
            'original-sortcode' => $this->originalSortCode,
            'calculation-sortcode' => $this->sortCode,
            'original-account-number' => $this->originalAccountNumber,
            'calculation-account-number' => $this->accountNumber,
            'numberOfTests' => $numberOfTests,
            'eiscd-sortcode' => true,
            'class' => $this->getClassName()
        ];
    }

    /**
     * Process the class name
     *
     * @return bool
     */
    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * Process the weight record(s) for a given sort code
     *
     * @return bool
     */
    public function processWeight()
    {
        $this->weight->passesTest = true;
        // Under some circumstances we do not actually run the test
        if (!$this->runTest()) {
            return $this->weight->passesTest;
        }

        $this->weight->testWasRun = true;

        $this->checkForOverrideWeights();

        $this->checkForOverrideSortCode();

        switch ($this->weight->mod_check) {
            case self::MOD_CHECK_DBLAL:
                $this->weight->passesTest = $this->doDblAlModulusCheck();
                break;
            case self::MOD_CHECK_MOD10:
                $this->weight->passesTest = $this->doModulusCheck(10);
                break;
            case self::MOD_CHECK_MOD11:
                $this->weight->passesTest = $this->doModulusCheck(11);
                break;
            default:
                throw new RuntimeException(sprintf("Unexpected modulus check '%s' encountered", $this->weight->mod_check));
        }

        return $this->weight->passesTest;
    }

    /**
     * Sometimes we do not run a particular test; but mostly we do
     *
     * @return bool
     */
    public function runTest()
    {
        return true;
    }


    /**
     * If multiple tests are being performed do we have to pass all of them; normally we do
     */
    public function passAllTests()
    {
        return true;
    }

    /**
     * Under certain conditions we override the weights used
     */
    public function checkForOverrideWeights()
    {
        // We don't normally do any overriding of the weight details
    }

    /**
     * Under certain conditions we override the incoming sort code
     */
    public function checkForOverrideSortCode()
    {
        // We don't normally do any overriding of the sort code
    }

    /**
     * Performs a double alternate modulus check on an account number
     * @return bool
     */
    public function doDblAlModulusCheck()
    {
        // Get the DBLAL total based on the sort code and account number
        $total = $this->getDblAlTotal();

        // Now we do a modulus check on the total; always modulo 10 for the DBLAL check
        return $this->doModulusCheckOnTotal($total, 10);
    }

    /**
     * Calculate the total for the double alternate test
     * @return int
     */
    protected function getDblAlTotal()
    {
        // Create one long string of the sort code and account number
        $calcString = $this->sortCode . $this->accountNumber;

        $pos = $total = 0;
        $data = [];
        // Iterate the weight fields and multiply each one by the corresponding portion of the
        // sort code / account number string
        foreach (Weight::FIELDS as $field) {
            // Select the sort code / account number portion to apply the weight to
            $num = (int)substr($calcString, $pos, 1);
            // Convert the resulting number to a string array
            $interimResultAry = str_split($num * (int)$this->weight->$field);

            foreach ($interimResultAry as $interimResultDigit) {
                $data[] = $interimResultDigit;
                // Add up the individual digits
                $total += (int)$interimResultDigit;
            }
            // Next position
            ++$pos;
        }

        return $total;
    }

    /**
     * Performs a modulus check on an account number
     *
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheck($modulo)
    {
        // Get the normal modulus check total based on the sort code and account number
        $total = $this->getNormalTotal();

        // Now we do a modulus check on the total
        return $this->doModulusCheckOnTotal($total, $modulo);
    }

    /**
     * Calculate the total for the double alternate test
     *
     * @return int
     */
    protected function getNormalTotal()
    {
        // Create one long string of the sort code and account number
        $calcString = $this->sortCode . $this->accountNumber;

        $pos = $total = 0;
        // Iterate the weight fields and multiply each one by the corresponding portion of the
        // sort code / account number string
        foreach (Weight::FIELDS as $field) {
            // Select the sort code / account number portion to apply the weight to, and accumulate the result
            $num = (int)substr($calcString, $pos, 1);
            $total += $num * (int)$this->weight->$field;
            // Next position
            ++$pos;
        }

        return $total;
    }

    /**
     * Performs a modulus check on the calculated total
     *
     * @param int $total
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheckOnTotal($total, $modulo)
    {
        return (0 === ($total % $modulo));
    }

    /**
     * Check the account number and possibly adjust it
     *
     * @param  string  $sortCode
     * @param  string  $accountNumber
     * @return bool|array
     */
    public function checkAccountNumber($sortCode, $accountNumber)
    {
        // If alphanumeric, less than 6 or greater than 10 digits there is nothing we can do
        $len = strlen($accountNumber);

        switch (1) {
            case !is_numeric($accountNumber):
                return false;

            case 6 === $len:
                return [
                    'sortCode' => $sortCode,
                    'accountNumber' => ('00' . $accountNumber)
                ];

            case 7 === $len:
                return [
                    'sortCode' => $sortCode,
                    'accountNumber' => ('0' . $accountNumber)
                ];

            case 8 === $len:
                return true;

            case 9 === $len:
                // Replace the last digit of the sort code with the first digit of
                // the account number and use only the last 8 digits of the account number
                $sortCode = substr($sortCode, 0, 5) . substr($accountNumber, 0, 1);
                $accountNumber = substr($accountNumber, 1, 8);
                return [
                    'sortCode' => $sortCode,
                    'accountNumber' => $accountNumber
                ];

            case 10 === $len:
                // Coop Bank or Leeds Bldg Society, use the first 8 digits
//                $accountNumber = substr($accountNumber, 0, 8);
                // Nat West Bank, use the last 8 digits
                $accountNumber = substr($accountNumber, 2, 8);
                return [
                    'sortCode' => $sortCode,
                    'accountNumber' => $accountNumber
                ];
        }

        return false;
    }
}
