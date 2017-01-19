<?php

namespace Mnabialek\LaravelDotpay;

use Mnabialek\LaravelDotpay\Exceptions\MissingParameter;

class Verifier
{
    /**
     * Verify whether required parameters are filled.
     *
     * @param array $parameters
     *
     * @return bool
     * @throws MissingParameter
     */
    public function check(array $parameters)
    {
        foreach ($this->required() as $param) {
            if (empty($parameters[$param])) {
                throw new MissingParameter("Required parameter {$param} is empty!");
            }
        }

        return true;
    }

    /**
     * Get minimum required fields that should be filled in.
     *
     * @return array
     */
    protected function required()
    {
        return [
            'api_version',
            'id',
            'amount',
            'currency',
            'description',
            'lang',
        ];
    }
}
