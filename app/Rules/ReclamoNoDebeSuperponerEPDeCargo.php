<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Periodo;
use App\Models\Agente;

class ReclamoNoDebeSuperponerEPDeCargo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Periodo $periodo_ep)
    {
        $this->periodo_ep = $periodo_ep;
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
        return Agente::find($value)
            ->novedadAprobada($this->periodo_ep)
            ->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Existe una efectiva prestación de cobertura de cargo aprobada en el periodo solicitado para el agente';
    }
}
