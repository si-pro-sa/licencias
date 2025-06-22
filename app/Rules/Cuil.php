<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Cuil implements Rule
{
    protected $documento;

    public function __construct($documento)
    {
        $this->documento = $documento;
    }

    public function passes($attribute, $value)
    {
        $documentoInCUIL = substr($value, 2, 8);

        if ($documentoInCUIL !== $this->documento) {
            return false;
        }

        // Remove non-numeric characters
        $value = preg_replace('/[^0-9]/', '', $value);

        // CUIL must have 11 digits
        if (strlen($value) !== 11) {
            return false;
        }

        // Validate the CUIL checksum
        $multipliers = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $checksum = 0;

        for ($i = 0; $i < 10; $i++) {
            $checksum += intval($value[$i]) * $multipliers[$i];
        }

        $checksum %= 11;
        $checksum = 11 - $checksum;

        if ($checksum === 11) {
            $checksum = 0;
        }

        return intval($value[10]) === $checksum;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El CUIL es inválido';
    }
}
