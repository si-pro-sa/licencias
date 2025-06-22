<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Periodo;
use App\Models\Agente;
use App\Models\Puesto;
use Illuminate\Support\Facades\DB;

class PuestoNoDebeSerPermanenteOTransitorio implements Rule
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
            ->where('fdesde', '<=', $this->periodo->fhasta)
            ->where(function ($query) {
                $query
                    ->where('fhasta', '>=', $this->periodo->fdesde)
                    ->orWhereNull('fhasta');
            })
            ->first();
        if ($puesto) {
            return !in_array($puesto->idtipo_planta, [1, 2, 6]);
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El Agente tiene puestos transitorios, permanentes, interinos o no tiene planta asignada en el período!.';
    }
}
