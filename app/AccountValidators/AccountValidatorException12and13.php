<?php

namespace App\AccountValidators;

class AccountValidatorException12and13 extends AccountValidator
{
    /**
     * If multiple tests are being performed do we have to pass all of them
     */
    public function passAllTests()
    {
        // Exit if the first check is valid, since the account number is valid if either of the checks is valid
        if (1 < $this->testCounter && $this->weights[0]->passesTest) {
            return true;
        }
        return false;
    }
}
