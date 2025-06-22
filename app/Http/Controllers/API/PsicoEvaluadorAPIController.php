<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\PsicoEvaluador;

class PsicoEvaluadorAPIController extends AppBaseController
{
    public function listar()
    {
        $psicoevaluadores = PsicoEvaluador::orderBy('firma')->get();
        $data = [];
        foreach($psicoevaluadores as $key => $psicoevaluador)
        {
            $data[$key] = [
                'value' => $psicoevaluador->idpsicoevaluador,
                'label' => $psicoevaluador->firma,
            ];
        }
        return response(['data' => $data],201);
    }

    public function get(int $idagente)
    {
        $psicoevaluador = PsicoEvaluador::where('idagente', $idagente)->first();

        $data = [
            'idpsicoevaluador' => $psicoevaluador->idpsicoevaluador,
            'firma' => $psicoevaluador->firma,
        ];
        return response()->json(['data' => $data, 201]);
    }
}
