<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Periodo;
use App\Models\Reemplazo;

class AgenteNoDebeTenerMasDeUnReemplazo implements Rule
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
        return Reemplazo::where('idagente_reemplazante', $value)
            ->where('idperiodo', $this->periodo_ep->idperiodo)
            ->where('aprobado', true)
            ->get()->count() <= 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El agente tiene mas de un reemplazo en el periodo de EP';
    }
}
