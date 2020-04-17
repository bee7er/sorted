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

    protected $weight;
    protected $weights = [];
    protected $originalSortCode;
    protected $sortCode;
    protected $accountNumber;
    protected $testWasRun;
    protected $testCounter;

    /**
     * AccountValidator constructor
     *
     * @param Weight $weight
     * @param array $weights
     * @param $originalSortCode
     * @param $sortCode
     * @param $accountNumber
     */
    public function __construct(
        Weight $weight, array $weights, $originalSortCode, $sortCode, $accountNumber
    ) {
        parent::__construct();

        $this->weight = $weight;
        $this->weights = $weights;
        $this->originalSortCode = $originalSortCode;
        $this->sortCode = $sortCode;
        $this->accountNumber = $accountNumber;
    }

    /**
     * Validates a sort code and account number combination
     *
     * @return array
     */
    public function isValid()
    {
        $result = $this->processWeight();
        $numberOfTests = count(array_filter($this->weights, function ($weight) { return $weight->testWasRun; }));

        if (!$result) {
            return [
                'valid' => $result,
                'message' => "The account number failed the modulus check",
                'original-sortcode' => $this->originalSortCode,
                'calculation-sortcode' => $this->sortCode,
                'numberOfTests' => $numberOfTests,
                'eiscd-sortcode' => true,
                'class' => $this->getClassName()
            ];
        }

        // Success
        return [
            'valid' => $result,
            'message' => "Sort code is valid",
            'original-sortcode' => $this->originalSortCode,
            'calculation-sortcode' => $this->sortCode,
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
        // Does the account number pass the modulus checks
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
        // Create one long string of the sort code and account number
        $calcString = $this->sortCode . $this->accountNumber;

        $pos = 0;
        $total = 0;
        // Iterate the weight fields and multiply each one by the corresponding portion of the
        // sort code / account number string
        foreach (Weight::FIELDS as $field) {
            // Select the sort code / account number portion to apply the weight to
            $num = (int)substr($calcString, $pos, 1);
            // Convert the resulting number to a string array
            $interimResultAry = str_split($num * (int)$this->weight->$field);
            foreach ($interimResultAry as $interimResultDigit) {
                // Add up the individual digits
                $total += (int)$interimResultDigit;
            }
            // Next position
            ++$pos;
        }

        // Now we do a modulus check on the total; always modulo 10 for the DBLAL check
        return $this->doModulusCheckOnTotal($total, 10);
    }

    /**
     * Performs a modulus check on an account number
     *
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheck($modulo)
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

        // Now we do a modulus check on the total
        return $this->doModulusCheckOnTotal($total, $modulo);
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
}
