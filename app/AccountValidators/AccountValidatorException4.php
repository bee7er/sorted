<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException4
 * @package App\AccountValidators
 */
class AccountValidatorException4 extends AccountValidator
{
    /**
     * Performs a modulus check on the calculated total
     *
     * @param int $total
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheckOnTotal($total, $modulo)
    {
        // For exception 4 we compare the remainder with part of the account number
        $gh = (int)substr($this->accountNumber, 6, 2);

        return ($gh === ($total % $modulo));
    }
}
