<?php

namespace App\Http\Controllers\API;

use App\Models\CargoTipoFirma;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TipoAgrupamientoController
 * @package App\Http\Controllers\API
 */
class CargoTipoFirmaAPIController extends AppBaseController
{
    /**
     * Muestro listado de tipos de agrupamiento utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposAgrupamiento = CargoTipoFirma::get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idcargo_tipo_firma, 'label' => $model->cargotipo_firma];
        });
        return $tiposAgrupamiento;
    }
}
