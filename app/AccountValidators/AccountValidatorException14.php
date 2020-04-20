<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException14
 * @package App\AccountValidators
 */
class AccountValidatorException14 extends AccountValidator
{
    /**
     * Check if we should adjust the weights for this exception and repeat the test
     */
    public function repeatTest()
    {
        $h = (int)substr($this->accountNumber, 7, 1);
        if (!in_array($h, [0, 1, 9])) {
            // It is not a valid Coutts account, don't bother doing another check
            return false;
        }

        // Remove the last digit and insert a '0' at the front
        $this->accountNumber = ('0' . substr($this->accountNumber, 0, 7));
        // Run the test again
        return true;
    }
}
