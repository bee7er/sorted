<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException9
 * @package App\AccountValidators
 */
class AccountValidatorException9 extends AccountValidator
{
    // Lloyds euro accounts sort code
    static $overrideSortCode = '309634';

    /**
     * We override the incoming sort code for this exception
     */
    public function checkForOverrideSortCode()
    {
        // Try with the Lloyds euro accounts sort code
        $this->sortCode = self::$overrideSortCode;
    }
}
