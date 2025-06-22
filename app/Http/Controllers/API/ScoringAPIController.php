<?php

namespace App\Http\Controllers\API;

use Response;
use Illuminate\Http\Request;
use App\Models\Agente;
use App\Models\Puesto;
use App\Models\PuestoAdicional;
use App\Models\TipoDia;
use App\Models\TipoNivel;
use App\Models\TipoPlanta;
use App\Models\TipoFuncion;
use App\Models\Dependencia;
use App\Models\Periodo;
use App\Http\Controllers\AppBaseController;
use App\Models\HorarioPuesto;
use App\Models\ScoreDiagrama;
use App\Models\CargoCambioEstado;
use App\Models\TipoActividad;
use App\Permission;
use App\Models\PermissionUser;
use PHPUnit\Runner\Hook;
use App\Http\Requests\ScoreDiagramaRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Scalar;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Illuminate\Http\Exceptions;
use PhpParser\Node\Stmt\TryCatch;

class ScoringAPIController extends AppBaseController
{

    public function getAgente(int $documento)
    {
        $agente = Agente::documento($documento)->first();

        if (isset($agente)) {
            $puesto = $agente->puestoActivo();

            if (isset($puesto)) {
                //----------Puesto principal----------------

                $horarioPuesto = $this->getHorarios($puesto->idpuesto);

                $planta = TipoPlanta::where('idtipo_planta', $puesto->idtipo_planta)->first();

                $nivel = TipoNivel::where('idtipo_nivel', $puesto->idtipo_nivel)->first();

                $funcion = TipoFuncion::where('idtipo_funcion', $puesto->idtipo_funcion)->first();

                $puesto_Principal = [
                    'puesto' => $puesto,
                    'nivel' => $nivel->tiponivel,
                    'planta' => $planta->tipoplanta,
                    'funcion' => $funcion->tipofuncion,
                    'horarios_puesto' => $horarioPuesto[0]
                ];

                //----------Puestos adicionales-------------

                $puestosadicionales = PuestoAdicional::where('idpuesto', $puesto->idpuesto)->get(); //Busco los puestos adicionales con el id del puesto

                $puestos_Adicionales = [];

                foreach ($puestosadicionales as $puestoadicional) {


                    $horariosPuestoAdicional = $this->getHorarios($puestoadicional->idpuesto_adicional);

                    $dependencia = Dependencia::where('iddependencia', $puestoadicional->iddependencia)->first();

                    $PuestoAdicional_Horarios = [
                        'puesto_adicional' => $puestoadicional,
                        'dependencia' => $dependencia->dependencia,
                        'horarios_puesto_adicional' => $horariosPuestoAdicional[0],
                    ];

                    $puestos_Adicionales[] = $PuestoAdicional_Horarios;
                }

                //------------Respuesta-------------

                $respuesta = [
                    'agente' => $agente,
                    'Puesto_Principal' => $puesto_Principal,
                    'Puestos_Adicionales' => $puestos_Adicionales
                ];

                return $this->sendResponse($respuesta, 'Agente encontrado');
            } else {
                return $this->sendError('Agente sin puestos');
            }
        } else {
            return $this->sendError('no se encontró al agente');
        }
    }

    public function getHorarios(int $idpuesto)
    {

        $horariosPuestos = HorarioPuesto::where('puesto_id', $idpuesto)->get();
        $horariosDias = [];
        foreach ($horariosPuestos as $horarioPuesto) {

            $tipoDia = TipoDia::where('idtipo_dia', $horarioPuesto->idtipo_dia)->first();


            $horariosytipoDia = [
                'idtipo_dia' => $horarioPuesto->idtipo_dia,
                'Tipo_dia' => $tipoDia->tipodia,
                'cantidad_horas' => $horarioPuesto->cantidad_horas,
                'cantidad_mensual' => $horarioPuesto->cantidad_mensual,
                'hora_desde' => $horarioPuesto->hora_desde,
                'hora_hasta' => $horarioPuesto->hora_hasta,
            ];

            $horariosDias[] = $horariosytipoDia;
        }
        $horarios = [
            $horariosDias
        ];
        return $horarios;
    }

    public function getDiagramas(Request $request)
    {
        $periodo = $request->periodo;
        $puesto = $request->puesto;
        $puestosadicionales = $request->puestoAdicional;

        $diagramas = $this->getDiagramasPorPerdiodo($puesto, $puestosadicionales, $periodo);

        return $diagramas;
    }

    public function getPeriodos()
    {

        $periodosDB = Periodo::select('periodo', 'idtipo_mes', 'fdesde', 'fhasta')->orderBy('idperiodo', 'desc')->get();

        $periodos2 = DB::table('public.periodo')
            ->join('public.guardia_cronograma', 'public.guardia_cronograma.idperiodo', '=', 'public.periodo.idperiodo')
            ->select(
                'public.periodo.periodo',
                'public.periodo.idtipo_mes',
                'public.periodo.fdesde',
                'public.periodo.fhasta',
                'public.guardia_cronograma.periodo_1',
                'public.guardia_cronograma.periodo_2',
                'public.guardia_cronograma.periodo_3',
            )->orderBy('public.periodo.idperiodo', 'desc')->get();

        $periodos = [];
        foreach ($periodos2 as $periodo) {
            $fdesde = date($periodo->fdesde);
            $fhasta = date($periodo->fhasta);
            $NuevoPeriodo = [
                'periodo' => $periodo->periodo,
                'idtipo_mes' => $periodo->idtipo_mes,
                'fdesde' => date("Y-m-d", strtotime($fdesde)),
                'fhasta' => date("Y-m-d", strtotime($fhasta)),
                'periodo_1' => $periodo->periodo_1,
                'periodo_2' => $periodo->periodo_2,
                'periodo_3' => $periodo->periodo_3,
            ];
            $periodos[] = $NuevoPeriodo;
        }
        return ['periodos' => $periodos];
    }

    public function getUsuario()
    {
        $user = auth()->user();

        $idpermisoPeriodo = Permission::select('id')->where('name', 'ScoreDiagramaController-fueradetermino')->first();

        $permisos_usuario = DB::table('sistema.permission_user')
            ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_user.permission_id')
            ->select('sistema.permissions.id')
            ->where('sistema.permission_user.user_id', '=', $user->idusuario)
            ->where('sistema.permissions.name', 'ScoreDiagramaController-fueradetermino');


        $permisos_usuario_rol = DB::table('sistema.role_user')
            ->join('sistema.permission_role', 'sistema.permission_role.role_id', '=', 'sistema.role_user.role_id')
            ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_role.permission_id')
            ->select('sistema.permissions.id')
            ->union($permisos_usuario)
            ->where('sistema.role_user.user_id', '=', $user->idusuario)
            ->where('sistema.permissions.name', 'ScoreDiagramaController-fueradetermino')
            ->groupBy('id')
            ->orderBy('id', 'asc')
            ->get();


        $permisoPeriodo = false;
        foreach ($permisos_usuario_rol as $permiso) {
            if ($permiso->id === $idpermisoPeriodo->id) {
                $permisoPeriodo = true;
            }
        }

        return [
            'fueraDeTermino' => $permisoPeriodo,
            'permisos rol' => $permisos_usuario_rol,
        ];
    }

    public function store(Request $request)
    {
        $respuesta = $request->all();

        $dias = $respuesta['data'];
        $puesto = $respuesta['puesto'];
        $PuestosAdicionales = $respuesta['PuestoAdicional'];
        $cantidadHorasTotales = $respuesta['cantidadHorasTotales'];
        $periodo = $respuesta['periodo'];

        $nuevosDiagramas = [];
        DB::beginTransaction();

        foreach ($dias as $dia) {

            if ($dia['idscorediagrama'] == 0) {
                $nuevosDiagramas[] = $dia;
                try {

                    $score_diagrama = new ScoreDiagrama();
                    $user = auth()->user()->idusuario;

                    $score_diagrama->idpuesto = $dia['idpuesto'];
                    $score_diagrama->idpuestoadicional = $dia['idpuestoadicional'];
                    $score_diagrama->iddependencia = $dia['iddependencia'];
                    $score_diagrama->fecha = $dia['fecha'];
                    $score_diagrama->hora_desde = $dia['hora_desde'];
                    $score_diagrama->cantHoras = $dia['cantHoras'];
                    $score_diagrama->licencias = $dia['licencias'];
                    $score_diagrama->efectivaPrestacion = null;
                    $score_diagrama->user_id_carga = $user;
                    $score_diagrama->carga_diagrama_at = date("Y-m-d");

                    $score_diagrama->idtipo_dia = (isset($dia['idtipo_dia']) ? $dia['idtipo_dia'] : null);
                    $score_diagrama->idtipo_actividad =(isset($dia['idtipo_actividad']) ? $dia['idtipo_actividad'] : null);
                    $score_diagrama->cantidad_pacientes = $dia['cantidad_pacientes'];

                    $score_diagrama->save();
                } catch (\Exception $exception) {
                    DB::rollBack();
                    return [
                        'success' => false,
                        'message' => 'Error al cargar los datos en la base de datos',
                        'excepcion' => $exception->getMessage()
                    ];
                }
            }
        }

        $diagramas = $this->getDiagramasPorPerdiodo($puesto, $PuestosAdicionales, $periodo);
        $validacion = $this->ValidarHorarios($diagramas, $cantidadHorasTotales);


        if ($validacion) {
            DB::commit();

            return [
                'success' => true,
                'message' => 'Datos cargados correctamente',
                'diagramas' => $diagramas,
                'diagramas_cargados' => $dias
            ];
        } else {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'no cumple horas permitidas',
                'validacion' => $validacion,
                'diagramas' => $diagramas
            ];
        }
    }

    public function ValidarHorarios($diagramas, $cantidadHorasTotales)
    {
        $contador = 0;
        foreach ($diagramas as $diagrama) {
            $contador = $contador + $diagrama['cantHoras'];
        }

        return ($contador == $cantidadHorasTotales);
    }

    public function getDiagramasPorPerdiodo($puesto, $puestoAdicional, $periodo)
    {

        $diagramasDelPeriodo = [];
        $fechadesde = strtotime($periodo['fdesde']);
        $fechahasta = strtotime($periodo['fhasta']);

        $diagramas = ScoreDiagrama::where('idpuesto', $puesto['idpuesto'])->orderBy('fecha')->get();

        foreach ($diagramas as $diagrama) {
            $fecha = strtotime($diagrama->fecha);
            if ($fechadesde <= $fecha && $fecha <= $fechahasta) {
                $diagramasDelPeriodo[] = $diagrama;
            }
        }

        return $diagramasDelPeriodo;
    }

    public function eliminar(Request $request)
    {
        $dias = $request['data'];
        $puesto = $request['puesto'];
        $PuestosAdicionales = $request['PuestoAdicional'];
        $periodo = $request['periodo'];

        foreach ($dias as $dia) {
            try {
                $diagrama = ScoreDiagrama::find($dia['idscorediagrama']);
                $diagrama->delete();
            } catch (\Exception $exception) {
                return [
                    'success' => false,
                    'message' => 'Error al eliminar datos en la base de datos',
                    'excepcion' => $exception
                ];
            }
        }

        $diagramas = $this->getDiagramasPorPerdiodo($puesto, $PuestosAdicionales, $periodo);

        return [
            'success' => true,
            'message' => 'Datos eliminados correctamente',
            'diagramas' => $diagramas
        ];
    }

    public function getTiposActividad(){
        return TipoActividad::select()->get();
    }
}
