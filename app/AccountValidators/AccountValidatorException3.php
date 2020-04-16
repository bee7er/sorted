<?php

namespace App\AccountValidators;

use App\Weight;
use RuntimeException;

class AccountValidatorException3 extends AccountValidator
{
    /**
     * Check if we should run the second test
     * @return bool
     */
    public function runSecondTest()
    {
        $c = substr($this->accountNumber, 2, 1);

        if (
            (6 === $c || 9 === $c)
            && self::MOD_CHECK_DBLAL === $this->weight->mod_check
        ) {
            return false;
        }

        return true;
    }
}
