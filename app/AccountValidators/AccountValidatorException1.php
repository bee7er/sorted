<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException1
 * @package App\AccountValidators
 */
class AccountValidatorException1 extends AccountValidator
{
    // The adjustment performed prior to doing the modulus check
    static $adjustment = 27;

    /**
     * Performs a modulus check on the calculated total, however, for exception 1
     * we first add 27 to the total
     *
     * @param int $total
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheckOnTotal($total, $modulo)
    {
        return (0 === (($total + self::$adjustment) % $modulo));
    }
}
