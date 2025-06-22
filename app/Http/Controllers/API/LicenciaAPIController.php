<?php

namespace App\Http\Controllers\API;

use App\Exports\LicenciasExport;
use App\Http\Requests\API\CreateLicenciaAPIRequest;
use App\Http\Requests\API\UpdateLicenciaAPIRequest;
use App\Models\Agente;
use App\Models\Antiguedad;
use App\Models\Dependencia;
use App\Models\Licencia;
use App\Models\LicenciaFamiliar;
use App\Models\LicenciaSaldos;
use App\Models\Puesto;
use App\Models\TipoLicencia;
use App\Models\CapacitacionAgente;
use App\Repositories\LicenciaRepository;
use App\Repositories\LicenciaFamiliarRepository;
use App\Repositories\LicenciaSaldosRepository;
use App\Repositories\AntiguedadRepository;
use App\User;
use App\Team;
use Auth;
use Cassandra\Date;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Carbon\Carbon;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


/**
 * Class LicenciaController
 * @package App\Http\Controllers\API
 */
class LicenciaAPIController extends AppBaseController
{
    /** @var  LicenciaRepository */
    private $licenciaRepository;
    /** @var  LicenciaFamiliarRepository */
    private $licenciaFamiliarRepository;
    /** @var  LicenciaSaldosRepository */
    private $licenciaSaldosRepository;
    /** @var  AntiguedadRepository */
    private $antiguedadRepository;

    public function __construct(LicenciaRepository $licenciaRepo, LicenciaFamiliarRepository $licenciaFamiliarRepo, LicenciaSaldosRepository $licenciaSaldosRepo, AntiguedadRepository $antiguedadRepository)
    {
        $this->licenciaRepository = $licenciaRepo;
        $this->licenciaFamiliarRepository = $licenciaFamiliarRepo;
        $this->licenciaSaldosRepository = $licenciaSaldosRepo;
        $this->antiguedadRepository = $antiguedadRepository;
    }

    /**
     * Display a listing of the Licencia.
     * GET|HEAD /licencias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->licenciaRepository->pushCriteria(new RequestCriteria($request));
        $this->licenciaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $licencias = $this->licenciaRepository->all();

        return $this->sendResponse($licencias->toArray(), 'Licencias retrieved successfully');
    }

    /**
     * Store a newly created Licencia in storage.
     * POST /licencias
     *
     * @param CreateLicenciaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenciaAPIRequest $request)
    {
        $input = $request->all();

        $licencia = $this->licenciaRepository->create($input);

        return $this->sendResponse($licencia->toArray(), 'Licencia saved successfully');
    }

    /**
     * Display the specified Licencia.
     * GET|HEAD /licencias/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {

        /** @var Licencia $licencia */
        $licenciaFamiliar = DB::table('licencia_familiares')->where('licencia_familiares.idlicencia', '=', $id)->first();

        if (empty($licenciaFamiliar)) {
            $licencias = $this->licenciaRepository->findWithoutFail($id);
        } else {
            $licencias = DB::table('licencias')->join('licencia_familiares', function ($join) {
                $join->on('licencia_familiares.idlicencia', '=', 'licencias.idlicencia');
            })
                ->join('personas', function ($join) {
                    $join->on('personas.idpersona', '=', 'licencia_familiares.idpersona');
                })
                ->where('licencias.idlicencia', '=', $id)->first();
        }
        if (empty($licencias)) {
            return $this->sendError('Licencia no encontrada');
        }

        return $this->sendResponse($licencias, 'Licencia retrieved successfully');
    }

    /**
     * Update the specified Licencia in storage.
     * PUT/PATCH /licencias/{id}
     *
     * @param int $id
     * @param UpdateLicenciaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenciaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Licencia $licencia */
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        if (empty($licencia)) {
            return $this->sendError('Licencia no encontrada');
        }

        $licencia = $this->licenciaRepository->update($input, $id);

        return $this->sendResponse($licencia->toArray(), 'Licencia updated successfully');
    }

    /**
     * Remove the specified Licencia from storage.
     * DELETE /licencias/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Licencia $licencia */
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        $tipoLicenciaCodigo = $licencia->tipoLicencia->codigo;


        if ($tipoLicenciaCodigo == 11) {
            $licenciaFamiliar = LicenciaFamiliar::where('licencia_familiares.idlicencia', '=', $id)->delete();
        }

        if (empty($licencia)) {
            return $this->sendError('Licencia no encontrada');
        }
        $saldos = LicenciaSaldos::where('licencia_saldos.idlicencia', '=', $id)->delete();
        $licencia->update([
            'idagenteResponsable' => Auth::user()->idagente
        ]);
        $licencia->delete();
        if (
            !empty($antiguedades) &&
            ($tipoLicenciaCodigo == 16 ||
                $tipoLicenciaCodigo == 17 ||
                $tipoLicenciaCodigo == 25 ||
                $tipoLicenciaCodigo == 27)
        ) {
            foreach ($antiguedades as $antiguedad) {
                $antiguedadModificar = $this->antiguedadRepository->findWithoutFail($antiguedad["idantiguedad"]);
                $antiguedadModificar->update([
                    'pedido' => $antiguedad["pedido"],
                ]);
            }
        }
        if ($licencia->idtipoLicencia === 18 || $licencia->idtipoLicencia === 19) {
            $fila = CapacitacionAgente::where('idAgente', '=', $licencia->idagente)->where('idCapacitacion', '=', $licencia->idCapacitacion)->first();
            $fila->delete();
        }
        return $this->sendResponse($id, 'Licencia deleted successfully');
    }

    /**
     * Obtiene la guardia del agente.
     * GET licencias/dias/{idagente}
     *
     * @param int $idagente
     *
     * @return Response
     */
    public function getDiasPosiblesLicenciasAgentes($idagente)
    {
        $Dias = Agente::join('puesto', function ($join) {
            $join->on('agente.idagente', '=', 'puesto.idagente')
                ->where('puesto.fhasta', '=', null);
        })
            ->join('horario_puesto_historico', function ($join) {
                $join->on('puesto.idpuesto', '=', 'horario_puesto_historico.idpuesto');
            })
            ->where('agente.idagente', '=', $idagente)
            ->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
            ->get();

        $dias_guardias = [];
        if ($Dias->isEmpty()) {
            $Dias = Agente::join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })
                ->join('horario_puesto', function ($join) {
                    $join->on('puesto.idpuesto', '=', 'horario_puesto.puesto_id');
                })
                ->where('agente.idagente', '=', $idagente)
                ->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
                ->get();
        }
        $contador = 0;
        foreach ($Dias as $Dia) {
            $dia_guardia = 0;
            if (isset($Dia->idtipo_horario)) {
                switch ($Dia->dias_guardia) {
                    case 'LUNES':
                        $dia_guardia = 1;
                        break;
                    case 'MARTES':
                        $dia_guardia = 2;
                        break;
                    case 'MIERCOLES':
                        $dia_guardia = 3;
                        break;
                    case 'JUEVES':
                        $dia_guardia = 4;
                        break;
                    case 'VIERNES':
                        $dia_guardia = 5;
                        break;
                    case 'SABADO':
                        $dia_guardia = 6;
                        break;
                    case 'DOMINGO':
                        $dia_guardia = 7;
                        break;
                    case 'ROTATIVA':
                        $dia_guardia = 8;
                        break;
                    default:
                        $dia_guardia = 9;
                        break;
                }
                $dias_guardias[$contador] = $dia_guardia;
                $contador++;
            }
            if (isset($Dia->idtipo_dia)) {
                switch ($Dia->idtipo_dia) {
                    case 6:
                    case 16:
                        $dia_guardia = 6;
                        break;
                    case 7:
                    case 17:
                        $dia_guardia = 7;
                        break;
                    case 9:
                    case 10:
                        $dia_guardia = 8;
                        break;
                    default:
                        $dia_guardia = 1;
                        break;
                }
                $dias_guardias[$contador] = $dia_guardia;
                $contador++;
            }
        }

        return $this->sendResponse($dias_guardias, 'Dias recibidos satisfactoriamente');
    }

    /**
     * @return Array de Dates from table feriados
     */
    public function getFeriados()
    {
        //TODO agrega que mande los textos de esos feriados para el popover
        $Feriados = DB::table('feriado')->where('deleted_at', '=', null)
            ->pluck('fecha');
        return $this->sendResponse($Feriados->toArray(), 'Feriados recibidos satisfactoriamente');
    }

    /**
     * Devuelve todas las licencias de un tipo de un agente
     * @param $idagente
     * @param $tipoLicencia
     * @return mixed
     */
    public function getLicenciasPorAgente($idagente, $tipoLicencia)
    {
        $Licencias = Licencia::where('licencias.idagente', '=', $idagente)
            ->join('tipo_licencias', 'licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia');
        if ($tipoLicencia != 0) {
            $Licencias = $Licencias->where('tipo_licencias.codigo', '=', $tipoLicencia);
        }
        $Licencias = $Licencias->get();

        return $this->sendResponse($Licencias->toArray(), 'Licencias retrieved successfully');
    }

    /**
     * Devuelve todas las licencias de un agente
     * @param $idagente
     * @return mixed
     */
    public function getLicenciasPorAgenteTodas($idagente)
    {
        $licencias = DB::table('licencias')
            ->where('licencias.idagente', '=', $idagente)
            ->join('tipo_licencias', 'licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
            ->where('licencias.deleted_at', '=', null)
            ->get();
        return $this->sendResponse($licencias->toArray(), 'Licencias retrieved successfully');
    }

    /**
     * @param $idagente este es el id del agente efectivamente valga la redundancia
     * @param $tipoLicencia este es el codigo del tipo licencia no confundir con id
     * @return Array de la tabla saldos de un cierto agente para un tipo de licencia
     */
    public function getSaldosPorAgente($idagente, $tipoLicencia)
    {
        if ($tipoLicencia == 0) {
            $saldos = DB::table('licencia_saldos')
                ->where('licencia_saldos.idagente', '=', $idagente)
                ->join('tipo_licencias', 'licencia_saldos.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                ->where('licencia_saldos.deleted_at', '=', null)
                ->get();
        } else {
            $saldos = DB::table('licencia_saldos')
                ->where('licencia_saldos.idagente', '=', $idagente)
                ->join('tipo_licencias', 'licencia_saldos.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                ->where('tipo_licencias.codigo', '=', $tipoLicencia)
                ->where('licencia_saldos.deleted_at', '=', null)
                ->get();
        }

        return $this->sendResponse($saldos->toArray(), 'saldos retrieved successfully');
    }

    /**
     * @param $idagente
     * @return Array los saldos completos del agente
     */
    public function getSaldosPorAgenteTodas($idagente)
    {
        $saldos = DB::table('licencia_saldos')
            ->where('licencia_saldos.idagente', '=', $idagente)
            ->join('tipo_licencias', 'licencia_saldos.idtipoLicencia', 'tipo_licencias.idtipoLicencia')
            ->where('licencia_saldos.deleted_at', '=', null)
            ->select(['licencia_saldos.*', 'tipo_licencias.descripcion as descripcion', 'tipo_licencias.codigo as codigo'])
            ->get();
        return $this->sendResponse($saldos->toArray(), 'saldos retrieved successfully');
    }

    /**
     * Este me devuelve todos los saldos que se le descuentan a antiguedad es la unica que tiene este comportamiento
     * @param $idagente
     * @return Array los saldos de las licencias habiles del agente
     */
    public function getSaldosHabilesPorAgente($idagente)
    {
        $IdsHabiles = TipoLicencia::whereIn('codigo', [16, 17, 25, 27])->pluck('idtipoLicencia')->toArray();
        $saldos = LicenciaSaldos::where('licencia_saldos.idagente', '=', $idagente)
            ->whereIn('idtipoLicencia', $IdsHabiles)
            ->where('licencia_saldos.deleted_at', '=', null)
            ->get();
        return $this->sendResponse($saldos->toArray(), 'saldos retrieved successfully');
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function storeComplete(Request $request)
    {
        $agenteResponsable = Auth::user()->idagente;
        $licencia = $request->get('0');
        $agente = $request->get('1');
        $saldos = $request->get('2');
        $antiguedades = $request->get('3');
        $fecha_pedido_inicial = new \DateTime($licencia["fecha_pedido_inicio"]);
        $fecha_pedido_final = new \DateTime($licencia["fecha_pedido_final"]);
        $dias = $licencia["dias"];
        $verificacion_fechas = LicenciaAPIController::fechasUtilizadas($fecha_pedido_inicial, $fecha_pedido_final, $agente["idagente"]);
        Log::info($licencia);
        Log::info($agente);
        Log::info($saldos);
        Log::info($antiguedades);
        Log::info($dias);
        $nowInGMTMinus3 = Carbon::now('America/Sao_Paulo');



        $tipoLicencia = TipoLicencia::where('codigo', '=', $licencia['idtipoLicencia'])
            ->where('deleted_at', '=', null)
            ->first();
        Log::info($tipoLicencia);
        if ($verificacion_fechas >= 1 && ($tipoLicencia->codigo != 35 && $tipoLicencia->codigo != 36)) {
            return $this->sendError("Error no paso la validacion de fecha superpuesta");
        }
        $puesto = Puesto::where('puesto.fhasta', '=', null)
            ->where('puesto.idagente', '=', $agente["idagente"])->first();

        $idefector = Dependencia::where('dependencia', '=', Dependencia::find($puesto['iddependencia'])->getPadre())
            ->pluck('iddependencia')
            ->first();

        if ($tipoLicencia->codigo == 25 && $antiguedades[0]['pedido'] == 0) {
            Antiguedad::create([
                'idagente' => $agente["idagente"],
                'año' => $antiguedades[0]['año'],
                'pedido' => $antiguedades[0]['pedido'],
                'disponible' => $antiguedades[0]['disponible'],
                'vigente' => $antiguedades[0]['vigente'],
                'idusuario' => 1812
            ]);
        }

        if (
            $tipoLicencia->codigo == 11 ||
            $tipoLicencia->codigo == 21 ||
            $tipoLicencia->codigo == 22 ||
            $tipoLicencia->codigo == 23 ||
            $tipoLicencia->codigo == 24 ||
            $tipoLicencia->codigo == 35 ||
            $tipoLicencia->codigo == 36
        ) {
            $licenciaInsertado = Licencia::create([
                'idefector' => $idefector,
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                'fecha_pedido_inicio' => $fecha_pedido_inicial,
                'fecha_pedido_final' => $fecha_pedido_final,
                'idagenteResponsable' => $agenteResponsable,
                'dias' => $dias
            ]);
            if (
                $tipoLicencia->codigo == 35 ||
                $tipoLicencia->codigo == 36
            ) {
                $licenciaInsertado->fecha_efectiva_inicio = $licenciaInsertado->fecha_pedido_inicio;
                $licenciaInsertado->fecha_efectiva_final = $licenciaInsertado->fecha_pedido_final;
                $licenciaInsertado->primer_visado = true;
                $licenciaInsertado->segundo_visado = true;
                $licenciaInsertado->fecha_visado_primero = $nowInGMTMinus3;
                $licenciaInsertado->fecha_visado_segundo = $nowInGMTMinus3;
                $licenciaInsertado->save();
            }
            if (!empty($licencia["idpersona"])) {
                $licenciaFamiliarInsertado = LicenciaFamiliar::create([
                    'idlicencia' => $licenciaInsertado->idlicencia,
                    'idpersona' => $licencia['idpersona']
                ]);
            }
        } elseif ($tipoLicencia->codigo == 18 || $tipoLicencia->codigo == 19) {
            $licenciaInsertado = Licencia::create([
                'idpuesto' => $agente["idpuesto"],
                'idefector' => $idefector,
                'idagente' => $agente["idagente"],
                'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                'fecha_pedido_inicio' => $fecha_pedido_inicial,
                'fecha_pedido_final' => $fecha_pedido_final,
                'idCapacitacion' => $licencia["idCapacitacion"],
                'caracter' => $licencia["caracter"],
                'idagenteResponsable' => $agenteResponsable,
                'dias' => $dias
            ]);
            CapacitacionAgente::create([
                'idAgente' => $agente['idagente'],
                'idCapacitacion' => $licencia["idCapacitacion"]
            ]);
        } elseif ($tipoLicencia->codigo == 14) {
            $licenciaInsertado = Licencia::create([
                'idefector' => $idefector,
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                'fecha_pedido_inicio' => $fecha_pedido_inicial,
                'fecha_pedido_final' => $fecha_pedido_final,
                'parentescoFallecido' => $licencia["parentescoFallecido"],
                'idagenteResponsable' => $agenteResponsable,
                'dias' => $dias
            ]);
        } elseif ($tipoLicencia->codigo == 33 || $tipoLicencia->codigo == 34 || $tipoLicencia->codigo == 37 || $tipoLicencia->codigo == 38 || $tipoLicencia->codigo == 39 || $tipoLicencia->codigo == 40) {
            $licenciaInsertado = Licencia::create([
                'idefector' => $idefector,
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                'fecha_pedido_inicio' => $fecha_pedido_inicial,
                'fecha_pedido_final' => $fecha_pedido_final,
                'fecha_efectiva_inicio' => $fecha_pedido_inicial,
                'fecha_efectiva_final' => $fecha_pedido_final,
                'idagenteResponsable' => $agenteResponsable,
                'primer_visado' => true,
                'segundo_visado' => true,
                'fecha_visado_primero' => $nowInGMTMinus3,
                'fecha_visado_segundo' => $nowInGMTMinus3,
                'dias' => $dias
            ]);
        } else {
            $licenciaInsertado = Licencia::create([
                'idefector' => $idefector,
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                'fecha_pedido_inicio' => $fecha_pedido_inicial,
                'idagenteResponsable' => $agenteResponsable,
                'fecha_pedido_final' => $fecha_pedido_final,
                'dias' => $dias
            ]);
        }
        if (
            $tipoLicencia->codigo == 9 ||
            $tipoLicencia->codigo == 10 ||
            $tipoLicencia->codigo == 12 ||
            $tipoLicencia->codigo == 13 ||
            $tipoLicencia->codigo == 14 ||
            $tipoLicencia->codigo == 16 ||
            $tipoLicencia->codigo == 17 ||
            $tipoLicencia->codigo == 25 ||
            $tipoLicencia->codigo == 26 ||
            $tipoLicencia->codigo == 27 ||
            $tipoLicencia->codigo == 28
        ) {
            $licenciaInsertado->fecha_efectiva_inicio = $licenciaInsertado->fecha_pedido_inicio;
            $licenciaInsertado->fecha_efectiva_final = $licenciaInsertado->fecha_pedido_final;
            $licenciaInsertado->primer_visado = true;
            $licenciaInsertado->segundo_visado = true;
            $licenciaInsertado->save();
        }
        if (!empty($saldos)) {
            foreach ($saldos as $saldo) {
                $saldoInsertado = LicenciaSaldos::create([
                    'idlicencia' => $licenciaInsertado["idlicencia"],
                    'idagente' => $agente["idagente"],
                    'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                    'año' => $saldo["año"],
                    'mes' => $saldo["mes"],
                    'saldoMensual' => $saldo["saldoMensual"],
                    'saldoAnual' => $saldo["saldoAnual"],
                    'dias' => $saldo["dias"]
                ]);
            }
        }


        return $this->sendResponse($licenciaInsertado->toArray(), 'Licencias guardada');
    }

    /**
     * Funcion para asegurar si es que ya hay registros en la bbdd con las fechas utilizadas
     * @param $fecha_pedido_inicial datetime
     * @param $fecha_pedido_final datetime
     * @param $agente_id int
     * @return int
     */
    public function fechasUtilizadas($fecha_pedido_inicial, $fecha_pedido_final, $agente_id)
    {
        //Verificar que no esten comparando con las fechas del tipo licencias 35 36
        $bandera = 0;
        $licencias = Licencia::where('idagente', '=', $agente_id)
            ->whereNull('primer_visado')
            ->whereBetween('fecha_pedido_inicio', [$fecha_pedido_inicial, $fecha_pedido_final])
            ->where('idtipoLicencia', '!=', 35)
            ->where('idtipoLicencia', '!=', 36)
            ->get();
        $bandera = ($licencias->isEmpty() ? 0 : 1);

        $licencias = Licencia::where('idagente', '=', $agente_id)
            ->whereNull('primer_visado')
            ->whereBetween('fecha_pedido_final', [$fecha_pedido_inicial, $fecha_pedido_final])
            ->where('idtipoLicencia', '!=', 35)
            ->where('idtipoLicencia', '!=', 36)
            ->get();
        $bandera = ($licencias->isEmpty() ? $bandera : 1);
        $licencias = Licencia::where('idagente', '=', $agente_id)
            ->where('segundo_visado', 'true')
            ->whereBetween('fecha_efectiva_inicio', [$fecha_pedido_inicial, $fecha_pedido_final])
            ->where('idtipoLicencia', '!=', 35)
            ->where('idtipoLicencia', '!=', 36)
            ->get();
        $bandera = ($licencias->isEmpty() ? $bandera : 1);
        $licencias = Licencia::where('idagente', '=', $agente_id)
            ->where('segundo_visado', 'true')
            ->whereNull('cuarta_visado')
            ->whereBetween('fecha_efectiva_final', [$fecha_pedido_inicial, $fecha_pedido_final])
            ->where('idtipoLicencia', '!=', 35)
            ->where('idtipoLicencia', '!=', 36)
            ->get();
        $bandera = ($licencias->isEmpty() ? $bandera : 1);
        $licencias = Licencia::where('idagente', '=', $agente_id)
            ->where('cuarta_visado', 'true')
            ->whereBetween('fecha_interrupcion_inicio', [$fecha_pedido_inicial, $fecha_pedido_final])
            ->where('idtipoLicencia', '!=', 35)
            ->where('idtipoLicencia', '!=', 36)
            ->get();
        $bandera = ($licencias->isEmpty() ? $bandera : 1);

        return $bandera;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function updateComplete(Request $request)
    {
        $licencia = $request->get('0');
        $agente = $request->get('1');
        $saldos = $request->get('2');
        $antiguedades = $request->get('3');


        $licenciaModificar = $this->licenciaRepository->findWithoutFail($licencia["idlicencia"]);

        // Si la licenciaModificar no tenia ni primer_visado ni segundo_visado, entonces pero $licencia si tiene ambos, entonces se le asigna la fecha actual a fecha_visado_primero y fecha_visado_segundo
        if (empty($licenciaModificar->primer_visado) && empty($licenciaModificar->segundo_visado) && !empty($licencia["primer_visado"]) && !empty($licencia["segundo_visado"])) {
            $licenciaModificar->update([
                'primer_visado' => $licencia["primer_visado"],
                'segundo_visado' => $licencia["segundo_visado"],
                'fecha_visado_primero' => Carbon::now('America/Sao_Paulo'),
                'fecha_visado_segundo' => Carbon::now('America/Sao_Paulo'),
                'id_agente_primer_visado' => Auth::user()->idagente,
                'id_agente_segundo_visado' => Auth::user()->idagente
            ]);
        } else {
            // Pero si la licenciaModificar no tenia primer_visado pero $licencia si tiene, entonces se le asigna la fecha actual a fecha_visado_primero
            if (empty($licenciaModificar->primer_visado) && !empty($licencia["primer_visado"])) {
                $licenciaModificar->update([
                    'primer_visado' => $licencia["primer_visado"],
                    'fecha_visado_primero' => Carbon::now('America/Sao_Paulo'),
                    'id_agente_primer_visado' => Auth::user()->idagente,
                ]);
            } else {

                // pero si la licenciaModificar no tenia segundo_visado pero $licencia si tiene, entonces se le asigna la fecha actual a fecha_visado_segundo
                if (empty($licenciaModificar->segundo_visado) && !empty($licencia["segundo_visado"])) {
                    $licenciaModificar->update([
                        'segundo_visado' => $licencia["segundo_visado"],
                        'fecha_visado_segundo' => Carbon::now('America/Sao_Paulo'),
                        'id_agente_segundo_visado' => Auth::user()->idagente
                    ]);
                } else {
                    // Si la licenciaModificar no tenia cuarta_visado pero $licencia si tiene, entonces se le asigna la fecha actual a fecha_visado_interrupcion
                    if (empty($licenciaModificar->cuarta_visado) && !empty($licencia["cuarta_visado"])) {
                        $licenciaModificar->update([
                            'cuarta_visado' => $licencia["cuarta_visado"],
                            'fecha_visado_interrupcion' => Carbon::now('America/Sao_Paulo')
                        ]);
                    }
                }
            }
        }

        $licenciaFamiliarModificar = LicenciaFamiliar::where('idlicencia', '=', $licencia["idlicencia"])->where('deleted_at', '=', null)
            ->first();
        $tipoLicencia = TipoLicencia::where('codigo', '=', $licencia['idtipoLicencia'])->first();

        if (empty($licencia["idlicencia"])) {
            return $this->sendError('Licencia no encontrada');
        }

        if (!empty($licencia["fecha_pedido_inicio"])) {
            $fecha_pedido_inicio = new \DateTime($licencia["fecha_pedido_inicio"]);
        } else {
            $fecha_pedido_inicio = null;
        }
        if (!empty($licencia["fecha_pedido_final"])) {
            $fecha_pedido_final = new \DateTime($licencia["fecha_pedido_final"]);
        } else {
            $fecha_pedido_final = null;
        }
        if (!empty($licencia["fecha_efectiva_inicio"])) {
            $fecha_efectiva_inicio = new \DateTime($licencia["fecha_efectiva_inicio"]);
        } else {
            $fecha_efectiva_inicio = null;
        }
        if (!empty($licencia["fecha_efectiva_final"])) {
            $fecha_efectiva_final = new \DateTime($licencia["fecha_efectiva_final"]);
        } else {
            $fecha_efectiva_final = null;
        }

        if (!empty($licencia["resolucion"])) {
            $resolucion = $licencia["resolucion"];
        } else {
            $resolucion = null;
        }

        if (!empty($licencia["fecha_interrupcion_inicio"])) {
            $fecha_interrupcion_inicio = new \DateTime($licencia["fecha_interrupcion_inicio"]);
        } else {
            $fecha_interrupcion_inicio = null;
        }

        $dias = $licencia["dias"];
        if ($tipoLicencia->codigo == 11) {
            $licenciaModificar->update([
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'fecha_pedido_inicio' => $fecha_pedido_inicio,
                'fecha_pedido_final' => $fecha_pedido_final,
                'fecha_efectiva_inicio' => $fecha_efectiva_inicio,
                'fecha_efectiva_final' => $fecha_efectiva_final,
                'fecha_interrupcion_inicio' => $fecha_interrupcion_inicio,
                'primer_visado' => $licencia["primer_visado"],
                'segundo_visado' => $licencia["segundo_visado"],
                'tercera_visado' => $licencia["tercera_visado"],
                'cuarta_visado' => $licencia["cuarta_visado"],
                'observacion_primera' => $licencia["observacion_primera"],
                'observacion_segunda' => $licencia["observacion_segunda"],
                'observacion_tercera' => $licencia["observacion_tercera"],
                'observacion_cuarta' => $licencia["observacion_cuarta"],
                'resolucion' => $licencia["resolucion"],
                'idagenteResponsable' => Auth::user()->idagente,
                'dias' => $dias
            ]);
            $licenciaFamiliarModificar->update([
                'idpersona' => $licencia['idpersona']
            ]);
        } elseif ($tipoLicencia->codigo == 18 || $tipoLicencia->codigo == 19) {
            $licenciaModificar->update([
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'fecha_pedido_inicio' => $fecha_pedido_inicio,
                'fecha_pedido_final' => $fecha_pedido_final,
                'fecha_efectiva_inicio' => $fecha_efectiva_inicio,
                'fecha_efectiva_final' => $fecha_efectiva_final,
                'fecha_interrupcion_inicio' => $fecha_interrupcion_inicio,
                'idCapacitacion' => $licencia["idCapacitacion"],
                'resolucion' => $resolucion,
                'caracter' => $licencia["caracter"],
                'primer_visado' => $licencia["primer_visado"],
                'segundo_visado' => $licencia["segundo_visado"],
                'tercera_visado' => $licencia["tercera_visado"],
                'cuarta_visado' => $licencia["cuarta_visado"],
                'observacion_primera' => $licencia["observacion_primera"],
                'observacion_segunda' => $licencia["observacion_segunda"],
                'observacion_tercera' => $licencia["observacion_tercera"],
                'observacion_cuarta' => $licencia["observacion_cuarta"],
                'idagenteResponsable' => Auth::user()->idagente,
                'dias' => $dias
            ]);
        } else {
            $licenciaModificar->update([
                'idpuesto' => $agente["idpuesto"],
                'idagente' => $agente["idagente"],
                'fecha_pedido_inicio' => $fecha_pedido_inicio,
                'fecha_pedido_final' => $fecha_pedido_final,
                'fecha_efectiva_inicio' => $fecha_efectiva_inicio,
                'fecha_efectiva_final' => $fecha_efectiva_final,
                'fecha_interrupcion_inicio' => $fecha_interrupcion_inicio,
                'primer_visado' => $licencia["primer_visado"],
                'segundo_visado' => $licencia["segundo_visado"],
                'tercera_visado' => $licencia["tercera_visado"],
                'cuarta_visado' => $licencia["cuarta_visado"],
                'observacion_primera' => $licencia["observacion_primera"],
                'observacion_segunda' => $licencia["observacion_segunda"],
                'observacion_tercera' => $licencia["observacion_tercera"],
                'observacion_cuarta' => $licencia["observacion_cuarta"],
                'idagenteResponsable' => Auth::user()->idagente,
                'resolucion' => $licencia["resolucion"],
                'dias' => $dias
            ]);
        }
        $saldosBorrar = LicenciaSaldos::where('licencia_saldos.idlicencia', '=', $licencia["idlicencia"])->delete();
        if (($licencia["primer_visado"] == true && $licencia["segundo_visado"] == true) || ($licencia["primer_visado"] == true && $licencia["segundo_visado"] == null)) {
            if (!empty($saldos) && $dias > 0) {
                foreach ($saldos as $saldo) {
                    $saldoInsertado = LicenciaSaldos::create([
                        'idlicencia' => $licenciaModificar["idlicencia"],
                        'idagente' => $agente["idagente"],
                        'idtipoLicencia' => $tipoLicencia->idtipoLicencia,
                        'año' => $saldo["año"],
                        'mes' => $saldo["mes"],
                        'saldoMensual' => $saldo["saldoMensual"],
                        'saldoAnual' => $saldo["saldoAnual"],
                        'dias' => $saldo["dias"]
                    ]);
                }
            }
        }


        return $this->sendResponse($licenciaModificar->toArray(), 'Licencia actualizada successfully');
    }

    /**
     * @param $idlicencia
     * @return mixed
     */
    public function desvisar($idlicencia)
    {

        $licenciaModificar = $this->licenciaRepository->findWithoutFail($idlicencia);

        if (empty($licenciaModificar)) {
            return $this->sendError('Licencia no encontrada');
        }

        $licenciaModificar->update([
            'fecha_efectiva_inicio' => null,
            'fecha_efectiva_final' => null,
            'fecha_interrupcion_inicio' => null,
            'fecha_interrupcion_final' => null,
            'fecha_visado_primero' => null,
            'fecha_visado_segundo' => null,
            'fecha_visado_interrupcion' => null,
            'primer_visado' => null,
            'segundo_visado' => null,
            'tercera_visado' => null,
            'cuarta_visado' => null,
            'observacion_primera' => null,
            'observacion_segunda' => null,
            'observacion_tercera' => null,
            'observacion_cuarta' => null,
            'idagenteResponsable' => Auth::user()->idagente,
            'dias' => 0
        ]);
        $saldos = LicenciaSaldos::where('licencia_saldos.idlicencia', '=', $idlicencia)->delete();
        if ($licenciaModificar->idtipoLicencia === 18 || $licenciaModificar->idtipoLicencia === 19) {
            $fila = CapacitacionAgente::where('idAgente', '=', $licenciaModificar->idagente)
                ->where('idCapacitacion', '=', $licenciaModificar->idCapacitacion)
                ->first();

            if ($fila) {
                $fila->delete();
            }
        }

        return $this->sendResponse($licenciaModificar->toArray(), 'Licencia desvisada successfully');
    }

    /**
     * Funcion para actualizar de manera generica las licencias
     *
     * @param array $licencias
     * @param string $campo
     * @param mixed $valor
     * @return int
     * @throws \Exception
     */
    public function visadoTodo($licencias, $campo, $valor)
    {
        $currentDate = Carbon::now()->toDateString();
        $licenciasLAO = array(16, 17, 25, 27);

        if (!empty($licencias)) {
            foreach ($licencias as $licencia) {
                $licenciaModificar = $this->licenciaRepository->findWithoutFail($licencia['idlicencia']);
                $fecha_pedido_inicio = new \DateTime($licenciaModificar["fecha_pedido_inicio"]);
                $fecha_pedido_final = new \DateTime($licenciaModificar["fecha_pedido_final"]);

                $fecha_efectiva_inicio = !empty($licenciaModificar["fecha_efectiva_inicio"]) ? new \DateTime($licenciaModificar["fecha_efectiva_inicio"]) : $fecha_pedido_inicio;
                $fecha_efectiva_final = !empty($licenciaModificar["fecha_efectiva_final"]) ? new \DateTime($licenciaModificar["fecha_efectiva_final"]) : $fecha_pedido_final;

                $updates = [
                    'fecha_efectiva_inicio' => $fecha_efectiva_inicio,
                    'fecha_efectiva_final' => $fecha_efectiva_final,
                    'idagenteResponsable' => Auth::user()->idagente,
                    $campo => $valor
                ];

                if ($valor == true) {
                    // Condición para actualizar solo el campo específico y su fecha
                    if ($campo == 'primer_visado') {
                        // No actualizar fecha_visado_primero si ya hay una fecha en fecha_visado_segundo
                        if (empty($licenciaModificar->fecha_visado_segundo)) {
                            $updates['fecha_visado_primero'] = $currentDate;
                        }
                    } elseif ($campo == 'segundo_visado') {
                        $updates['fecha_visado_segundo'] = $currentDate;

                        // Si no tiene fecha_visado_primero, establecer la misma fecha en ambos campos
                        if (empty($licenciaModificar->fecha_visado_primero)) {
                            $updates['fecha_visado_primero'] = $currentDate;
                        }
                    }

                    $licenciaModificar->update($updates);
                } else {
                    if ($campo == 'primer_visado') {
                        $licenciaModificar->update([
                            $campo => $valor,
                            'segundo_visado' => $valor,
                            'fecha_visado_primero' => $currentDate,
                            'fecha_visado_segundo' => $currentDate,
                            'idagenteResponsable' => Auth::user()->idagente
                        ]);
                    } elseif ($campo == 'segundo_visado') {
                        $licenciaModificar->update([
                            $campo => $valor,
                            'fecha_visado_segundo' => $currentDate,
                            'idagenteResponsable' => Auth::user()->idagente
                        ]);
                    }

                    LicenciaSaldos::where('licencia_saldos.idlicencia', '=', $licencia['idlicencia'])->delete();
                }

                if (in_array($licenciaModificar['idtipoLicencia'], $licenciasLAO)) {
                    $licenciaModificar->update([
                        'segundo_visado' => $valor,
                        'idagenteResponsable' => Auth::user()->idagente,
                        'fecha_visado_segundo' => $currentDate
                    ]);
                }
            }
        }

        return 0;
    }



    /**
     * Funcion para generar el primer visado masivo
     *
     * @param Request $request
     * @throws \Exception
     */
    public function primerVisadoTodo(Request $request)
    {
        $licencias = $request->input('licencias');
        $valor = $request->input('value');

        $this->visadoTodo($licencias, 'primer_visado', $valor);
        return $this->sendSuccess('Se realizo el primer visado correctamente');
    }


    /**
     *  Funcion para realizar el segundo visado
     *
     * @param Request $request
     * @throws \Exception
     */
    public function segundoVisadoTodo(Request $request)
    {
        $licencias = $request->input('licencias');
        $valor = $request->input('value');

        $this->visadoTodo($licencias, 'segundo_visado', $valor);
        return $this->sendSuccess('Se realizo el segundo visado correctamente');
    }

    /**
     * Get Licencias para la pantalla de visado masivo
     *  /api/licencias/dependiente/
     * @param Request $request
     * @return mixed
     */
    public function getLicenciasDependientes(Request $request)
    {

        $now = Carbon::now('-3');
        $last = Carbon::now('-3')->subYear(2);
        $to = Carbon::now('-3')->addYear(2);
        $user = $request->user();
        $idagente = $request->query('idagente');
        $efector = $request->query('efector');
        $codigo_nombre = $request->query('codigo_nombre');
        $documento = $request->query('documento');
        $apellido_nombre = $request->query('apellido_nombre');
        $tipoLicencia = $request->query('tipoLicencia');
        $dias = $request->query('dias');
        $idLicencia = $request->query('idlicencia');
        $dependencia = $request->query('dependencia');
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        if (!empty($request->query('fecha_pedido_inicio'))) {
            $fecha_pedido_inicio = new \DateTime($request->query('fecha_pedido_inicio'));
        }

        if (!empty($request->query('fecha_pedido_final'))) {
            $fecha_pedido_final = new \DateTime($request->query('fecha_pedido_final'));
        }
        if (!empty($request->query('fecha_efectiva_inicio'))) {
            $fecha_efectiva_inicio = new \DateTime($request->query('fecha_efectiva_inicio'));
        }
        if (!empty($request->query('fecha_efectiva_final'))) {
            $fecha_efectiva_final = new \DateTime($request->query('fecha_efectiva_final'));
        }

        $primer_visado = $request->query('primer_visado');

        $segundo_visado = $request->query('segundo_visado');

        $cuarta_visado = $request->query('cuarta_visado');
        if ($primer_visado >= 0) {
            $primer_visado = $primer_visado == '0' ? true : ($primer_visado == '1' ? false : ($primer_visado == '3' ? 'all' : 'null'));
        }
        if ($segundo_visado >= 0) {
            $segundo_visado = $segundo_visado == '0' ? true : ($segundo_visado == '1' ? false : ($segundo_visado == '3' ? 'all' : 'null'));
        }
        $page = $request->query('page');
        $itemsPerPage = $request->query('itemsPerPage');
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        $roles = array(15, 18, 19, 3, 5, 27, 9, 28, 16, 8, 17, 22, 10, 25);
        $user = $request->user();

        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $dependenciasHijas = Dependencia::hijas($dependencia);
        $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');

        $user = User::where('idusuario', '=', $request->user()->idusuario)
            ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->leftJoin('teams', 'role_user.team_id', 'teams.id')
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();
        if (empty($user)) {
            $user = \App\User::where('idusuario', '=', $request->user()->idusuario)
                ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', 'roles.id')
                ->leftJoin('teams', 'role_user.team_id', 'teams.id')
                ->whereIn('role_user.role_id', $roles)
                ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
                ->first();
        }


        // Now use $IdsDependenciasHijas in your query logic
        if (
            in_array($user->display_name, [
                'Gerencia',
                'Departamento Seleccion',
                'Salud Ocupacional',
                'Departamento Planificación',
                'Formacion y Capacitacion',
                'Administracion y Despacho',
                'Juridico',
                //'Jefe Personal de Areas Operativas Con Carga De RI'
            ])
            || $tienePermisoVerTodo
        ) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $IdsDependenciasHijasNoGerencia)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
            30,
            31,
            32,
            33,
            34,
            35,
            36,
            37,
            38,
            39,
            40,
            41
        ];
        if ($user->display_name === 'Salud Ocupacional') {
            $tipoLicenciaCodigos = [1, 2, 3, 4, 7, 8, 11, 21, 22, 35, 36, 37, 38, 39, 40];
            $visado = 2;
        } elseif ($user->display_name === 'Administracion y Despacho') {
            $tipoLicenciaCodigos = [15, 6, 30, 31, 32];
            $visado = 2;
        } elseif ($user->display_name === 'Formacion y Capacitacion') {
            $tipoLicenciaCodigos = [18, 19];
            $visado = 2;
        } elseif ($user->display_name === "Consulta para Directores") {
            $visado = 1;
        }

        $Licencias = Licencia::whereIn('licencias.idagente', $IdAgentes)
            ->join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
                $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                    ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
            })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'puesto.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'puesto.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'puesto.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'puesto.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->whereBetween('licencias.fecha_pedido_inicio', [$last, $to]);



        if ($visado == 1) {
            $Licencias = $Licencias->whereNull('licencias.primer_visado');
        } elseif ($visado == 2) {
            $Licencias = $Licencias->whereNull('licencias.segundo_visado')
                ->where('licencias.primer_visado', '=', true);
        }

        if (isset($efector)) {
            $Licencias = $Licencias->where('e.dependencia', 'LIKE', '%' . $efector . '%');
        }

        if (isset($codigo_nombre)) {
            $Licencias = $Licencias->where('d.codigorrhh', 'LIKE', '%' . $codigo_nombre . '%');
            $Licencias = $Licencias->orWhere('d.dependencia', 'LIKE', '%' . $codigo_nombre . '%');
        }

        if (isset($documento)) {
            $Licencias = $Licencias->where('agente.documento', 'LIKE', '%' . $documento . '%');
        }

        if (isset($apellido_nombre)) {
            $Licencias = $Licencias->where('agente.nombre', 'LIKE', '%' . $apellido_nombre . '%');
            $Licencias = $Licencias->orWhere('agente.apellido', 'LIKE', '%' . $apellido_nombre . '%');
        }

        if (isset($tipoLicencia)) {
            $Licencias = $Licencias->where('tipo_licencias.descripcion', 'LIKE', '%' . $tipoLicencia . '%');
        }
        if (isset($dias)) {
            $Licencias = $Licencias->where('licencias.dias', '=', $dias);
        }

        if (isset($idLicencia) && $idLicencia != 'undefined') {
            $Licencias = $Licencias->where('licencias.idlicencia', '=', $idLicencia);
        }

        if (isset($fecha_pedido_inicio)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_pedido_inicio', '>=', $fecha_pedido_inicio);
        }
        if (isset($fecha_pedido_final)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_pedido_final', '<=', $fecha_pedido_final);
        }
        if (isset($fecha_efectiva_inicio)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_efectiva_inicio', '>=', $fecha_efectiva_inicio);
        }

        if (isset($fecha_efectiva_final)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_efectiva_final', '<=', $fecha_efectiva_final);
        }
        if (isset($fecha_interrupcion_inicio)) {
            $Licencias = $Licencias->where('licencias.fecha_interrupcion_inicio', '=', $fecha_interrupcion_inicio);
        }
        if (isset($primer_visado)) {

            if (strcmp($primer_visado, 'null') == 0 && $visado == 0) {
                $Licencias = $Licencias->whereNull('licencias.primer_visado');
            } elseif (strcmp($primer_visado, 'all') == 0) {
            } elseif (($primer_visado == true || $primer_visado == false) && $visado == 0) {
                $Licencias->where('licencias.primer_visado', '=', $primer_visado);
            }
        }

        if (isset($segundo_visado)) {
            if (strcmp($segundo_visado, 'null') == 0 && $visado == 0) {
                $Licencias = $Licencias->whereNull('licencias.segundo_visado');
            } elseif (strcmp($segundo_visado, 'all') == 0) {
            } elseif (($segundo_visado == true || $segundo_visado == false) && $visado == 0) {
                $Licencias->where('licencias.segundo_visado', '=', $segundo_visado);
            }
        }

        if (isset($cuarta_visado)) {
            if (strcmp($cuarta_visado, 'null') == 0 && $visado == 0) {
                $Licencias = $Licencias->whereNull('licencias.cuarta_visado');
            } elseif (strcmp($cuarta_visado, 'all') == 0) {
            } elseif (($segundo_visado == true || $cuarta_visado == false) && $visado == 0) {
                $Licencias->where('licencias.cuarta_visado', '=', $cuarta_visado);
            }
        }

        $Licencias = $Licencias->select([
            'd.iddependencia as iddependencia',
            'puesto.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'e.dependencia as efector',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'tipo_licencias.descripcion as descripcion'
        ])->selectRaw("concat(apellido,' ',nombre) as apellido_nombre, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre");

        if (strcmp($sortDesc, 'false') == 0) {
            $Licencias = $Licencias->orderBy($sortBy);
        }
        if (strcmp($sortDesc, 'true') == 0) {
            $Licencias = $Licencias->orderByDesc($sortBy);
        }

        $Licencias = $Licencias->paginate($itemsPerPage);


        if (empty($Licencias)) {
            return $this->sendError('Licencias no encontrado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivo successfully');
    }


    /**
     * Get Licencias para la pantalla de visado masivo
     *  /api/licencias/dependiente/
     * @param Request $request
     * @return mixed
     */
    public function getLicenciasDependientesCapacitacion(Request $request)
    {
        $now = Carbon::now('-3');
        $last = Carbon::now('-3')->subYear(2);
        $to = Carbon::now('-3')->addYear(2);
        $user = $request->user();
        $idagente = $request->query('idagente');
        $efector = $request->query('efector');
        $codigo_nombre = $request->query('codigo_nombre');
        $documento = $request->query('documento');
        $apellido_nombre = $request->query('apellido_nombre');
        $tipoLicencia = $request->query('tipoLicencia');
        $dias = $request->query('dias');
        $idLicencia = $request->query('idlicencia');
        $primer_visado = $request->query('primer_visado');
        $segundo_visado = $request->query('segundo_visado');
        $cuarta_visado = $request->query('cuarta_visado');
        $caracter = $request->query('caracter');
        $resolucion = $request->query('resolucion');
        $alcance = $request->query('alcance');
        $tipoEvento = $request->query('tipoEvento');
        $idCapacitacion = $request->query('idCapacitacion');
        $evento_nombre = $request->query('evento_nombre');
        $evento_lugar = $request->query('evento_lugar');
        $dependencia = $request->query('dependencia');
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        if (!empty($request->query('fecha_pedido_inicio'))) {
            $fecha_pedido_inicio = new \DateTime($request->query('fecha_pedido_inicio'));
        }
        if (!empty($request->query('fecha_pedido_final'))) {
            $fecha_pedido_final = new \DateTime($request->query('fecha_pedido_final'));
        }
        if (!empty($request->query('fecha_efectiva_inicio'))) {
            $fecha_efectiva_inicio = new \DateTime($request->query('fecha_efectiva_inicio'));
        }
        if (!empty($request->query('fecha_efectiva_final'))) {
            $fecha_efectiva_final = new \DateTime($request->query('fecha_efectiva_final'));
        }
        if (!empty($request->query('fecha_visado_primero'))) {
            $fecha_visado_primero = new \DateTime($request->query('fecha_visado_primero'));
        }
        if (!empty($request->query('fecha_visado_segundo'))) {
            $fecha_visado_segundo = new \DateTime($request->query('fecha_visado_segundo'));
        }

        $page = $request->query('page');
        $itemsPerPage = $request->query('itemsPerPage');
        $sortBy = $request->query('sortBy');
        $sortDesc = $request->query('sortDesc');

        $roles = array(15, 18, 19, 3, 5, 27, 9, 28, 16, 8, 17, 22, 10, 25);
        $user = $request->user();

        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $dependenciasHijas = Dependencia::hijas($dependencia);
        $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');


        $user = User::where('idusuario', '=', $request->user()->idusuario)
            ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->leftJoin('teams', 'role_user.team_id', 'teams.id')
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();
        if (empty($user)) {
            $user = \App\User::where('idusuario', '=', $request->user()->idusuario)
                ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', 'roles.id')
                ->leftJoin('teams', 'role_user.team_id', 'teams.id')
                ->whereIn('role_user.role_id', $roles)
                ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
                ->first();
        }
        // Now use $IdsDependenciasHijas in your query logic
        if (in_array(
            $user->display_name,
            [
                'Gerencia',
                'Departamento Seleccion',
                'Salud Ocupacional',
                'Departamento Planificación',
                'Formacion y Capacitacion',
                'Administracion y Despacho',
                'Juridico',
                //'Jefe Personal de Areas Operativas Con Carga De RI'
            ]
        ) || $tienePermisoVerTodo) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $IdsDependenciasHijasNoGerencia)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            18,
            19
        ];

        $Licencias = Licencia::join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
            $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
        })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'puesto.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'puesto.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'puesto.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'puesto.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->join('capacitacion', 'capacitacion.idCapacitacion', 'licencias.idCapacitacion')
            ->join('alcance_capacitacion', 'alcance_capacitacion.idAlcanceCapacitacion', 'capacitacion.idAlcanceCapacitacion')
            ->join('tipo_evento', 'tipo_evento.idTipoEvento', 'capacitacion.idTipoEvento')
            ->whereBetween('licencias.fecha_pedido_inicio', [$last, $to]);
        // Añadir la condición para excluir licencias con segundo_visado True o False
        //->whereNull('licencias.segundo_visado');

        // no todas licencias se muestran porque no tienen enlaces a las otras tablas

        if (isset($efector)) {
            $Licencias = $Licencias->where('e.dependencia', 'LIKE', '%' . $efector . '%');
        }

        if (isset($codigo_nombre)) {
            $Licencias = $Licencias->where('d.codigorrhh', 'LIKE', '%' . $codigo_nombre . '%');
            $Licencias = $Licencias->orWhere('d.dependencia', 'LIKE', '%' . $codigo_nombre . '%');
        }

        if (isset($documento)) {
            $Licencias = $Licencias->where('agente.documento', 'LIKE', '%' . $documento . '%');
        }

        if (isset($apellido_nombre)) {
            $Licencias = $Licencias->where('agente.nombre', 'LIKE', '%' . $apellido_nombre . '%');
            $Licencias = $Licencias->orWhere('agente.apellido', 'LIKE', '%' . $apellido_nombre . '%');
        }
        if (isset($tipoLicencia)) {
            $Licencias = $Licencias->where('tipo_licencias.descripcion', 'LIKE', '%' . $tipoLicencia . '%');
        }
        if (isset($dias)) {
            $Licencias = $Licencias->where('licencias.dias', '=', $dias);
        }

        if (isset($idLicencia) && $idLicencia != 'undefined') {
            $Licencias = $Licencias->where('licencias.idlicencia', '=', $idLicencia);
        }
        if (isset($fecha_pedido_inicio)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_pedido_inicio', '>=', $fecha_pedido_inicio);
        }
        if (isset($fecha_pedido_final)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_pedido_final', '<=', $fecha_pedido_final);
        }
        if (isset($fecha_efectiva_inicio)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_efectiva_inicio', '>=', $fecha_efectiva_inicio);
        }
        if (isset($fecha_visado_primero)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_visado_primero', '>=', $fecha_visado_primero);
        }
        if (isset($fecha_visado_segundo)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_visado_segundo', '>=', $fecha_visado_segundo);
        }
        if (isset($fecha_efectiva_final)) {
            $Licencias = $Licencias->whereDate('licencias.fecha_efectiva_final', '<=', $fecha_efectiva_final);
        }
        if (isset($fecha_interrupcion_inicio)) {
            $Licencias = $Licencias->where('licencias.fecha_interrupcion_inicio', '=', $fecha_interrupcion_inicio);
        }
        if (isset($fecha_interrupcion_final)) {
            $Licencias = $Licencias->where('licencias.fecha_interrupcion_final', '=', $fecha_interrupcion_final);
        }
        if (isset($primer_visado)) {
            switch ($primer_visado) {
                case 2:
                    $Licencias = $Licencias->whereNull('licencias.primer_visado');
                    break;
                case 3:
                    break;
                case 0:
                    $Licencias = $Licencias->where('licencias.primer_visado', '=', true);
                    break;
                case 1:
                    $Licencias = $Licencias->where('licencias.primer_visado', '=', false);
                    break;
            }
        }
        if (isset($segundo_visado)) {
            switch ($segundo_visado) {
                case 2:
                    $Licencias = $Licencias->whereNull('licencias.segundo_visado');
                    break;
                case 3:
                    break;
                case 0:
                    $Licencias = $Licencias->where('licencias.segundo_visado', '=', true);
                    break;
                case 1:
                    $Licencias = $Licencias->where('licencias.segundo_visado', '=', false);
                    break;
            }
        }
        if (isset($cuarta_visado)) {
            switch ($cuarta_visado) {
                case 2:
                    $Licencias = $Licencias->whereNull('licencias.cuarta_visado');
                    break;
                case 3:
                    break;
                case 0:
                    $Licencias = $Licencias->where('licencias.cuarta_visado', '=', true);
                    break;
                case 1:
                    $Licencias = $Licencias->where('licencias.cuarta_visado', '=', false);
                    break;
            }
        }
        if (isset($caracter)) {
            $Licencias = $Licencias->where('licencias.caracter', '=', $caracter);
        }
        if (isset($resolucion)) {
            $Licencias = $Licencias->where('licencias.resolucion', '=', $resolucion);
        }
        if (isset($alcance)) {
            $Licencias = $Licencias->where('alcance_capacitacion.descripcion', '=', $alcance);
        }
        if (isset($tipoEvento)) {
            $Licencias = $Licencias->where('tipo_evento.descripcion', 'LIKE', '%' . $tipoEvento . '%');
        }
        if (isset($idCapacitacion)) {
            $Licencias = $Licencias->where('capacitacion.idCapacitacion', '=', $idCapacitacion);
        }
        if (isset($evento_nombre)) {
            $Licencias = $Licencias->whereRaw('LOWER(capacitacion.evento_nombre) LIKE ?', ['%' . strtolower($evento_nombre) . '%']);
        }
        if (isset($evento_lugar)) {
            $Licencias = $Licencias->whereRaw('LOWER(capacitacion.evento_lugar) LIKE ?', ['%' . strtolower($evento_lugar) . '%']);
        }
        if (isset($fecha_evento_inicio)) {
            $Licencias = $Licencias->whereDate('capacitacion.fecha_evento_inicio', '>=', $fecha_evento_inicio);
        }
        if (isset($fecha_evento_final)) {
            $Licencias = $Licencias->whereDate('capacitacion.fecha_evento_final', '<=', $fecha_evento_final);
        }

        $Licencias = $Licencias->select([
            'd.iddependencia as iddependencia',
            'puesto.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'e.dependencia as efector',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'tipo_licencias.descripcion as descripcion',
            'tipo_evento.descripcion as tipo_evento',
            'capacitacion.evento_nombre as evento_nombre',
            'capacitacion.evento_lugar as evento_lugar',
            'capacitacion.fecha_evento_inicio as fecha_evento_inicio',
            'capacitacion.fecha_evento_final as fecha_evento_final',
            'capacitacion.programa as programa',
            'licencias.idCapacitacion as idCapacitacion',
            'licencias.caracter as caracter',
            'alcance_capacitacion.descripcion as alcance',
            'licencias.id_agente_primer_visado as id_agente_primer_visado',
            'licencias.id_agente_segundo_visado as id_agente_segundo_visado',
        ])->selectRaw("concat(apellido,' ',nombre) as apellido_nombre, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre");

        if (strcmp($sortDesc, 'false') == 0) {
            $Licencias = $Licencias->orderBy($sortBy);
        }
        if (strcmp($sortDesc, 'true') == 0) {
            $Licencias = $Licencias->orderByDesc($sortBy);
        }
        $Licencias = $Licencias->paginate($itemsPerPage);

        if (empty($Licencias)) {
            return $this->sendError('Licencias no encontrado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivamente devueltas');
    }


    /**
     * Get Licencias para la pantalla de consulta
     * /api/licencias/consulta
     * @param Request $request
     *
     * @return mixed
     */
    public function getLicenciasConsulta(Request $request)
    {
        $last = $request->query('fecha_desde');
        $to = $request->query('fecha_hasta');
        $dependencia = $request->query('dependencia');
        $tipoLicencias = explode(',', $request->query('tipo_licencias'));
        $to = Carbon::parse($to)->format('Y-m-d');
        $last = Carbon::parse($last)->format('Y-m-d');
        $user = $request->user();
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        $user_role = User::where('idusuario', '=', $user->idusuario)
            ->join('role_user', function ($join) {
                $join->on('role_user.user_id', '=', 'usuario.idusuario');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->join('roles_teams', function ($join) {
                $join->on('roles.id', '=', 'roles_teams.role_id');
            })
            ->join('teams', function ($join) {
                $join->on('teams.id', '=', 'roles_teams.team_id');
            })
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();

        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');
        // $dependenciasHijas = Dependencia::hijas($dependencia);
        // $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');

        // Now use $IdsDependenciasHijas in your query logic
        if (in_array($user_role->display_name, [
            'Consulta para Directores',
            'Gestion de Capital Humano',
            'Salud Ocupacional',
            'Gerencia',
            'Departamento Seleccion',
            'Administracion y Despacho',
            'Jefe Dpto Seleccion',
            'Jefe Dpto Planificacion',
            'Juridico',
            //'Jefe Personal de Areas Operativas Con Carga De RI'
        ]) || $tienePermisoVerTodo) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $iddependencias)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
            30,
            31,
            32,
            33,
            35,
            36,
            37,
            38,
            39,
            40,
            41
        ];

        if ($user_role->display_name === 'Salud Ocupacional') {
            $tipoLicenciaCodigos = [1, 2, 3, 4, 7, 8, 11, 21, 22, 35, 36, 37, 38, 39, 40];
            $visado = 2;
        } elseif ($user_role->display_name === 'Administracion y Despacho') {
            $tipoLicenciaCodigos = [15, 6, 30, 31, 32];
            $visado = 2;
        } elseif ($user_role->display_name === 'Formacion y Capacitacion') {
            $tipoLicenciaCodigos = [18, 19];
        } elseif ($user_role->display_name === "Consulta para Directores") {
            $visado = 1;
        }
        if (count($tipoLicencias) > 1) {
            $tipoLicenciaCodigos = $tipoLicencias;
        }
        $Licencias = Licencia::whereIn('licencias.idagente', $IdAgentes)
            ->join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
                $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                    ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
            })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto where fhasta is null group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'p.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'p.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->leftJoin('licencia_familiares as lf', 'licencias.idlicencia', 'lf.idlicencia')
            ->leftJoin('personas as per', 'lf.idpersona', 'per.idpersona')
            ->leftJoin('grupo_familiar_personas as gfp', 'per.idpersona', 'gfp.idpersona')
            ->leftJoin('tipo_parentescos as tp', 'gfp.idtipoParentesco', 'tp.idtipoParentesco')
            ->where('licencias.fecha_pedido_inicio', '<=', $to)
            ->where('licencias.fecha_pedido_final', '>=', $last);


        $Licencias = $Licencias->select([
            'e.dependencia as efector',
            'd.iddependencia as iddependencia',
            'p.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'tipo_licencias.descripcion as descripcion',
            'per.nombre as nombre_familiar',
            'per.apellido as apellido_familiar',
            'tp.descripcion as parentesco'
        ])->selectRaw("concat(agente.apellido,' ',agente.nombre) as apellido_nombre, concat(per.apellido,' ',per.nombre) as apellido_nombre_familiar, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre")
            ->distinct('licencias.idlicencia')->get();

        $counter = 1;
        $Licencias->each(function ($licencia) use (&$counter) {
            // Formatting the created_at date to "MMMM YYYY"
            $formattedDate = Carbon::parse($licencia->created_at)->format('F Y');
            // Constructing the new column
            $licencia->observaciones = "{$formattedDate} {$licencia->dias} DIAS-LEY 9254 ART {$licencia->descripcion} POR {$licencia->apellido_nombre_familiar}";
        });

        if ($Licencias->isEmpty()) {
            return $this->sendResponse($Licencias, 'Licencias no encontradas para el periodo seleccionado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivo successfully');
    }

    public function getLicenciasRetroactiva(Request $request)
    {
        $mes = Carbon::createFromFormat('Y-m-d', $request->query('mes'));
        $dependencia = $request->query('dependencia');
        $tipoLicencias = explode(',', $request->query('tipo_licencias'));
        // Define las fechas de inicio y fin del mes
        $inicioMes = $mes->startOfMonth()->toDateTimeString();
        $finMes = $mes->endOfMonth()->toDateTimeString();
        $user = $request->user();
        $idagente = $user->idagente;
        $user_role = User::where('idusuario', '=', $user->idusuario)
            ->join('role_user', function ($join) {
                $join->on('role_user.user_id', '=', 'usuario.idusuario');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->join('roles_teams', function ($join) {
                $join->on('roles.id', '=', 'roles_teams.role_id');
            })
            ->join('teams', function ($join) {
                $join->on('teams.id', '=', 'roles_teams.team_id');
            })
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();

        $user = $request->user();
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        // $dependenciasHijas = Dependencia::hijas($dependencia);
        // $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');

        // Now use $IdsDependenciasHijas in your query logic
        if (in_array($user_role->display_name, [
            'Consulta para Directores',
            'Gestion de Capital Humano',
            'Salud Ocupacional',
            'Gerencia',
            'Departamento Seleccion',
            'Administracion y Despacho',
            'Jefe Dpto Seleccion',
            'Jefe Dpto Planificacion',
            'Juridico',
            //'Jefe Personal de Areas Operativas Con Carga De RI'
        ]) || $tienePermisoVerTodo) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $iddependencias)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
            30,
            31,
            32,
            33,
            35,
            36,
            37,
            38,
            39,
            40,
            41
        ];

        if ($user_role->display_name === 'Salud Ocupacional') {
            $tipoLicenciaCodigos = [1, 2, 3, 4, 7, 8, 11, 21, 22, 35, 36, 37, 38, 39, 40];
            $visado = 2;
        } elseif ($user_role->display_name === 'Administracion y Despacho') {
            $tipoLicenciaCodigos = [15, 6, 30, 31, 32];
            $visado = 2;
        } elseif ($user_role->display_name === 'Formacion y Capacitacion') {
            $tipoLicenciaCodigos = [18, 19];
        } elseif ($user_role->display_name === "Consulta para Directores") {
            $visado = 1;
        }

        if (count($tipoLicencias) > 1) {
            $tipoLicenciaCodigos = $tipoLicencias;
        }
        $Licencias = Licencia::whereIn('licencias.idagente', $IdAgentes)
            ->join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
                $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                    ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
            })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto where fhasta is null group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'p.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'p.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->leftJoin('licencia_familiares as lf', 'licencias.idlicencia', 'lf.idlicencia')
            ->leftJoin('personas as per', 'lf.idpersona', 'per.idpersona')
            ->leftJoin('grupo_familiar_personas as gfp', 'per.idpersona', 'gfp.idpersona')
            ->leftJoin('tipo_parentescos as tp', 'gfp.idtipoParentesco', 'tp.idtipoParentesco')
            ->whereBetween('licencias.fecha_pedido_inicio', [$inicioMes, $finMes])
            ->whereBetween('licencias.fecha_pedido_final', [$inicioMes, $finMes]);

        $Licencias = $Licencias->select([
            'e.dependencia as efector',
            'd.iddependencia as iddependencia',
            'p.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'tipo_licencias.descripcion as descripcion',
            'per.nombre as nombre_familiar',
            'per.apellido as apellido_familiar',
            'tp.descripcion as parentesco'
        ])->selectRaw("concat(agente.apellido,' ',agente.nombre) as apellido_nombre, concat(per.apellido,' ',per.nombre) as apellido_nombre_familiar, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre")
            ->distinct('licencias.idlicencia')->get();

        $counter = 1;
        $Licencias->each(function ($licencia) use (&$counter) {
            // Formatting the created_at date to "MMMM YYYY"
            $formattedDate = Carbon::parse($licencia->created_at)->format('F Y');
            // Constructing the new column
            $licencia->observaciones = "{$formattedDate} {$licencia->dias} DIAS-LEY 9254 ART {$licencia->descripcion} POR {$licencia->apellido_nombre_familiar}";
        });

        if ($Licencias->isEmpty()) {
            return $this->sendResponse($Licencias, 'Licencias no encontradas para el periodo seleccionado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivo successfully');
    }

    public function getLicenciasMensual(Request $request)
    {
        $mes = Carbon::createFromFormat('Y-m-d', $request->query('mes'));
        $tipoLicencias = explode(',', $request->query('tipo_licencias'));
        // Define las fechas de inicio y fin del mes
        $inicioMes = $mes->startOfMonth()->toDateTimeString();
        $finMes = $mes->endOfMonth()->toDateTimeString();
        $dependencia = $request->query('dependencia');
        $user = $request->user();
        $idagente = $user->idagente;
        $user_role = User::where('idusuario', '=', $user->idusuario)
            ->join('role_user', function ($join) {
                $join->on('role_user.user_id', '=', 'usuario.idusuario');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->join('roles_teams', function ($join) {
                $join->on('roles.id', '=', 'roles_teams.role_id');
            })
            ->join('teams', function ($join) {
                $join->on('teams.id', '=', 'roles_teams.team_id');
            })
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();


        $user = $request->user();
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $dependenciasHijas = Dependencia::hijas($dependencia);
        // $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');

        // Now use $IdsDependenciasHijas in your query logic
        if (in_array($user_role->display_name, [
            'Consulta para Directores',
            'Gestion de Capital Humano',
            'Salud Ocupacional',
            'Gerencia',
            'Departamento Seleccion',
            'Administracion y Despacho',
            'Jefe Dpto Seleccion',
            'Jefe Dpto Planificacion',
            'Juridico',
            //'Jefe Personal de Areas Operativas Con Carga De RI'
        ]) || $tienePermisoVerTodo) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $iddependencias)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            20,
            21,
            22,
            23,
            24,
            25,
            26,
            27,
            28,
            29,
            30,
            31,
            32,
            33,
            35,
            36,
            37,
            38,
            39,
            40,
            41
        ];

        if ($user_role->display_name === 'Salud Ocupacional') {
            $tipoLicenciaCodigos = [1, 2, 3, 4, 7, 8, 11, 21, 22, 35, 36, 37, 38, 39, 40];
            $visado = 2;
        } elseif ($user_role->display_name === 'Administracion y Despacho') {
            $tipoLicenciaCodigos = [15, 6, 30, 31, 32];
            $visado = 2;
        } elseif ($user_role->display_name === 'Formacion y Capacitacion') {
            $tipoLicenciaCodigos = [18, 19];
        } elseif ($user_role->display_name === "Consulta para Directores") {
            $visado = 1;
        }

        if (count($tipoLicencias) > 1) {
            $tipoLicenciaCodigos = $tipoLicencias;
        }
        $Licencias = Licencia::whereIn('licencias.idagente', $IdAgentes)
            ->join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
                $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                    ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
            })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto where fhasta is null group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'p.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'p.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->join('licencia_familiares as lf', 'licencias.idlicencia', 'lf.idlicencia')
            ->join('personas as per', 'lf.idpersona', 'per.idpersona')
            ->join('grupo_familiar_personas as gfp', 'per.idpersona', 'gfp.idpersona')
            ->join('tipo_parentescos as tp', 'gfp.idtipoParentesco', 'tp.idtipoParentesco')
            ->whereBetween('licencias.fecha_pedido_inicio', [$inicioMes, $finMes])
            ->whereBetween('licencias.fecha_pedido_final', [$inicioMes, $finMes])
            ->whereBetween('licencias.created_at', [$inicioMes, $finMes]);

        $Licencias = $Licencias->select([
            'e.dependencia as efector',
            'd.iddependencia as iddependencia',
            'p.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'tipo_licencias.descripcion as descripcion',
            'per.nombre as nombre_familiar',
            'per.apellido as apellido_familiar',
            'tp.descripcion as parentesco'
        ])->selectRaw("concat(agente.apellido,' ',agente.nombre) as apellido_nombre, concat(per.apellido,' ',per.nombre) as apellido_nombre_familiar, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre")
            ->orderBy('fecha_efectiva_inicio')->get();

        $counter = 1;
        $Licencias->each(function ($licencia) use (&$counter) {
            $licencia->contador = $counter++;
            // Formatting the created_at date to "MMMM YYYY"
            $formattedDate = Carbon::parse($licencia->created_at)->format('F Y');
            // Constructing the new column
            $licencia->observaciones = "{$formattedDate} {$licencia->dias} DIAS-LEY 9254 ART {$licencia->descripcion} POR {$licencia->apellido_nombre_familiar} {$licencia->parentesco}";
        });

        if ($Licencias->isEmpty()) {
            return $this->sendResponse($Licencias, 'Licencias no encontradas para el periodo seleccionado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivo successfully');
    }

    public function exportLicencias(Request $request)
    {
        $idsLicencias = $request->input('licencias');

        $export = new LicenciasExport($idsLicencias);

        return Excel::download($export, 'licencias.xlsx');
    }
    public function getCapacitacion(Request $request)
    {
        $licencia = $request->idlicencia;
        $capacitacion = Licencia::where('idlicencia', '=', $licencia)
            ->join('capacitacion as c', 'licencias.idCapacitacion', 'c.idCapacitacion')
            ->leftJoin('tipo_evento as tev', 'tev.idTipoEvento', '=', 'c.idTipoEvento')
            ->leftJoin('alcance_capacitacion as ac', 'ac.idAlcanceCapacitacion', '=', 'c.idAlcanceCapacitacion')
            ->select([
                'licencias.resolucion as resolucion',
                'licencias.caracter as caracter',
                'tev.descripcion as tipo_evento',
                'ac.descripcion as alcance',
                'c.razon as razon',
                'c.fecha_evento_inicio as fecha_evento_inicio',
                'c.fecha_evento_final as fecha_evento_final',
                'c.evento_nombre as evento_nombre',
                'c.evento_lugar as evento_lugar',
            ])
            ->get();
        return $this->sendResponse($capacitacion->toArray(), 'Capacitacion devuelta');
    }
    public function getSaldosPersonasSalud(Request $request)
    {
        $IdAgente = $request->idagente;

        $PersonasFiltros = DB::table('grupo_familiares as gf')
            ->join('grupo_familiar_personas as gfp', 'gfp.idgrupoFamiliar', 'gf.idgrupoFamiliar')
            ->where('gf.activo', true)->where('gf.idagente', '=', $IdAgente)->pluck('gfp.idpersona')->toArray();

        $Saldos = DB::table('licencia_familiares as lf')
            ->join('licencia_saldos as ls', 'ls.idlicencia', 'lf.idlicencia')
            ->join('personas as p', 'lf.idpersona', 'p.idpersona')
            ->join('tipo_licencias as tll', 'tll.idtipoLicencia', 'ls.idtipoLicencia')
            ->whereIn('ls.idtipoLicencia', [21, 22])
            ->whereIn('lf.idpersona', $PersonasFiltros)
            ->whereNull('ls.deleted_at')
            ->whereNull('lf.deleted_at')
            ->groupBy('lf.idpersona', 'ls.año', 'ls.idtipoLicencia')
            ->selectRaw('lf.idpersona, min(p.nombre) as "nombre", min(p.apellido) as "apellido",ls.año, ls."idtipoLicencia" as "codigo", min(tll.descripcion) as "tipo_licencia", sum(ls.dias) as "dias", case when ls."idtipoLicencia" = 21 then 90 else 180 end as "disponible",  case when ls."idtipoLicencia" = 21 then 90 else 180 end - sum(ls.dias) as "saldo"')
            ->get();

        return $this->sendResponse($Saldos->toArray(), 'Saldos de familiares devueltos');
    }
    public function licenciasCapacitaciones(Request $request)
    {
        $last = $request->query('fecha_desde');
        $to = $request->query('fecha_hasta');
        $to = Carbon::parse($to)->format('Y-m-d');
        $last = Carbon::parse($last)->format('Y-m-d');
        // comprobar si $last es una fecha antes que el $to y intercambiarlas en todo caso
        if ($last > $to) {
            $temp = $last;
            $last = $to;
            $to = $temp;
        }
        $user = $request->user();
        $dependencia = $request->query('dependencia');
        $idagente = $user->idagente;
        $user_role = User::where('idusuario', '=', $user->idusuario)
            ->join('role_user', function ($join) {
                $join->on('role_user.user_id', '=', 'usuario.idusuario');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->join('roles_teams', function ($join) {
                $join->on('roles.id', '=', 'roles_teams.role_id');
            })
            ->join('teams', function ($join) {
                $join->on('teams.id', '=', 'roles_teams.team_id');
            })
            ->where('teams.name', '=', 'licencias')
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();


        $user = $request->user();
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        // $dependenciasHijas = Dependencia::hijas($dependencia);
        // $IdsDependenciasHijasNoGerencia = $dependenciasHijas->pluck('iddependencia');
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');

        // Now use $IdsDependenciasHijas in your query logic
        if (in_array($user_role->display_name, [
            'Consulta para Directores',
            'Gestion de Capital Humano',
            'Salud Ocupacional',
            'Gerencia',
            'Departamento Seleccion',
            'Administracion y Despacho',
            'Jefe Dpto Seleccion',
            'Jefe Dpto Planificacion',
            'Juridico',
            //'Jefe Personal de Areas Operativas Con Carga De RI'
        ]) || $tienePermisoVerTodo) {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->pluck('agente.idagente')
                ->toArray();
        } else {
            $IdAgentes = Agente::join('puesto as p', 'p.idagente', 'agente.idagente')
                ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
                ->whereIn('p.iddependencia', $iddependencias)
                ->pluck('agente.idagente')
                ->toArray();
        }

        $visado = 0;
        $tipoLicenciaCodigos = [
            18,
            19
        ];

        $Licencias = Licencia::whereIn('licencias.idagente', $IdAgentes)
            ->join('tipo_licencias', function ($join) use ($tipoLicenciaCodigos) {
                $join->on('licencias.idtipoLicencia', '=', 'tipo_licencias.idtipoLicencia')
                    ->whereIn('tipo_licencias.codigo', $tipoLicenciaCodigos);
            })
            ->join('agente', 'licencias.idagente', '=', 'agente.idagente')
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto where fhasta is null group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('dependencia as e', function ($join) {
                $join->on('licencias.idefector', '=', 'e.iddependencia')
                    ->select(['e.dependencia as efector']);
            })
            ->join('dependencia as d', 'p.iddependencia', 'd.iddependencia')
            ->join('tipo_dependencia', 'd.idtipo_dependencia', 'tipo_dependencia.idtipo_dependencia')
            ->join('tipo_nivel', 'p.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->join('capacitacion as c', 'licencias.idCapacitacion', 'c.idCapacitacion')
            //->leftJoin('caracter as car', 'car.idCaracter', '=', 'c.idCaracter')
            ->leftJoin('tipo_evento as tev', 'tev.idTipoEvento', '=', 'c.idTipoEvento')
            ->leftJoin('alcance_capacitacion as ac', 'ac.idAlcanceCapacitacion', '=', 'c.idAlcanceCapacitacion')
            ->whereBetween('licencias.fecha_visado_segundo', [$last, $to]);

        $Licencias = $Licencias->select([
            'licencias.caracter as caracter',
            'tev.descripcion as tipo_evento',
            'ac.descripcion as alcance',
            'c.fecha_evento_inicio as fecha_evento_inicio',
            'c.fecha_evento_final as fecha_evento_final',
            'c.evento_nombre as evento_nombre',
            'c.evento_lugar as evento_lugar',
            'e.dependencia as efector',
            'd.iddependencia as iddependencia',
            'p.idpuesto as idpuesto',
            'd.dependencia as servicio',
            'd.codigorrhh as codigo',
            'tipo_funcion.tipofuncion as funcion',
            'tipo_nivel.tiponivel as nivel',
            'tipo_planta.tipoplanta as planta',
            'agente.documento as documento',
            'agente.nombre as nombre',
            'agente.apellido as apellido',
            'licencias.fecha_pedido_inicio as fecha_pedido_inicio',
            'licencias.fecha_pedido_final as fecha_pedido_final',
            'licencias.fecha_efectiva_inicio as fecha_efectiva_inicio',
            'licencias.fecha_efectiva_final as fecha_efectiva_final',
            'licencias.fecha_visado_primero as fecha_visado_primero',
            'licencias.fecha_visado_segundo as fecha_visado_segundo',
            'licencias.fecha_interrupcion_inicio as fecha_interrupcion_inicio',
            'licencias.created_at as created_at',
            'licencias.idlicencia as idlicencia',
            'licencias.dias as dias',
            'licencias.primer_visado as primer_visado',
            'licencias.segundo_visado as segundo_visado',
            'licencias.cuarta_visado as cuarta_visado',
            'licencias.observacion_primera as primera_observacion',
            'licencias.observacion_segunda as segunda_observacion',
            'tipo_licencias.descripcion as descripcion',
            'licencias.id_agente_primer_visado as id_agente_primer_visado',
            'licencias.id_agente_segundo_visado as id_agente_segundo_visado',
        ])->selectRaw("concat(apellido,' ',nombre) as apellido_nombre, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre")->orderBy('fecha_pedido_inicio')->get();


        if ($Licencias->isEmpty()) {
            return $this->sendResponse($Licencias, 'Licencias no encontradas para el periodo seleccionado');
        }

        return $this->sendResponse($Licencias->toArray(), 'Licencias de masivo successfully');
    }

    public function fetchTipoLicencias(Request $request)
    {
        $tipoLicencias = TipoLicencia::all();
        return $this->sendResponse($tipoLicencias->toArray(), 'Tipo de licencias devueltas');
    }
}
