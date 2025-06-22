<?php

namespace App\Http\Controllers\API;

use App\Models\TipoCargo;
use App\Http\Controllers\AppBaseController;

/**
 * Class TipoCargoController
 * @package App\Http\Controllers\API
 */

class TipoCargoAPIController extends AppBaseController
{
    /**
     * Muestro listado de tipos de Cargo utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposCargo = TipoCargo::orderBy('tipocargo')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idtipo_cargo, 'label' => $model->tipocargo];
        });
        return $this->sendResponse($tiposCargo, 'Listado');
    }
}
