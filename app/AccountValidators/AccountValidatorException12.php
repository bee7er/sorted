<?php

namespace App\AccountValidators;

class AccountValidatorException12 extends AccountValidator
{
    /**
     * If multiple tests are being performed do we have to pass all of them
     */
    public function passAllTests()
    {
        // We do not need to pass all tests, if there are multiple
        return false;
    }
}
