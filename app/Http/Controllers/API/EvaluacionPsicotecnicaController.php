<?php

namespace App\Http\Controllers\API;

use App\Impresiones\FormularioPsicotecnicoPDF;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\EvaluacionTecnica;
use App\Models\ExtendidoEvaluacionPsicotecnica;
use App\Models\PsicoEvaluador;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\EvaluacionPsicotecnica;
use Illuminate\Support\Facades\DB;

class EvaluacionPsicotecnicaController extends AppBaseController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'idAgenteLoggedIn' => 'integer',
            'tipo_entrevista' => 'required|string',
            'fecha_evaluacion' => 'required|date',
            'desempeno' => 'required|integer',
            'aspectos_cognitivos' => 'required|integer',
            'aspectos_psicoafectivos'=> 'required|integer',
            'motivacion'=> 'required|integer',
            'condicionantes' => 'required|boolean',
            'experiencia_laboral' => 'required',
            'sector_publico' => 'required',
            'idtipo_puesto' => 'required|integer',
            'observaciones' => 'required',
            'atencion_usuario' => 'required',
            'trabajo_en_equipo' => 'required',
            'adaptabilidad' => 'required',
            'tolerancia_presion' => 'required',
            'organizacion' => 'required',
            'reune_competencias' => 'required',
        ]);
        $psicoevaluador = PsicoEvaluador::where('idagente',$request->idAgenteLoggedIn)->first();
        //dd($psicoevaluador);
        if(!isset($psicoevaluador)) {
            $data ='Error debe iniciar sesión un psicólogo autorizado.';

            return response()->json($data, 403);
        } else {
            $ep = new EvaluacionPsicotecnica();

            $ep->tipo_entrevista = $request->tipo_entrevista;
            $ep->fecha_evaluacion = $request->fecha_evaluacion;
            $ep->desempeno = $request->desempeno;
            $ep->aspectos_cognitivos = $request->aspectos_cognitivos;
            $ep->aspectos_psicoafectivos = $request->aspectos_psicoafectivos;
            $ep->motivacion = $request->motivacion;
            $ep->condicionantes = $request->condicionantes;
            $ep->tipo_condicionantes = $request->tipo_condicionantes;
            $ep->experiencia_laboral = $request->experiencia_laboral;
            $ep->sector_publico = $request->sector_publico;
            $ep->reune_competencias = $request->reune_competencias;
            $ep->observaciones = $request->observaciones;
            $ep->idtipo_funcion = $request->idtipo_puesto;
            $ep->atencion_usuario = $request->atencion_usuario;
            $ep->trabajo_en_equipo = $request->trabajo_en_equipo;
            $ep->adaptabilidad = $request->adaptabilidad;
            $ep->tolerancia_presion = $request->tolerancia_presion;
            $ep->organizacion = $request->organizacion;
            $ep->idpsicoevaluador = $psicoevaluador->idpsicoevaluador;
            $ep->evaluacion_psicotecnica_id = $request->idagente;

            if($request->isAgente) {
                $agente = Agente::findOrFail($request->idagente);
                $ep->evaluacion_psicotecnica_type = get_class($agente);
            } else {
                $candidato = Candidato::findOrFail($request->idagente);
                $ep->evaluacion_psicotecnica_type = get_class($candidato);
            }
            DB::beginTransaction();
            if($ep->save()) {
                //Controlo si hay que agregar informe extendido
                if(!$request->reune_competencias) {
                    if(isset($request->ext_presentacion) && isset($request->ext_cognitivos)
                        && isset($request->ext_relacional) && isset($request->ext_motivacion)) {
                        $extendido = new ExtendidoEvaluacionPsicotecnica();
                        $extendido->presentacion = $request->ext_presentacion;
                        $extendido->aspectos_cognitivos = $request->ext_cognitivos;
                        $extendido->modalidad_relacional = $request->ext_relacional;
                        $extendido->motivacion = $request->ext_motivacion;
                        $extendido->idevaluacion_psicotecnica = $ep->idevaluacion_psicotecnica;

                        if($extendido->save()) {
                            DB::commit();
                            $data = [
                                'success' => true,
                                'message' => 'Evaluación creada con éxito',
                            ];
                            return response()->json($data, 200);
                        } else {
                            DB::rollBack();
                            $data = [
                                'success' => false,
                                'message' => '*Error al crear la evaluación, verifique los campos obligatorios.',
                            ];
                            return response()->json($data, 422);
                        }
                    } else {
                        DB::rollBack();
                        $data = [
                            'success' => false,
                            'message' => '*Error al crear la evaluación, verifique los campos obligatorios.',
                        ];
                        return response()->json($data, 422);
                    }
                }
                DB::commit();
                $data = [
                    'success' => true,
                    'message' => 'Evaluación creada con éxito',
                ];
                return response()->json($data, 200);
            } else {
                DB::rollBack();
                $data = [
                    'success' => false,
                    'message' => '*Error al crear la evaluación, verifique los campos obligatorios.',
                ];
                return response()->json($data, 422);
            }
        }
        //dd($psicoevaluador);
    }

    public function print($id)
    {
        $psicotecnico = EvaluacionPsicotecnica::find($id)->formatoPDF();
        //dd($psicotecnico);
        $pdf = new FormularioPsicotecnicoPDF('P','mm','A4');
        $pdf->AddPage();
        $pdf->DatosPostulante($psicotecnico['postulante']);
        $pdf->PerfilLaboral($psicotecnico['perfil_laboral']);
        $pdf->Firma($psicotecnico['firma']);
        $pdf->TablaCompetencias($psicotecnico['competencias']);
        $pdf->Output();
        exit;
    }

    public function consolidado($filtros=null)
    {
        //dd(json_decode($filtros,true));
        if(isset($filtros)) {
            return EvaluacionPsicotecnica::consolidado(json_decode($filtros,true));
        }

        return EvaluacionPsicotecnica::consolidado();
    }




}
