<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Agente;

class AgenteDebeTenerPsicotecnicoAprobado implements Rule
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
        return Agente::findOrFail($value)->psicotecnicoAprobado();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El agente no tiene psicotecnicos aprobados';
    }
}
