<?php

namespace App\AccountValidators;

use App\Substitute;

class AccountValidatorException5 extends AccountValidator
{
    /**
     * We override the incoming sort code for this exception
     */
    public function checkForOverrideSortCode()
    {
        // Does the sort code appear in the substitution table
        $substitute = Substitute::query()
            ->whereNull('inactivated_at')
            ->where('original_sort_code', '=', $this->sortCode)
            ->get();

        if (!$substitute->isEmpty()) {
            // There exists a substitute, use it
            $this->sortCode = $substitute->first()->substitute_sort_code;
        }
    }

    /**
     * Performs a double alternate modulus check on an account number
     * @return bool
     */
    public function doDblAlModulusCheck()
    {
        // Get the DBLAL total based on the sort code and account number
        $total = $this->getDblAlTotal();
        $remainder = $total % 10;

        // For exception 5 we compare the remainder with part of the account number, in this case $h
        $h = (int)substr($this->accountNumber, 7, 1);

        switch ($remainder) {
            case 0:
                return (0 === $h);

            default:
                // For all other remainders subtract them from 11 and compare with $h
                return ((10 - $remainder) === $h);
        }
    }

    /**
     * Performs a modulus check on the calculated total
     *
     * @param int $total
     * @param int $modulo
     * @return bool
     */
    public function doModulusCheckOnTotal($total, $modulo)
    {
        $remainder = $total % $modulo;
        // For exception 5 we compare the remainder with part of the account number
        $g = (int)substr($this->accountNumber, 6, 1);

        switch ($remainder) {
            case 0:
                return (0 === $g);

            case 1:
                return false;

            default:
                // For all other remainders subtract them from 11 and compare with $g
                return ((11 - $remainder) === $g);
        }
    }
}
