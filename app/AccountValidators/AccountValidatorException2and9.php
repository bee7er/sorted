<?php

namespace App\AccountValidators;

use App\Weight;
use RuntimeException;

class AccountValidatorException2and9 extends AccountValidator
{
    // 0 0 1 2 5 3 6 4 8 7 10 9 3 1
    static $overrideWeights1 = ['0', '0', '1', '2', '5', '3', '6', '4', '8', '7', '10', '9', '3', '1'];
    // 0 0 0 0 0 0 0 0 8 7 10 9 3 1
    static $overrideWeights2 = ['0', '0', '0', '0', '0', '0', '0', '0', '8', '7', '10', '9', '3', '1'];
    // The exception when we override the sort code
    static $overrideSortCodeException = 9;
    // Lloyds euro accounts sort code
    static $overrideSortCode = '309634';

    /**
     * If multiple tests are being performed do we have to pass all of them
     */
    public function passAllTests()
    {
        // Failed on first we can go ahead and try with the second
        return false;
    }

    /**
     * Under certain conditions we override the incoming sort code
     *
     * @param Weight $weight
     */
    public function checkForOverrideSortCode()
    {
        if (self::$overrideSortCodeException === $this->weight->exception) {
            // Try with the Lloyds euro accounts sort code
            $this->sortCode = self::$overrideSortCode;
        }
    }

    /**
     * The weights can be substituted
     */
    public function checkForOverrideWeights()
    {
        // Check the account number for possible weight substitution
        $substituteWeights = null;
        $a = substr($this->accountNumber, 0, 1);
        $g = substr($this->accountNumber, 6, 1);

        if ($a <> 0 && $g <> 9) {
            $substituteWeights = self::$overrideWeights1;
        } elseif ($a <> 0 && $g = 9) {;
            $substituteWeights = self::$overrideWeights2;
        }

        if (null !== $substituteWeights) {
            $pos = 0;
            foreach (Weight::FIELDS as $field) {
                // Override the field from the substitutes value
                $this->weight->$field = $substituteWeights[$pos];
                // Next position
                ++$pos;
            }
        }
    }
}
