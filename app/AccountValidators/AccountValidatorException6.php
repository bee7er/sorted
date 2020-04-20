<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException6
 * @package App\AccountValidators
 */
class AccountValidatorException6 extends AccountValidator
{
    /**
     * Check if we should run this test
     * @return bool
     */
    public function runTest()
    {
        $a = (int)substr($this->accountNumber, 0, 1);
        $g = (int)substr($this->accountNumber, 6, 1);
        $h = (int)substr($this->accountNumber, 7, 1);

        if (in_array($a, [4,5,6,7,8]) && $g === $h) {
            // Foreign currency account, we cannot do the check
            return false;
        }

        return true;
    }
}
