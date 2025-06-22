<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Periodo;
use App\Models\Puesto;

class AgenteDebeTenerPuesto implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Periodo $periodo)
    {
        $this->periodo = $periodo;
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
        $puesto = Puesto::where('idagente', $value)
            ->with('tipoPlanta')
            ->where('fdesde', '<=', $this->periodo->fhasta)
            ->where(function ($query) {
                $query
                    ->where('fhasta', '>=', $this->periodo->fdesde)
                    ->orWhereNull('fhasta');
            })
            ->first();
        return isset($puesto);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El agente no tiene puesto asignado en el periodo solicitado';
    }
}
