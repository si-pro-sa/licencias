<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoFormularioReclamoDePago as TipoFormulario;

class TipoFormularioReclamoDePagoAPIController extends Controller
{
    /**
     * Muestro listado de tipos de formularios utilizando json
     *
     * @return JSON
    */
    public function vueSelect()
    {
        return TipoFormulario::
                                orderBy('tipo_formulario')
                                ->get()
                                ->map(function ($model) {
                                        return [
                                            'value' => $model->idtipo_formulario_reclamo_de_pago,
                                            'label' => $model->tipo_formulario
                                        ];
                            });
    }
}
