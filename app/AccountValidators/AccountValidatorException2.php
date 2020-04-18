<?php

namespace App\AccountValidators;

use App\Weight;

class AccountValidatorException2 extends AccountValidator
{
    // 0 0 1 2 5 3 6 4 8 7 10 9 3 1
    static $overrideWeights1 = ['0', '0', '1', '2', '5', '3', '6', '4', '8', '7', '10', '9', '3', '1'];
    // 0 0 0 0 0 0 0 0 8 7 10 9 3 1
    static $overrideWeights2 = ['0', '0', '0', '0', '0', '0', '0', '0', '8', '7', '10', '9', '3', '1'];

    /**
     * If multiple tests are being performed do we have to pass all of them
     */
    public function passAllTests()
    {
        // We do not need to pass all tests, if there are multiple
        return false;
    }

    /**
     * The weights can be substituted
     */
    public function checkForOverrideWeights()
    {
        // Check the account number for possible weight substitution
        $substituteWeights = null;
        $a = (int)substr($this->accountNumber, 0, 1);
        $g = (int)substr($this->accountNumber, 6, 1);

        if (0 <> $a && 9 <> $g) {
            $substituteWeights = self::$overrideWeights1;
        } elseif (0 <> $a && 9 === $g) {
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
