<?php

namespace App\Http\Controllers\API;

use App\Models\Agente;
use App\Models\Candidato;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EvaluacionTecnica;
use App\Impresiones\FormularioTecnicoPDF;

class EvaluacionTecnicaAPIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'idAgenteLoggedIn' => 'integer',
            'fecha_evaluacion' => 'required|date',
            'escrito' => 'boolean',
            'teorico' => 'boolean',
            'practico' => 'boolean',
            'conclusion' => 'string',
            'conclusion_tipo' => 'required|integer',
        ]);

        $eval = new EvaluacionTecnica();
        $eval->fecha_evaluacion = $request->fecha_evaluacion;
        $eval->escrito = $request->escrito;
        $eval->teorico = $request->teorico;
        $eval->practico = $request->practico;
        $eval->conclusion = $request->conclusion;
        $eval->conclusion_rango = $request->conclusion_tipo;
        $eval->candidato_id = $request->idagente;

        if($request->isAgente) {
            $agente = Agente::findOrFail($request->idagente);
            $eval->candidato_type = get_class($agente);
        } else {
            $candidato = Candidato::findOrFail($request->idagente);
            $eval->candidato_type = get_class($candidato);
        }

        if($eval->save()) {
            $data = [
                'success' => true,
                'message' => 'Evaluación creada con éxito',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'success' => false,
                'message' => '*Error al crear la evaluación, verifique los campos obligatorios.',
            ];
            return response()->json($data, 422);
        }

    }

    public function print($id)
    {
        $evaluacion_tecnica = EvaluacionTecnica::find($id)->formatoPDF();
        $pdf = new FormularioTecnicoPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->DatosPostulante($evaluacion_tecnica['postulante']);
        $pdf->ModoEvaluacion($evaluacion_tecnica['modo_evaluacion']);
        $pdf->Conclusion($evaluacion_tecnica['conclusion']);
        $pdf->Output();
        exit;
    }

    public function consolidado()
    {
        $data = EvaluacionTecnica::exportarExcel();

        return $data;
    }
}
