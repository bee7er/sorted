<?php

namespace App\AccountValidators;

/**
 * Class AccountValidatorException7
 * @package App\AccountValidators
 */
class AccountValidatorException7 extends AccountValidator
{
    /**
     * Check if we should adjust the weights for this exception
     */
    public function checkForOverrideWeights()
    {
        $g = (int)substr($this->accountNumber, 6, 1);

        if (9 === $g) {
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
