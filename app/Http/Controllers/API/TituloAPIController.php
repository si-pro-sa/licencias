<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Titulo;


class TituloAPIController extends AppBaseController
{
    public function listar()
    {
        $titulos = Titulo::orderBy('titulo')->get();
        $data = [];
        foreach($titulos as $key => $titulo)
        {
            if($titulo->idtipo_funcion === 490)
                $titulo->tipofuncion = "Otro";

            $data[$key] = [
                'value' => $titulo->idtitulo,
                'label' => $titulo->titulo,
            ];
        }

        return response(['data' => $data],200);
    }
}
