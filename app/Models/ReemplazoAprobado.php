<?php

namespace App\Models;

use App\Models\Reemplazo;
use App\Models\ReemplazoVisado;
use App\Models\ReemplazoValidation;

class ReemplazoAprobado implements ReemplazoVisado
{
    use ReemplazoValidation;
    
    private $reemplazo = null;

    public function __construct(int $id)
    {
        $this->reemplazo = Reemplazo::with([
            'puestoReemplazado',
            'puestoReemplazante',
            'efectivaPrestacionReemplazos',
            'dependencia'
            ])
        ->where('idreemplazo', $id)
        ->firstOrFail();
    }

    public function visar()
    {
        if (isset($this->reemplazo)) {
            $this->reemplazo->aprobado = true;
            $this->reemplazo->desaprobado = false;

            $formErrors = $this->validate();
            if ($formErrors === true && $this->reemplazo->save()) {
                return true;
            }
            return $formErrors;
        }
    }
}
