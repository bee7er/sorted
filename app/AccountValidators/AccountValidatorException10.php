<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException10
 * @package App\AccountValidators
 */
class AccountValidatorException10 extends AccountValidator
{
    /**
     * If multiple tests are being performed do we have to pass all of them
     */
    public function passAllTests()
    {
        // We do not need to pass all tests, if there are multiple
        return false;
    }

    /**
     * Check if we should adjust the weights for this exception
     */
    public function checkForOverrideWeights()
    {
        $a = (int)substr($this->accountNumber, 0, 1);
        $b = (int)substr($this->accountNumber, 1, 1);
        $g = (int)substr($this->accountNumber, 6, 1);

        if (
            ((0 === $a && 9 === $b) || (9 === $a && 9 === $b))
            && 9 === $g
        ) {
            // Zeroise some of the weights
            $this->weight->u = 0;
            $this->weight->v = 0;
            $this->weight->w = 0;
            $this->weight->x = 0;
            $this->weight->y = 0;
            $this->weight->z = 0;
            $this->weight->a = 0;
            $this->weight->b = 0;
        }
    }
}
