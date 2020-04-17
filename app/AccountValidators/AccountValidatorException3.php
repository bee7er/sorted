<?php

namespace App\AccountValidators;

class AccountValidatorException3 extends AccountValidator
{
    /**
     * Check if we should run this test
     * @return bool
     */
    public function runTest()
    {
        $c = (int)substr($this->accountNumber, 2, 1);

        if (
            (6 === $c || 9 === $c)
            && self::MOD_CHECK_DBLAL === $this->weight->mod_check
        ) {
            return false;
        }

        return true;
    }
}
