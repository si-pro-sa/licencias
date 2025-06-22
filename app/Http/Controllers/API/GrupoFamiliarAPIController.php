<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGrupoFamiliarAPIRequest;
use App\Http\Requests\API\UpdateGrupoFamiliarAPIRequest;
use App\Http\Requests\API\CreatePersonaAPIRequest;
use App\Models\Dependencia;
use App\Models\DependenciaUsuario;
use App\Models\DependenciaRelacion;
use App\Models\GrupoFamiliar;
use App\Models\Persona;
use App\Models\GrupoFamiliarPersona;
use App\Models\TipoParentesco;
use App\Models\HorarioPuestoHistorico;
use App\Models\Agente;
use App\Models\HorarioPuesto;
use App\Models\Usuario;
use App\Repositories\GrupoFamiliarRepository;
use App\Repositories\AgenteRepository;
use App\Repositories\PersonaRepository;
use App\Team;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Carbon\Carbon;

/**
 * Class GrupoFamiliarController
 * @package App\Http\Controllers\API
 */
class GrupoFamiliarAPIController extends AppBaseController
{
    /** @var  GrupoFamiliarRepository */
    private $grupoFamiliarRepository;
    private $agenteRepository;
    private $personaRepository;
    private $colleccionDependenciasIds;

    public function __construct(GrupoFamiliarRepository $grupoFamiliarRepo, AgenteRepository $agenteRepo, PersonaRepository $personaRepo)
    {
        $this->grupoFamiliarRepository = $grupoFamiliarRepo;
        $this->agenteRepository = $agenteRepo;
        $this->personaRepository = $personaRepo;
    }

    /**
     * Display a listing of the GrupoFamiliar.
     * GET|HEAD /grupoFamiliars
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->grupoFamiliarRepository->pushCriteria(new RequestCriteria($request));
        $this->grupoFamiliarRepository->pushCriteria(new LimitOffsetCriteria($request));
        $grupoFamiliars = $this->grupoFamiliarRepository->all();

        return $this->sendResponse($grupoFamiliars->toArray(), 'Grupo Familiars retrieved successfully');
    }

    /**
     * Store a newly created GrupoFamiliar in storage.
     * POST /grupoFamiliars
     *
     * @param CreateGrupoFamiliarAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateGrupoFamiliarAPIRequest $request)
    {

        $input = $request->all();

        $grupoFamiliar = $this->grupoFamiliarRepository->create($input);

        return $this->sendResponse($grupoFamiliar->toArray(), 'Grupo Familiar saved successfully');
    }

    /**
     * Display the specified GrupoFamiliar.
     * GET|HEAD /grupoFamiliars/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var GrupoFamiliar $grupoFamiliar */
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            return $this->sendError('Grupo Familiar not found');
        }

        return $this->sendResponse($grupoFamiliar->toArray(), 'Grupo Familiar retrieved successfully');
    }

    /**
     * Update the specified GrupoFamiliar in storage.
     * PUT/PATCH /grupoFamiliars/{id}
     *
     * @param  int $id
     * @param UpdateGrupoFamiliarAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGrupoFamiliarAPIRequest $request)
    {
        $input = $request->all();

        /** @var GrupoFamiliar $grupoFamiliar */
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            return $this->sendError('Grupo Familiar not found');
        }

        $grupoFamiliar = $this->grupoFamiliarRepository->update($input, $id);

        return $this->sendResponse($grupoFamiliar->toArray(), 'GrupoFamiliar updated successfully');
    }

    /**
     * Remove the specified GrupoFamiliar from storage.
     * DELETE /grupoFamiliars/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var GrupoFamiliar $grupoFamiliar */
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            return $this->sendError('Grupo Familiar not found');
        }
        GrupoFamiliarPersona::where('idgrupoFamiliar', '=', $id)->delete();
        $grupoFamiliar->delete();

        return $this->sendResponse($id, 'Grupo Familiar deleted successfully');
    }

    /**
     * GET grupofamiliar/{logueado}/agente/{documento}
     *
     * @param $logueado id del agente
     * @param $documento documento del agente
     * @return mixed
     */
    public function getAgente($logueado, $documento)
    {

        //todo solo los agentes del hospital


        $Agente = Agente::where('agente.documento', '=', $documento)
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })->join('dependencia', 'puesto.iddependencia', '=', 'dependencia.iddependencia')
            ->join('tipo_planta', 'puesto.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_nivel', 'puesto.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_funcion', 'puesto.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->leftJoin('horario_puesto_historico', 'horario_puesto_historico.idpuesto', 'puesto.idpuesto')
            ->join('tipo_horario', 'horario_puesto_historico.idtipo_horario', 'tipo_horario.idtipo_horario')
            ->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
            ->select([
                'dependencia.iddependencia as iddependencia',
                'puesto.idpuesto as idpuesto',
                'dependencia.dependencia as servicio',
                'dependencia.codigorrhh as codigo',
                'tipo_funcion.tipofuncion as funcion',
                'tipo_nivel.tiponivel as nivel',
                'tipo_planta.tipoplanta as planta',
                'agente.idagente as idagente',
                'agente.documento as documento',
                'agente.nombre as nombre',
                'agente.apellido as apellido',
                'agente.fnacimiento as fnacimiento',
                'horario_puesto_historico.dias_guardia as dias_guardia',
                'horario_puesto_historico.hora_desde as hora_desde',
                'horario_puesto_historico.hora_hasta as hora_hasta',
                'tipo_horario.tipohorario as tipohorario'
            ])
            ->get();
        $Agente->each(function ($item, $key) {
            $item['apellido_nombre'] = $item['apellido'] . " " . $item['nombre'];
            $item['codigo_nombre'] = $item['codigo'] . " - " . $item['servicio'];
            $item['efector'] = Dependencia::find($item['iddependencia'])->getPadre();
        });

        if (empty($Agente)) {
            return $this->sendError('Agente no encontrado');
        }

        return $this->sendResponse($Agente->toArray(), 'Agente retrieved successfully');
    }

    /**
     * GET agente/hijos
     * Tarjeta Agente Busqueda Avanzada
     * @return mixed
     */
    public function getAgentesHijos(Request $request)
    {
        $documento = $request->query('documento');
        $nombre = strtoupper($request->query('nombre'));
        $apellido = strtoupper($request->query('apellido'));
        $funcion = $request->query('funcion');
        $IdDependencia = $request->query('dependencia');
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $request->user()->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        $user = User::where('idusuario', '=', $request->user()->idusuario)
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


        // Obtener todas las dependencias hijas en un array de ids, luego a cada una aplicar getIdsDescendencia y obtener un solo array con todos los ids de dependencias
        // $DependenciaPadre = Dependencia::find($IdDependencia)->getPadre();
        // $IdsDependenciasHijas = Dependencia::where('dependencia', '=', $DependenciaPadre)->hijas($IdDependencia)->pluck('iddependencia');
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');

        $Agentes = Agente::where(DB::raw('cast(agente.documento as text)'), 'like', $documento . '%')
            ->where('agente.nombre', 'like', '%' . $nombre . '%')
            ->where('agente.apellido', 'like', '%' . $apellido . '%')
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
            ->select([
                'p.iddependencia as iddependencia',
                'p.idpuesto as idpuesto',
                'tipo_funcion.tipofuncion as funcion',
                'tipo_planta.tipoplanta as planta',
                'agente.idagente as idagente',
                'agente.documento as documento',
                'agente.nombre as nombre',
                'agente.apellido as apellido'
            ])
            ->selectRaw("concat(apellido,' ',nombre) as apellido_nombre");
        if (
            // $user->display_name === 'Dpto./Oficina Personal Hospitales' ||
            // $user->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios' ||
            // $user->display_name === 'Jefe Personal de Areas Operativas Con Carga De RI' ||
            // $user->display_name === 'Equipos RRHH' ||
            $user->display_name === 'Consulta para Directores' ||
            $user->display_name === 'Gestion de Capital Humano' ||
            $user->display_name === 'Salud Ocupacional' ||
            $user->display_name === 'Gerencia' ||
            $user->display_name === 'Departamento Seleccion' ||
            $user->display_name === 'Departamento Planificacion' ||
            $user->display_name === 'Jefe Dpto Seleccion' ||
            $user->display_name === 'Administracion y Despacho' ||
            $user->display_name === 'Juridico' ||
            $user->display_name === 'Jefe Dpto Planificacion' ||
            $tienePermisoVerTodo
        ) {
            // $Agentes = $Agentes->whereIn('p.iddependencia', $IdsDependenciasHijas);
        } else {
            $Agentes = $Agentes->whereIn('p.iddependencia', $iddependencias);
        }

        if ($funcion > 0) {
            $Agentes = $Agentes->where('tipo_funcion.idtipo_funcion', '=', $funcion);
        }
        $Agentes = $Agentes->get();

        if (empty($Agentes)) {
            // $TodosAgentes = Agente::where(DB::raw('cast(agente.documento as text)'), 'like', $documento . '%')
            // ->where('agente.nombre', 'like', '%' . $nombre . '%')
            // ->where('agente.apellido', 'like', '%' . $apellido . '%')
            // ->join('puesto as p', 'p.idagente', 'agente.idagente')
            // ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            // ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            // ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            // ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
            // ->select([
            //     'p.iddependencia as iddependencia',
            //     'p.idpuesto as idpuesto',
            //     'tipo_funcion.tipofuncion as funcion',
            //     'tipo_planta.tipoplanta as planta',
            //     'agente.idagente as idagente',
            //     'agente.documento as documento',
            //     'agente.nombre as nombre',
            //     'agente.apellido as apellido'
            // ])
            // ->selectRaw("concat(apellido,' ',nombre) as apellido_nombre")->get();
            # Comparo entre si hay un agente entre todos y si es asi mandar el mensaje de agente encontrado pero no puede trabajar con el
            return $this->sendError('Agente no encontrado');
        }
        return $this->sendResponse($Agentes, 'Agente retrieved successfully');
    }

    /**
     * GET agente/rapido
     * Tarjeta Agente
     * @return mixed
     */
    public function getAgentesRapido(Request $request)
    {
        $documento = $request->query('documento');
        $iddependencia = $request->query('dependencia');
        $userRole = User::where('idusuario', '=', $request->user()->idusuario)
            ->join('role_user', function ($join) {
                $join->on('role_user.user_id', '=', 'usuario.idusuario');
            })->where('role_user.team_id', '=', 5)
            ->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })
            ->select('roles.display_name as display_name', 'roles.name')
            ->first();

        // Si no se encuentra un registro con team_id = 5, buscar con cualquier otro team_id
        if (!$userRole) {
            $userRole = User::where('idusuario', '=', $request->user()->idusuario)
                ->join('role_user', function ($join) {
                    $join->on('role_user.user_id', '=', 'usuario.idusuario');
                })
                ->join('roles', function ($join) {
                    $join->on('roles.id', '=', 'role_user.role_id');
                })
                ->select('roles.display_name as display_name', 'roles.name')
                ->first();
        }

        $user = $request->user();
        $team = Team::where('name', 'licencias')->first();
        $tienePermisoVerTodo = $user->rolePorModulo(5)->hasPermissions($team, 'ver-efectoresTodos');
        $user['display_name'] = $userRole->display_name;
        $user['name'] = $userRole->name;

        // Calling getDependenciasVisible and extracting dependenciasHijas
        // $response = $user->getDependenciasVisible();
        // $responseContent = json_decode($response->getContent(), true);
        // $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $response = $user->getDependenciasVisible();
        $responseContent = json_decode($response->getContent(), true);
        $IdsDependenciasHijas = $responseContent['dependenciasHijas'] ?? [];
        $iddependencias = array_column($IdsDependenciasHijas, 'iddependencia');
        // $DependenciaPadre = Dependencia::find($iddependencia)->getPadre();
        // $IdsDependenciasHijas = Dependencia::where('dependencia', '=', $DependenciaPadre)->hijas($iddependencia)->pluck('iddependencia');
        $Agentes = Agente::where('agente.documento', '=', $documento)
            ->join('puesto as p', 'p.idagente', 'agente.idagente')
            ->join(DB::raw('(select max(idpuesto) as idpuesto from puesto where fhasta IS NULL group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
            ->join('dependencia as d', 'p.iddependencia', '=', 'd.iddependencia')
            ->join('tipo_planta', 'p.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_nivel', 'p.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_funcion', 'p.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->whereIn('p.idtipo_planta', [1, 2, 4, 5, 6])
            ->select([
                'd.iddependencia as iddependencia',
                'p.idpuesto as idpuesto',
                'p.fdesde as fdesde',
                'p.fhasta as fhasta',
                'd.dependencia as servicio',
                'd.codigorrhh as codigo',
                'tipo_funcion.tipofuncion as funcion',
                'tipo_nivel.tiponivel as nivel',
                'tipo_planta.tipoplanta as planta',
                'agente.idagente as idagente',
                'agente.documento as documento',
                'agente.nombre as nombre',
                'agente.apellido as apellido',
                'agente.fnacimiento as fnacimiento',
                'agente.antiguedad as antiguedad',
                'agente.idtipo_sexo as idtipo_sexo',
            ])
            ->selectRaw("concat(apellido,' ',nombre) as apellido_nombre, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre");

        if (
            // $user->display_name === 'Dpto./Oficina Personal Hospitales' ||
            // $user->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios' ||
            // $user->display_name === 'Jefe Personal de Areas Operativas Con Carga De RI' ||
            // $user->display_name === 'Equipos RRHH' ||
            $user->display_name === 'Consulta para Directores' ||
            $user->display_name === 'Gestion de Capital Humano' ||
            $user->display_name === 'Salud Ocupacional' ||
            $user->display_name === 'Gerencia' ||
            $user->display_name === 'Departamento Seleccion' ||
            $user->display_name === 'Departamento Planificacion' ||
            $user->display_name === 'Jefe Dpto Seleccion' ||
            $user->display_name === 'Administracion y Despacho' ||
            $user->display_name === 'Juridico' ||
            $user->display_name === 'Jefe Dpto Planificacion'
            || $tienePermisoVerTodo
        ) {
        } else {
            $Agentes = $Agentes->whereIn('d.iddependencia', $iddependencias);
        }

        $Agentes = $Agentes->first();

        if (!empty($Agentes)) {
            $HorarioPuesto = HorarioPuestoHistorico::where('idpuesto', '=', $Agentes['idpuesto'])
                ->join('tipo_horario', 'horario_puesto_historico.idtipo_horario', 'tipo_horario.idtipo_horario')
                ->get();
            if ($HorarioPuesto->isEmpty()) {

                $HorarioPuesto = HorarioPuesto::where('puesto_id', '=', $Agentes['idpuesto'])
                    ->join('tipo_dia', 'horario_puesto.idtipo_dia', 'tipo_dia.idtipo_dia')
                    ->get();
            }
            $Agentes['efector'] = Dependencia::find($Agentes['iddependencia'])->getPadre();


            return $this->sendResponse([$Agentes, $HorarioPuesto], 'Agente retrieved successfully');
        }


        if (empty($Agentes)) {
            $Agentes = Agente::where('agente.documento', '=', $documento)->first();
            if (!empty($Agentes)) {
                return $this->sendError('Agente encontrado pero no esta habilitado para consultarlo');
            }
            return $this->sendError('Agente no encontrado');
        }
    }



    /**
     * GET agente/sancion/{documento}/{hijos}
     *
     * @param $documento
     * @param $hijos
     * @return mixed
     */
    public function getAgentesHijosSancion(Request $request, $documento)
    {
        //TODO: agregar la busqueda del documento al agente porque no esta trayendo un solo agente
        $user = User::where('idusuario', '=', $request->user()->idusuario)
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

        $agenteLogueado = User::where('idusuario', '=', $request->user()->idusuario)
            ->join('public.agente', 'sistema.usuario.idagente', 'public.agente.idagente')
            ->select('public.agente.idagente as idagente')
            ->first();


        $IdDependencia = Agente::where('agente.idagente', '=', $agenteLogueado->idagente)
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente');
            })->pluck('puesto.iddependencia')->first();

        if ($user->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios') {
            $IdDependencia = Dependencia::where('dependencia', '=', 'DIRECCION GENERAL DE RED DE SERVICIOS')->pluck('iddependencia')->first();
        }
        $DependenciaReal = Dependencia::find($IdDependencia);
        $DependenciaPadre = Dependencia::find($IdDependencia)->getPadre();
        $IdsDependenciasHijas = Dependencia::where('dependencia', '=', $DependenciaPadre)->first()->getIdsDescendencia();

        //        $IdsDependenciasHijas = Dependencia::where('dependencia', '=', Dependencia::find($IdDependencia)
        //            ->getPadre())
        //            ->first()
        //            ->getIdsDescendencia();

        $Agente = Agente::where('agente.documento', '=', $documento)
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente');
            })->join('dependencia', 'puesto.iddependencia', '=', 'dependencia.iddependencia')
            ->join('tipo_planta', 'puesto.idtipo_planta', 'tipo_planta.idtipo_planta')
            ->join('tipo_nivel', 'puesto.idtipo_nivel', 'tipo_nivel.idtipo_nivel')
            ->join('tipo_funcion', 'puesto.idtipo_funcion', 'tipo_funcion.idtipo_funcion')
            ->leftJoin('horario_puesto', 'horario_puesto.idpuesto', 'puesto.idpuesto')
            ->leftJoin('tipo_horario', 'horario_puesto.idtipo_horario', 'tipo_horario.idtipo_horario')
            ->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
            ->select([
                'dependencia.iddependencia as iddependencia',
                'puesto.idpuesto as idpuesto',
                'dependencia.dependencia as servicio',
                'dependencia.codigorrhh as codigo',
                'tipo_funcion.tipofuncion as funcion',
                'tipo_nivel.tiponivel as nivel',
                'tipo_planta.tipoplanta as planta',
                'agente.idagente as idagente',
                'agente.documento as documento',
                'agente.nombre as nombre',
                'agente.apellido as apellido',
                'agente.fnacimiento as fnacimiento',
                'agente.antiguedad as antiguedad',
                'horario_puesto.dias_guardia as dias_guardia',
                'horario_puesto.hora_desde as hora_desde',
                'horario_puesto.hora_hasta as hora_hasta',
                'tipo_horario.tipohorario as tipohorario'
            ]);

        if (
            $user->display_name === 'Dpto./Oficina Personal Hospitales' ||
            $user->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios' ||
            $user->display_name === 'Jefe Personal de Areas Operativas Con Carga De RI' ||
            $user->display_name === 'Equipos RRHH' ||
            $user->display_name === 'Consulta para Directores'
        ) {
            $Agente->whereIn('dependencia.iddependencia', $IdsDependenciasHijas);
        }

        $Agente = $Agente->first();


        if (empty($Agente)) {
            return $this->sendError('Agente no encontrado');
        }
        $Agente['apellido_nombre'] = $Agente['apellido'] . " " . $Agente['nombre'];
        $Agente['codigo_nombre'] = $Agente['codigo'] . " - " . $Agente['servicio'];
        $Agente['efector'] = Dependencia::find($Agente['iddependencia'])->getPadre();


        return $this->sendResponse($Agente, 'Agente retrieved successfully');
    }




    public function getDependencias($id)
    {
        $this->colleccionDependenciasIds = array();
        $Dependencias = DependenciaRelacion::where('iddependenciapadre', '=', $id)->with('dependenciasHijas')->get();
        array_push($this->colleccionDependenciasIds, (int) $id);
        $Dependencias = $Dependencias->toArray();
        array_walk_recursive($Dependencias, function ($value, $key) {
            if ($key == 'iddependenciahija') {
                array_push($this->colleccionDependenciasIds, $value);
            }
        });
        sort($this->colleccionDependenciasIds);
        return $this->sendResponse($this->colleccionDependenciasIds, 'Dependencia retrieved successfully');
    }
    public function getConsultaDependencias($id)
    {
        $this->colleccionDependenciasIds = array();
        $Dependencias = DependenciaRelacion::where('iddependenciapadre', '=', $id)->with('dependenciasHijas')->get();

        array_push($this->colleccionDependenciasIds, (int) $id);
        $Dependencias = $Dependencias->toArray();
        array_walk_recursive($Dependencias, function ($value, $key) {
            if ($key == 'iddependenciahija') {
                array_push($this->colleccionDependenciasIds, $value);
            }
        });
        sort($this->colleccionDependenciasIds);
        return $this->colleccionDependenciasIds;
    }
    public function getExpedientes($idagente)
    {

        $Expedientes = DB::table('grupo_familiares')
            ->where('grupo_familiares.idagente', '=', $idagente)
            ->where('deleted_at', '=', null)
            ->get();

        return $this->sendResponse($Expedientes->toArray(), 'Expedientes retrieved successfully');
    }
    public function getPersonasExpedientes($idexpediente)
    {

        $Personas = DB::table('grupo_familiar_personas')
            ->join('personas', function ($join) {
                $join->on('grupo_familiar_personas.idpersona', '=', 'personas.idpersona');
            })
            ->where('grupo_familiar_personas.idgrupoFamiliar', '=', $idexpediente)
            ->where('grupo_familiar_personas.deleted_at', '=', null)
            ->get();

        return $this->sendResponse($Personas->toArray(), 'Personas retrieved successfully');
    }

    /**
     * Store a newly created GrupoFamiliar in storage.
     * POST /grupofamiliar/complete
     *
     * @param Request $request
     *
     * @return Response
     */
    public function storeComplete(Request $request)
    {
        $personas = $request->get('0');
        $agente = $request->get('1');
        //Obtengo ultimo expediente
        $ultimoExpediente = GrupoFamiliar::where('idagente', '=', $agente["idagente"])->orderBy('nExpediente', 'desc')->first();



        $fecha = getdate()["year"] + 1;

        //Actualiza el expediente activo
        $actualizarGrupos = GrupoFamiliar::where('idagente', '=', $agente["idagente"])
            ->where('activo', 'true')
            ->update(['activo' => 'false']);

        $grupoInsertado = GrupoFamiliar::create([
            'nExpediente' => (!empty($ultimoExpediente)) ? ($ultimoExpediente->nExpediente) + 1 : 1,
            'idagente' => $agente["idagente"],
            'aprobado' => true,
            'activo' => true,
            'vencimiento' => (new \DateTime)->setDate($fecha, 3, 31)
        ]);


        foreach ($personas as $persona) {

            if ($persona['idtipoParentesco'] != 0) {
                $tipoParentesco = TipoParentesco::find($persona['idtipoParentesco']);
            }
            if ($persona['idpersona'] != 0) {
                $personaInsertada = Persona::find($persona['idpersona'])->update([
                    'nombre' => strtoupper($persona['nombre']),
                    'apellido' => strtoupper($persona['apellido']),
                    'fecha_nacimiento' => new DateTime($persona['fecha_nacimiento']),
                    'discapacidad' => $persona['discapacidad']
                ]);
                GrupoFamiliarPersona::create([
                    'idtipoParentesco' => $tipoParentesco->idtipoParentesco,
                    'idgrupoFamiliar' => $grupoInsertado->idgrupoFamiliar,
                    'idpersona' => $persona['idpersona']
                ]);
            } else {
                $personaInsertada = Persona::create([
                    'documento' => $persona['documento'],
                    'nombre' => strtoupper($persona['nombre']),
                    'apellido' => strtoupper($persona['apellido']),
                    'fecha_nacimiento' => new DateTime($persona['fecha_nacimiento']),
                    'discapacidad' => $persona['discapacidad']
                ]);
                GrupoFamiliarPersona::create([
                    'idtipoParentesco' => $tipoParentesco->idtipoParentesco,
                    'idgrupoFamiliar' => $grupoInsertado->idgrupoFamiliar,
                    'idpersona' => $personaInsertada->idpersona
                ]);
            }
        }


        return $this->sendResponse($grupoInsertado->toArray(), 'Grupo Familiar saved successfully');
    }


    public function getAgenteUsuario($idusuario)
    {
        $agente = User::join('public.agente', 'usuario.idagente', '=', 'public.agente.idagente')
            ->where('usuario.idusuario', '=', $idusuario)
            ->first();
        return $this->sendResponse($agente->toArray(), 'Datos del usuario');
    }
}
