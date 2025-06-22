<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Piso implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (preg_match('/pb|[0-9]/i', $value) == 1) ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Debe ingresar un número de piso o PB(Planta Baja).';
    }
}
