<?php

namespace App\Http\Controllers\API;

use App\Models\Puesto;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Candidato;
use App\Models\Agente;

class SeleccionAPIController extends AppBaseController
{
    /**
     * Find by dni a candidate or agent
     */
    public function findByDocumento($documento)
    {
        $agente = Agente::where('documento', $documento)->first();
        if(isset($agente)){
            $data = $this->getData($agente,true);
            return response()->json($data, 200);
        }else {
            $candidato = Candidato::where('documento', $documento)->first();

            if(isset($candidato)){
                $data = $this->getData($candidato,false);
                return response()->json($data, 200);
            } else{
                $data = [
                    'success' => false,
                    'message' => 'No se encuentra el agente',
                ];
                return response()->json($data,202);
            }
        }
    }

    private function getData($agente, $isAgente)
    {
        $agenteData = [
            'idagente' => $agente->idagente,
            'documento' => number_format($agente->documento,0,',','.'),
            'cuil' => preg_replace('/^(\d{2})(\d{8})(\d+)$/', '$1-$2-$3', $agente->cuil),
            'apellido' => mb_strtoupper($agente->apellido, 'UTF-8'),
            'nombre' => mb_strtoupper($agente->nombre, 'UTF-8'),
            //'genero' => $agente->genero,
            'fnacimiento_es' => $agente->fnacimiento->format('d/m/Y'),
            'fnacimiento' => $agente->fnacimiento->format('Y-m-d'),
            'telefono' => $agente->telefono,
            'celular' => $agente->celular,
            'domicilio' => '',
            'email' => $agente->email,
            'isAgente' => $isAgente,
        ];
        if(isset($agente->iddomicilio)) {
            $domicilio['direccion'] = $agente->domicilio->calle.' '.$agente->domicilio->numero;
            if(isset($agente->domicilio->idlocalidad)) {
                $domicilio['localidad'] = $agente->domicilio->localidad->localidad;
                $domicilio['provincia'] = $agente->domicilio->provincia->nombre;
                $domicilio['calle'] = $agente->domicilio->calle;
                $domicilio['numero'] = $agente->domicilio->numero;
                $domicilio['codigo_postal'] = $agente->domicilio->codigo_postal;
                $domicilio['piso'] = $agente->domicilio->piso;
                $domicilio['departamento'] = $agente->domicilio->departamento;
                $domicilio['block'] = $agente->domicilio->block;
                $domicilio['extras'] = "Piso: " . $agente->domicilio->piso . " - Dpto: ".
                                        $agente->domicilio->departamento . " - Block: ".
                                        $agente->domicilio->block;
            }
            $agenteData['domicilio'] = $domicilio;
        }
        if($isAgente){
            $agenteData['falta'] = date("d/m/Y", strtotime($agente->falta));
            $puestos = Puesto::where('idagente',$agente->idagente)->orderBy('fdesde','DESC')->get();
            $message = "Agente Encontrado con Exito";
        }else {
            $agenteData['idagente'] = $agente->idcandidato;
            $agenteData['falta'] = date("d/m/Y", strtotime($agente->created_at));
          $message = "Candidato Encontrado con Éxito";
        }
        if($agente->celular == null){
            $agenteData['celular'] = "No asignado";
        }
        if($agente->telefono == null){
            $agenteData['telefono'] = "No asignado";
        }
        if($agente->email == null){
            $agenteData['email'] = "No asignado";
        }

        $data = [
            'agente' => $agenteData,
            'puestos' => null,
            'success' => true,
            'message' => $message,
        ];

        $eval_tecnicaData = [];
        foreach($agente->evalTecnicas as $key => $evalTecnica) {

            $eval_tecnicaData[$key] = [
                'idevaluacion_tecnica' => $evalTecnica->idevaluacion_tecnica,
                'fecha_evaluacion' => date("d/m/Y", strtotime($evalTecnica->fecha_evaluacion)),
                'escrito' => $evalTecnica->escrito ? "SI":"NO",
                'teorico' => $evalTecnica->teorico ? "SI":"NO",
                'practico' => $evalTecnica->practico ? "SI":"NO",
                'conclusion' => $evalTecnica->conclusion,
            ];
            switch($evalTecnica->conclusion_rango) {
                case 2:
                    $eval_tecnicaData[$key]['conclusion_tipo'] = "MEDIO";
                    break;
                case 3:
                    $eval_tecnicaData[$key]['conclusion_tipo'] = "ALTO";
                    break;
                default:
                    $eval_tecnicaData[$key]['conclusion_tipo'] = "BAJO";
                    break;
            }
        }

        $psicotecnicosData = [];
        foreach($agente->psicotecnicos as $key => $psicotecnico) {
            $psicotecnicosData[$key] = [
                'idpsicotecnico' => $psicotecnico->idevaluacion_psicotecnica,
                'tipo_entrevista' => $psicotecnico->tipo_entrevista,
                'desempeno' => $psicotecnico->desempeno,
                'aspectos_cognitivos' => $psicotecnico->aspectos_cognitivos,
                'aspectos_psicoafectivos' => $psicotecnico->aspectos_psicoafectivos,
                'motivacion' => $psicotecnico->motivacion,
                'condicionantes' => $psicotecnico->condicionantes ? "SI":"NO",
                'tipo_condicionantes' => $psicotecnico->tipo_condicionantes,
                'experiencia_laboral' => $psicotecnico->experiencia_laboral ? "SI":"NO",
                'sector_publico' => $psicotecnico->sector_publico,
                'reune_competencias' => $psicotecnico->reune_competencias ? "SI":"NO",
                'observaciones' => $psicotecnico->observaciones,
                'puesto_recomendado' => $psicotecnico->puestoRecomendado->tipofuncion,
                'atencion_usuario' => $psicotecnico->atencion_usuario,
                'trabajo_en_equipo' => $psicotecnico->trabajo_en_equipo,
                'adaptabilidad' => $psicotecnico->adaptabilidad,
                'tolerancia_presion' => $psicotecnico->tolerancia_presion,
                'organizacion' => $psicotecnico->organizacion,
                'fecha_creacion' => date("d/m/Y",strtotime($psicotecnico->fecha_evaluacion)),
                'psicoevaluador_firma' => $psicotecnico->psicoevaluador->firma,
                'psicoevaluador_matricula' => $psicotecnico->psicoevaluador->matricula,
                ''
            ];
         }
        $data['psicotecnicos'] = $psicotecnicosData;
        $data['ev_tecnicas'] = $eval_tecnicaData;

        $puestosData = [];
        if(isset($puestos)){
            foreach($puestos as $key => $puesto) {
                $puestosData[$key] = [
                    'fdesde' => date("d/m/Y", strtotime($puesto->fdesde)),
                    'fhasta' => date("d/m/Y", strtotime($puesto->hasta)),
                    'dependencia' => $puesto->dependencia->dependencia,
                    'planta' => $puesto->tipoPlanta->tipoplanta,
                    'funcion' => $puesto->tipoFuncion->tipofuncion,
                    'nivel' => $puesto->tipoNivel->tiponivel,
                ];
            }
            $data = [
                'agente' => $agenteData,
                'puestos' => $puestosData,
                'psicotecnicos' => $psicotecnicosData,
                'ev_tecnicas' => $eval_tecnicaData,
                'success' => true,
                'message' => $message,
            ];
        }

        return $data;
    }
}
