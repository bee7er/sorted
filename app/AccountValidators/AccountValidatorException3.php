<?php

namespace App\AccountValidators;

use App\AccountValidatorManager;

/**
 * Class AccountValidatorException3
 * @package App\AccountValidators
 */
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
            && AccountValidatorManager::MOD_CHECK_DBLAL === $this->weight->mod_check
        ) {
            return false;
        }

        return true;
    }
}
