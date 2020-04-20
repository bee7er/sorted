<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException8
 * @package App\AccountValidators
 */
class AccountValidatorException8 extends AccountValidator
{
    static $overrideSortCode = '090126';

    /**
     * We override the incoming sort code for this exception
     */
    public function checkForOverrideSortCode()
    {
        // For exception 8 we always override the sort code
        $this->sortCode = self::$overrideSortCode;
    }
}
