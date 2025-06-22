<?php

namespace App\Http\Controllers\API;

use App\Exports\RecomendacionCandidatoExport;
use App\Models\Agente;
use App\Models\Candidato;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\RecomendacionCandidato;

class RecomendacionCandidatoAPIController extends AppBaseController
{
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'candidato_id' => 'required|integer',
            'idtipo_referido_interno' => 'required|integer',
            'idtipo_referido_politico' => 'required|integer',
            'idtipo_funcion' => 'required|integer',
            'idtitulo' => 'required|integer',
            'idtipo_nivel' => 'required|integer'
        ]);

        $agente = Agente::findOrFail($request->candidato_id);
        $recomendacion = new RecomendacionCandidato();
        $recomendacion->candidato_id = $request->candidato_id;
        $recomendacion->candidato_type = get_class($agente);
        $recomendacion->idtipo_referido_interno = $request->idtipo_referido_interno;
        $recomendacion->idtipo_referido_politico = $request->idtipo_referido_politico;
        $recomendacion->idtipo_funcion = $request->idtipo_funcion;
        $recomendacion->idtitulo = $request->idtitulo;
        $recomendacion->idtipo_nivel = $request->idtipo_nivel;

        if ($recomendacion->save()) {
            $data = [
                'success' => true,
                'message' => 'Candidato guardado correctamente.',
            ];
            return response()->json(['data' => $data, 201]);
        } else {
            $data = [
                'success' => false,
                'message' => '*Error al crear la recomendación, verifique los campos obligatorios.',
            ];
            return response()->json(['data' => $data, 422]);
        }
    }

    public function consolidado()
    {
        $data = $this->getConsolidado();

        return $data;
    }

    public function exportarConsolidado()
    {
        return (new RecomendacionCandidatoExport())->download('recomendacion.xlsx');
    }

    private function getConsolidado()
    {
        $recomendaciones = RecomendacionCandidato::get()->sortBy('candidato.apellido');

        $data = [];

        foreach ($recomendaciones as $key => $recomendacion) {
            if (isset($recomendacion->candidato->iddomicilio)) {
                $domicilio = [
                    'direccion' => $recomendacion->candidato->domicilio->calle . ' ' . $recomendacion->candidato->domicilio->numero,
                    'departamento' => $recomendacion->candidato->domicilio->localidad->departamento->departamento,
                    'localidad' => $recomendacion->candidato->domicilio->localidad->localidad,
                    'cp' => $recomendacion->candidato->domicilio->codigo_postal,
                ];
            } else {
                $domicilio = [
                    'direccion' => '',
                    'departamento' => '',
                    'localidad' => '',
                    'cp' => '',
                ];
            }
            $data[$key] = [
                'apynom' => strtoupper($recomendacion->candidato->apellido . ', ' . $recomendacion->candidato->nombre),
                'documento' => $recomendacion->candidato->documento,
                'domicilio' => $domicilio,
                'telefono' => $recomendacion->candidato->telefono,
                'celular' => $recomendacion->candidato->celular,
                'email' => $recomendacion->candidato->email,
                'formacion' => $recomendacion->formacion->titulo,
                'especialidad' => $recomendacion->especialidad->tipofuncion,
                'nivel' => $recomendacion->nivel->tiponivel,
                'referido1' => $recomendacion->referidoInterno->nombre,
                'referido2' => $recomendacion->referidoPolitico->nombre,
            ];
        }

        usort($data, function ($a, $b) {//Ordeno por apynom el array
            return $a['apynom'] <=> $b['apynom'];
        });

        return $data;
    }

    public function getDatosCandidato(int $documento)
    {
        $candidato = Agente::buscarDocumento($documento);
        if (!isset($candidato)) {
            $candidato = Candidato::buscarDocumento($documento);
        }
        if (!isset($candidato)) {
            return $this->sendError('Candidato no encontrado');
        }
        if (isset($candidato->recomendacion) && count($candidato->recomendacion) > 0) {
            $recomendacion = $candidato->recomendacion[0];
            $datosCandidato = [
                'documento' => $candidato->documento,
                'nombre' => $candidato->apellido . ' ' . $candidato->nombre,
                'fnacimiento' => $candidato->fnacimiento->format('d/m/Y'),
                'edad' => $candidato->edad,
                'funcion' => $recomendacion->especialidad->tipofuncion,
                'idtipo_funcion' => $recomendacion->idtipo_funcion,
                'titulo' => $recomendacion->formacion->titulo,
                'nivel' => $recomendacion->nivel->tiponivel,
                'agrupamiento' => $recomendacion->agrupamiento->tipoagrupamiento ?? '',
            ];
            return $this->sendResponse($datosCandidato, 'Datos de Candidato');
        }
        return $this->sendError('Candidato no encontrado');
    }
}
