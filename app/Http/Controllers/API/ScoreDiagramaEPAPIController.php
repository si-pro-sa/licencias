<?php

namespace App\Http\Controllers\API;

use App\Models\Dependencia;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Periodo;
use App\Models\Puesto;
use App\Models\Agente;
use App\Models\DependenciaRelacion;
use App\Models\HorarioPuesto;
use App\Models\PermissionUser;
use App\Permission;
use App\Models\ScoreDiagrama;
use App\Models\ScoreParamSubnivel;
use App\Models\PuestoAdicional;
use Illuminate\Support\Facades\DB;

class ScoreDiagramaEPAPIController extends AppBaseController
{
    public function efectores(){
        $Efectores = Dependencia::select('iddependencia','dependencia', 'codigorrhh')->ObtDependenciasSegunUsuario([6, 7]);
        $EfectoresServicios = [];
        foreach($Efectores as $efector){
            $idservicios = $efector->getIdsDescendencia();
            $servicios = Dependencia::select('iddependencia', 'dependencia', 'codigorrhh')->where('idtipo_dependencia', 4)->whereIn('iddependencia', $idservicios)->get();
            $EfectorServicio = [
                'iddependencia' => $efector->iddependencia,
                'codigo_nombre' => $efector->codigo_nombre,
                'servicios' => $servicios
            ];
            $EfectoresServicios[] = $EfectorServicio; 
        }
        return $EfectoresServicios;
    }

    public function periodos(){
        $Periodos = Periodo::select('idperiodo', 'periodo', 'idtipo_mes','fdesde', 'fhasta')->orderBy('idperiodo', 'desc')->get()->toArray();
        return $Periodos;
    }

    public function buscarAgentes($iddependencia, $fdesde, $fhasta){
        $Dependencia = Dependencia::select('iddependencia','dependencia', 'codigorrhh')->where('iddependencia', $iddependencia)->first();

        $iddependenciashijas = $Dependencia->getIdsDescendencia();

        $servicios = Dependencia::where('idtipo_dependencia', 4)->whereIn('iddependencia', $iddependenciashijas)->get(['iddependencia']);
        
        $puestosDB = Puesto::whereIn('iddependencia', $iddependenciashijas)->whereNull('fhasta')->get();
        $agentesConEP = [];
        $agentesSinEP = [];

        foreach ($puestosDB as $puesto){

            //----------- Diagramas -----------------

            $diagramas = $puesto->getDiagramasPorPerdiodo($fdesde, $fhasta);

            if(sizeof($diagramas)!=0){

                $tieneConfirmadaEfectiva = false;
    
                foreach ($diagramas as $diagrama){
                    if ($diagrama->user_id_ep != null){
                        $tieneConfirmadaEfectiva = true;
                    }
                }
                if ($tieneConfirmadaEfectiva){
                    $diagramasEP = $this->calcularDiagramasEP($diagramas);
                }else{
                    $diagramasEP = 1;
                }
    
                //----------- Puesto Principal -----------------
    
                $horarioPuestoPrincipal = $this->cantidadHorasPuesto($puesto->idpuesto);
    
                $puestoPrincipal = [
                    'idpuesto' => $puesto->idpuesto,
                    'iddependencia' => $puesto->iddependencia,
                    'servicio' => $puesto->dependencia->dependencia,
                    'padre' => $puesto->dependencia->area_operativa,
                    'horario' => $horarioPuestoPrincipal,
                ];
    
                //----------- Puesto Adicional -----------------
    
                $puestosAdicionalesDB = PuestoAdicional::select('idpuesto_adicional','iddependencia')->where('idpuesto', $puesto->idpuesto)->get();
    
                $puestosAdicionales = [];
    
                foreach($puestosAdicionalesDB as $puestoAdicional){
                    
                    $horarioPuestoAdicional = $this->cantidadHorasPuesto($puestoAdicional->idpuesto_adicional);
                    $dependencia = Dependencia::select('iddependencia','dependencia',)->where('iddependencia', $puestoAdicional->iddependencia)->first();
    
                    $PuestoAdicional = [
                        'idpuestoadicional' => $puestoAdicional->idpuesto_adicional,
                        'iddependencia' => $puestoAdicional->iddependencia,
                        'servicio' => $dependencia->dependencia,
                        'padre' => $dependencia->getPadre(),
                        'horario' => $horarioPuestoAdicional
                    ];
    
                    $puestosAdicionales[] = $PuestoAdicional;
                }
    
                //----------- Respuesta --------------- 
    
                $agente = [
                    'DNI' => $puesto->agente->documento,
                    'apellido_nombre' => $puesto->agente->apellido_nombre,
                    'Funcion' => $puesto->tipoFuncion->tipofuncion,
                    'Nivel' => $puesto->tipoNivel->tiponivel,
    
                    'puesto' => $puestoPrincipal,
                    'puestosAdicionales' => $puestosAdicionales,
    
                    'diagramas' => $diagramas,
                    'diagramasEP' => $diagramasEP
                ];
    
                if ($tieneConfirmadaEfectiva){
                    $agentesConEP[]=$agente;
                }else{
                    $agentesSinEP[]=$agente;
                }
            
            }

        }
        return [
            'Agentes_Con_EP' =>$agentesConEP,
            'Agentes_Sin_EP' =>$agentesSinEP
        ];
    }

    public function calcularDiagramasEP($diagramas){
        $cantidadDeFalse = 0;
        foreach ($diagramas as $diagrama){
            if($diagrama->efectivaPrestacion == false){
                $cantidadDeFalse = $cantidadDeFalse + 1;
            }
        }

        if ($cantidadDeFalse == sizeof($diagramas)){
            return 0;
        }elseif($cantidadDeFalse == 0){
            return 1;
        }elseif($cantidadDeFalse < sizeof($diagramas) && $cantidadDeFalse != 0){
            return 2;
        }
    }

    public function cantidadHorasPuesto($idpuesto){
        $contador = 0;
        $horarios = HorarioPuesto::select('cantidad_mensual', 'cantidad_horas')->where('puesto_id', $idpuesto)->get();
        foreach ($horarios as $horario){
            $contador = $contador + ($horario->cantidad_mensual * $horario->cantidad_horas);
        }
        return $contador;
    }
    
    public function obtDatosUsuario(){
        $user = auth()->user();

        $permisos = Permission::select('id', 'name')->
                where('name', 'ScoreDiagramaEP-store')->
                orWhere('name', 'ScoreDiagramaEP-fueradetermino')->
                get();

        /* $permisos_usuario = PermissionUser::select('permission_id')->where('user_id', $user->idusuario)->get();  */

        $permisosUsuario = DB::table('sistema.usuario')
                        ->join('sistema.permission_user', 'sistema.permission_user.user_id', '=', 'sistema.usuario.idusuario')
                        ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_user.permission_id')
                        ->select('sistema.permissions.id', 'sistema.permissions.name')
                        ->where('sistema.usuario.idusuario', $user->idusuario)
                        ->where(function ($query){
                            $query->where('sistema.permissions.name', 'ScoreDiagramaEP-store')
                                    ->orWhere('sistema.permissions.name', 'ScoreDiagramaEP-fueradetermino');
                        });

        $permisosRol = DB::table('sistema.usuario')
                    ->join('sistema.role_user', 'sistema.role_user.user_id', '=', 'sistema.usuario.idusuario')
                    ->join('sistema.permission_role', 'sistema.permission_role.role_id', '=', 'sistema.role_user.role_id')
                    ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_role.permission_id') 
                    ->select('sistema.permissions.id', 'sistema.permissions.name')
                    ->where('sistema.usuario.idusuario', $user->idusuario)
                    ->Where(function ($query){
                        $query->where('sistema.permissions.name', 'ScoreDiagramaEP-store')
                                ->orWhere('sistema.permissions.name', 'ScoreDiagramaEP-fueradetermino');
                    })
                    ->union($permisosUsuario)
                    ->orderBy('id', 'asc')
                    ->get();

        foreach ($permisos as $permiso){
            foreach ($permisosRol as $permisoUser){
                if($permisoUser->id == $permiso->id){
                    $permiso->valido = true;
                    break;
                }else{
                    $permiso->valido = false;
                }
            }
        }
        
        return $permisos;
    }

    public function confirmarDiagramas(Request $request){
        $diagramas = $request->get('diagramas');
        $diagramaActualizado = [];
        $user = auth()->user()->idusuario;
        $fecha = date("Y-m-d");
        
        DB::beginTransaction();

        foreach($diagramas as $diagrama){
            try{

                $diagramaDB = ScoreDiagrama::find($diagrama['idscorediagrama']);

                $diagramaDB->efectivaPrestacion = $diagrama['efectivaPrestacion'];
                $diagramaDB->user_id_ep = $user;
                $diagramaDB->carga_ep_at = $fecha;
                
                $diagramaDB->save();

                $diagramaActualizado[] = $diagramaDB;

                $diagrama = $diagramaDB;

            }catch (\Exception $exception){
                DB::rollBack();
                return [
                    'success' => false,
                    'mensaje' => 'Error al registrar la efectiva Prestacion',
                    'exception' => $exception
                ];
            }
        }
        DB::commit();
        return [
            'success' => true,
            'mensaje' => 'se cargo el diagrama correctamente',
            'diagramasActualizados' => $diagramaActualizado
        ];
    }

    public function volverEnviado(Request $request){
        $diagramas = $request->get('diagramas');
        return [
            'mensaje' => 'todo correcto',
            'diagramasActualizados' => $diagramas
        ];
    }

}
