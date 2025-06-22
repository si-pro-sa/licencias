<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\TipoNivel;

class TipoNivelAPIController extends AppBaseController
{
    public function listar()
    {
        $tipoNiveles = TipoNivel::orderBy('tiponivel')->get();
        $data = [];
        foreach($tipoNiveles as $key => $tipoNivel) {
            $data[$key] = [
                'value' => $tipoNivel->idtipo_nivel,
                'label' => $tipoNivel->tiponivel,
            ];
        }
        return response(['data' => $data],200);
    }
}
