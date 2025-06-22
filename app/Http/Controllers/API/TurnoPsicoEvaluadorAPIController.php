<?php

namespace App\Http\Controllers\API;

use App\Models\PsicoEvaluador;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\TurnoPsicoEvaluador;
use App\Models\Agente;
use App\Models\Candidato;
use App\Mail\TurnoPsicoEvaluador as AsignacionDeTurno;
use App\Mail\TurnoPsicoEvaluadorCancelar;
use App\Mail\TurnoPsicoEvaluadorReprogramar;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TurnoPsicoEvaluadorAPIController extends AppBaseController
{

    public function asignarPsicoEvaluador(Request $request)
    {
        $this->validate($request, [
            'candidato_id' => 'required|integer',
            'isAgente' => 'required|boolean',
            'idpsicoevaluador' => 'required|integer',
        ]);

        if($request->isAgente) {
            $candidato = Agente::findOrFail($request->candidato_id);
        } else {
            $candidato = Candidato::findOrFail($request->candidato_id);
        }
        $psicoevaluador = PsicoEvaluador::findOrFail($request->idpsicoevaluador);
        $turno = new TurnoPsicoEvaluador();
        $turno->idpsicoevaluador = $request->idpsicoevaluador;
        $turno->candidato_id = $request->candidato_id;
        $turno->candidato_type = get_class($candidato);
        if($turno->save()) {
            $data = [
                'success' => true,
                'message' => 'Psicólogo asignado con éxito',
            ];
            $this->enviarEmailAsignacion($psicoevaluador);
            return response()->json(['data' => $data, 201]);
        } else {
            $data = [
                'success' => false,
                'message' => 'Error al asignar psicologo, intente nuevamente o contacte con un administrador del sistema.',
            ];
            return response()->json(['data' => $data, 422]);
        }
    }

    public function asignarTurno(Request $request)
    {
        $this->validate($request, [
            'idturno_psicoevaluador' => 'required|integer',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        $turno = TurnoPsicoEvaluador::findOrFail($request->idturno_psicoevaluador);

        $turno->fecha = $request->fecha;
        $turno->hora = $request->hora;

        if($turno->save()) {
            $data = [
                'success' => true,
                'message' => 'Turno asignado con éxito',
            ];
            return response()->json(['data' => $data, 201]);
        } else {
            $data = [
                'success' => false,
                'message' => 'Error al asignar el turno, contacte con un administrador del sistema.',
            ];
        }
    }

    public function buscarTurnos(int $idpsicoevaluador, $fecha=null)
    {
        if(!is_null($fecha)) {
            $turnos = TurnoPsicoEvaluador::where('idpsicoevaluador', $idpsicoevaluador)
                ->where('fecha', $fecha)
                ->orderBy('hora')->get();
        } else {
            $turnos = TurnoPsicoEvaluador::where('idpsicoevaluador', $idpsicoevaluador)
                ->orderBy('fecha','DESC')
                ->orderBy('hora','ASC')->get();
        }

        if(count($turnos)>0) {
            $data = [];
            foreach($turnos as $key => $turno) {
                $data[$key] = [
                    'idturno_psicoevaluador' => $turno->idturno_psicoevaluador,
                    'apellido' => $turno->candidato->apellido,
                    'nombre' => $turno->candidato->nombre,
                    'documento' => $turno->candidato->documento,
                    'fecha' => date("d/m/Y", strtotime($turno->fecha)),
                    'hora' => date("H:i", strtotime($turno->hora)),
                    'fecha_en' => date("Y-m-d",strtotime($turno->fecha)),
                    'hora_en' => $turno->hora,
                ];
            }
            return response()->json($data,200);
        }

        $data = [
            'success' => false,
            'message' => 'No se han encontrado turnos en la fecha solicitada.'
        ];
        return response()->json($data,204);

    }

    public function buscarTurnosTodos(int $idpsicoevaluador)
    {
        $factual = Carbon::now();

        $turnos = TurnoPsicoEvaluador::where('idpsicoevaluador', $idpsicoevaluador)
                ->orderBy('created_at','DESC')->get();


        if(count($turnos)>0) {
            $data = [];
            foreach($turnos as $key => $turno) {
                $data[$key] = [
                    'idturno_psicoevaluador' => $turno->idturno_psicoevaluador,
                    'apellido' => $turno->candidato->apellido,
                    'nombre' => $turno->candidato->nombre,
                    'documento' => $turno->candidato->documento,
                    'fecha' => $turno->fecha ? $turno->fecha->format('d/m/Y'):"NO ASIGNADO",
                    'hora' => $turno->hora ? date("H:i", strtotime($turno->hora)):"NO ASIGNADO",
                    'fecha_en' => $turno->fecha,
                    'hora_en' => $turno->hora,
                ];
            }
            return response()->json(['data' => $data],200);
        }

        $data = [
            'success' => false,
            'message' => 'No se han encontrado turnos en la fecha solicitada.'
        ];
    }


    public function listadoDeCandidatosPorPsicoEvaluador(int $idpsicoevaluador) {

        $psicoevaluador = PsicoEvaluador::where('idagente', $idpsicoevaluador)->first();

        $turnosSinAsignar = TurnoPsicoEvaluador::where('idpsicoevaluador', $psicoevaluador->idpsicoevaluador)
                                                ->where('fecha', null)
                                                ->where('hora', null)
                                                ->get();
        if(count($turnosSinAsignar)===0) {
            $data = [
                'success' => true,
                'message' => 'No se encontraron candidatos asignados',
            ];
        }
        $data = [];
        foreach($turnosSinAsignar as $key => $c) {
            $data[$key] = [
                'idagente' => $c->candidato->idagente,
                'apellido' => $c->candidato->apellido,
                'nombre' => $c->candidato->nombre,
                'documento' => $c->candidato->documento,
                'idturno_psicoevaluador' => $c->idturno_psicoevaluador,
            ];
        }
        return response()->json(['data' => $data], 201 );
    }

    public function reprogramarTurno(Request $request, int $idturno_psicoevaluador)
    {
        //dd($request);
        $this->validate($request, [
            'fecha' => 'required',
            'hora' => 'required',
        ]);

        $turno = TurnoPsicoEvaluador::findOrFail($idturno_psicoevaluador);
        DB::beginTransaction();
        $turno->fecha = $request->fecha;
        $turno->hora = $request->hora;
        if($turno->save()) {
            $this->enviarReprogramacionTurno($turno);
            DB::commit();
            $data = [
                'success' => true,
                'message' => 'Turno reprogramado con éxito',
            ];
            return response()->json($data, 200);
        }  else {
            DB::rollback();
            $data = [
                'success' => true,
                'message' => 'Ocurrio un error al eliminar el turno, consulte un administrador',
            ];
            return response()->json($data, 422);
        }
    }

    public function eliminarTurno(int $idturno_psicoevaluador)
    {
        $turno = TurnoPsicoEvaluador::findOrFail($idturno_psicoevaluador);
        $psicoevaluador = PsicoEvaluador::findOrFail($turno->idpsicoevaluador);
        $candidato = [
            'nombre' => $turno->candidato->apellido.", ".$turno->candidato->nombre,
            'documento' => $turno->candidato->documento
        ];
        DB::beginTransaction();
        if($turno->delete()) {
            DB::commit();
            $this->enviarEmailCancelacion($psicoevaluador,$candidato);
            $data = [
                'success' => true,
                'message' => 'Turno eliminado con éxito',
            ];
            return response()->json($data, 201);

        } else {
            DB::rollback();
            $data = [
                'success' => true,
                'message' => 'Ocurrio un error al eliminar el turno, consulte un administrador',
            ];
            return response()->json($data, 422);
        }
    }

    private function enviarEmailAsignacion($psicoevaluador)
    {

        Mail::to($psicoevaluador->email)->send(new AsignacionDeTurno($psicoevaluador));

        return "email enviado correctamente";
    }

    private function enviarEmailCancelacion($psicoevaluador, $candidato)
    {
        Mail::to(env('DPTO_SELECCION_EMAIL'))->send(new TurnoPsicoEvaluadorCancelar($psicoevaluador, $candidato));

        return "email enviado correctamente";
    }

    private function enviarReprogramacionTurno($turno)
    {
        Mail::to(env('DPTO_SELECCION_EMAIL'))->send(new TurnoPsicoEvaluadorReprogramar($turno));

        return "email enviado correctamente";
    }

}
