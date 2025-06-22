<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\TipoPlanta;

class TipoPlantaAPIController extends AppBaseController
{
    //Select DataTable
    public function dataTablesSelect()
    {
        return $this->sendResponse(TipoPlanta::all()->pluck('tipoplanta'), 'Tipos Planta');
    }
    /**
     * Muestro listado de tipos de sexo utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        return TipoPlanta::orderBy('tipoplanta')
            ->get()
            ->makeHidden(['usuario', 'foperacion', 'operacion'])
            ->map(function ($model) {
                return [
                        'value' => $model->idtipo_planta,
                        'label' => $model->tipoplanta
                    ];
            });
    }
}
