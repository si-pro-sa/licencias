<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAntiguedadAPIRequest;
use App\Http\Requests\API\UpdateAntiguedadAPIRequest;
use App\Models\Agente;
use App\Models\Antiguedad;
use App\Models\Dependencia;
use App\Models\LicenciaSaldos;
use App\Models\Puesto;
use App\Repositories\AntiguedadRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AgenteImport;
use App\Imports\AgenteImportNoVigente;
use DB;

/**
 * Class AntiguedadController
 * @package App\Http\Controllers\API
 */
class AntiguedadAPIController extends AppBaseController
{
    /** @var  AntiguedadRepository */
    private $antiguedadRepository;

    public function __construct(AntiguedadRepository $antiguedadRepo)
    {
        $this->antiguedadRepository = $antiguedadRepo;
    }

    /**
     * Display a listing of the Antiguedad.
     * GET|HEAD /antiguedads
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $antiguedads = $this->antiguedadRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($antiguedads->toArray(), 'Antiguedads retrieved successfully');
    }

    /**
     * Store a newly created Antiguedad in storage.
     * POST /antiguedads
     *
     * @param CreateAntiguedadAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAntiguedadAPIRequest $request)
    {
        $input = $request->all();

        $antiguedad = $this->antiguedadRepository->create($input);

        return $this->sendResponse($antiguedad->toArray(), 'Antiguedad saved successfully');
    }

    /**
     * Display the specified Antiguedad.
     * GET|HEAD /antiguedads/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Antiguedad $antiguedad */
        $antiguedad = $this->antiguedadRepository->find($id);

        if (empty($antiguedad)) {
            return $this->sendError('Antiguedad not found');
        }

        return $this->sendResponse($antiguedad->toArray(), 'Antiguedad retrieved successfully');
    }

    /**
     * Update the specified Antiguedad in storage.
     * PUT/PATCH /antiguedads/{id}
     *
     * @param int $id
     * @param UpdateAntiguedadAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAntiguedadAPIRequest $request)
    {
        $input = $request->all();

        /** @var Antiguedad $antiguedad */
        $antiguedad = $this->antiguedadRepository->find($id);

        if (empty($antiguedad)) {
            return $this->sendError('Antiguedad not found');
        }

        $antiguedad = $this->antiguedadRepository->update($input, $id);

        return $this->sendResponse($antiguedad->toArray(), 'Antiguedad updated successfully');
    }

    /**
     * Remove the specified Antiguedad from storage.
     * DELETE /antiguedads/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Antiguedad $antiguedad */
        $antiguedad = $this->antiguedadRepository->find($id);

        if (empty($antiguedad)) {
            return $this->sendError('Antiguedad not found');
        }

        $antiguedad->delete();

        return $this->sendResponse($id, 'Antiguedad deleted successfully');
    }

    /**
     * Display a listing of antifuedades. Este es para la pantalla de computo
     * GET|HEAD /antiguedades
     *
     * @param $idagente
     * @return Response
     */
    public function getAntiguedadesPorAgente($idagente)
    {
        $antiguedades = Antiguedad::where('antiguedades.idagente', '=', $idagente)
            ->join('sistema.usuario', 'antiguedades.idusuario', 'sistema.usuario.idusuario')
            ->where('antiguedades.deleted_at', '=', null)
            ->select(
                'antiguedades.idantiguedad as idantiguedad',
                'antiguedades.año as año',
                'antiguedades.pedido as pedido',
                'antiguedades.disponible as disponible',
                'antiguedades.vigente as vigente',
                'antiguedades.created_at as created_at',
                'antiguedades.updated_at as updated_at',
                'sistema.usuario.nombreusuario as nombreusuario'
            )
            ->get();

        $saldos = DB::table('licencia_saldos')
            ->where('licencia_saldos.idagente', '=', $idagente)
            ->whereIn('licencia_saldos.idtipoLicencia', [16, 17, 25, 27])
            ->where('licencia_saldos.deleted_at', '=', null)
            ->get();


        if (!empty($saldos) && !empty($antiguedades)) {
            foreach ($antiguedades as $antiguedad) {
                foreach ($saldos as $saldo) {
                    if ($saldo->año == $antiguedad->año) {
                        $antiguedad->pedido += $saldo->dias;
                    }
                }
            }
        }
        return $this->sendResponse($antiguedades->toArray(), 'antiguedades retrieved successfully');
    }

    /**
     * Consulta saldo Lao
     * GET|HEAD /antiguedades
     *
     * @param $idagente
     * @return Response
     */
    public function getAntiguedadesConsulta(Request $request)
    {

        $idDependencia = $request->query('iddependencia');
        $last = $request->query('fecha_desde');
        $to = $request->query('fecha_hasta');
        $to = Carbon::parse($to)->format('Y-m-d');
        $last = Carbon::parse($last)->format('Y-m-d');
        $user = $request->user();

        $idagente = $user->idagente;
        $user = User::where('idusuario', '=', $user->idusuario)
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

        /*
                $IdDependencia = Puesto::where('puesto.fhasta', '=', null)
                    ->where('puesto.idagente', '=', $idagente)
                    ->pluck('puesto.iddependencia')
                    ->first();

                $Puesto = Puesto::where('puesto.fhasta', '=', null)
                    ->where('puesto.idagente', '=', $idagente)->first();

                if ($user->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios') {
                    $IdDependencia = Dependencia::where('dependencia', '=', 'DIRECCION GENERAL DE RED DE SERVICIOS')->pluck('iddependencia')->first();
                }
                $DependenciaPadre = Dependencia::find($IdDependencia)->getPadre();
        */
        $Dependencia = Dependencia::where('iddependencia', '=', $idDependencia)->first();
        $IdsDependenciasHijas = $Dependencia->getIdsDescendencia();


        $IdAgentes = Agente::join('puesto', function ($join) {
            $join->on('agente.idagente', '=', 'puesto.idagente')
                ->where('puesto.fhasta', '=', null);
        })->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
            ->whereIn('puesto.iddependencia', $IdsDependenciasHijas)
            ->pluck('agente.idagente')
            ->toArray();


        $saldos = LicenciaSaldos::select(['idagente', 'año', DB::raw('sum(dias) as pedido')])
            ->groupBy(['idagente', 'año'])
            ->whereIn('licencia_saldos.idagente', $IdAgentes)
            ->whereIn('idtipoLicencia', [16, 17, 25, 27])
            //->whereBetween('created_at', [$last, $to])
            ->where('deleted_at', '=', null);
        //->havingRaw('SUM(dias) > ?', [0]);

        $antiguedades = Antiguedad::whereIn('antiguedades.idagente', $IdAgentes)
            //->where('antiguedades.vigente', '=', true)
            ->leftjoinSub($saldos, 'lic_saldos', function ($join) {
                $join->on('antiguedades.idagente', '=', 'lic_saldos.idagente');
                $join->on('antiguedades.año', '=', 'lic_saldos.año');
            })
            ->join('public.agente', 'antiguedades.idagente', 'public.agente.idagente')
            ->join('puesto', function ($join) {
                $join->on('agente.idagente', '=', 'puesto.idagente')
                    ->where('puesto.fhasta', '=', null);
            })
            ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
            ->where('antiguedades.deleted_at', '=', null)
            ->select(
                'dependencia.dependencia as servicio',
                'dependencia.codigorrhh as codigo',
                'agente.documento as documento',
                'antiguedades.idagente as idagente',
                'antiguedades.idantiguedad as idantiguedad',
                'antiguedades.año as año',
                'lic_saldos.pedido as pedido',
                'antiguedades.disponible as disponible',
                'antiguedades.vigente as vigente',
                'antiguedades.created_at as created_at',
                'puesto.iddependencia as iddependencia',
                'agente.nombre as nombre',
                'agente.apellido as apellido',
                'agente.antiguedad as antiguedad',
                'antiguedades.vigente as vigente'
            )
            ->get();

        $antiguedades->each(function ($item, $key) use ($Dependencia) {
            $item['apellido_nombre'] = $item['apellido'] . " " . $item['nombre'];
            $item['codigo_nombre'] = $item['codigo'] . " - " . $item['servicio'];
            $item['pedido'] = $item['pedido'] == null ? 0 : $item['pedido'];
            $item['saldo'] = intval(trim($item['disponible'])) - intval(trim($item['pedido']));
            $item['efector'] = $Dependencia->dependencia;
        });

        return $this->sendResponse($antiguedades->toArray(), 'antiguedades retrieved successfully');
    }

    /**
     * Este es para la LicenciaCreate
     * GET|HEAD /antiguedades/licencia/{idagente}/{idlicencia}
     * @param $idagente
     * @param $idlicencia
     * @return Response
     */
    public function getAntiguedadesMenosLicencia($idagente, $idlicencia)
    {
        //TODO Modificar la tabla antiguedades para que contenga la variable tipolicencia
        //pondremos todo lo anual aqui y por el grupo
        $antiguedades = Antiguedad::where('antiguedades.idagente', '=', $idagente)
            ->where('antiguedades.deleted_at', '=', null)
            ->where('antiguedades.vigente', '=', true)
            ->select('idantiguedad', 'año', 'pedido', 'disponible', 'vigente', 'created_at')
            ->get();

        $saldos = LicenciaSaldos::where('licencia_saldos.idagente', '=', $idagente)
            ->whereIn('licencia_saldos.idtipoLicencia', [16, 17, 25, 27])
            ->where('licencia_saldos.deleted_at', '=', null);
        if ($idlicencia != 0) {
            $saldos = $saldos->where('licencia_saldos.idlicencia', '!=', $idlicencia);
        }
        $saldos = $saldos->get();

        if (!empty($saldos) && !empty($antiguedades)) {
            foreach ($antiguedades as $antiguedad) {
                foreach ($saldos as $saldo) {
                    if ($saldo->año == $antiguedad->año) {
                        $antiguedad->pedido += $saldo->dias;
                    }
                }
                $antiguedad['saldo'] = $antiguedad->disponible - $antiguedad->pedido;
            }
        }
        return $this->sendResponse($antiguedades->toArray(), 'antiguedades retrieved successfully');
    }

    /**
     * Este es para la LicenciaCreate
     * PUT|HEAD /antiguedades/vigente/{iddependencia}
     * @param $iddependencia
     * @return Response
     */
    public function updateVigente(Request $request)
    {

        $iddependencia = $request->iddependencia;
        $vigente = $request->vigente;

        $Dependencia = Dependencia::where('iddependencia', '=', $iddependencia)->first();
        $IdsDependenciasHijas = $Dependencia->getIdsDescendencia();


        $IdAgentes = Agente::join('puesto', function ($join) {
            $join->on('agente.idagente', '=', 'puesto.idagente')
                ->where('puesto.fhasta', '=', null);
        })->whereIn('puesto.idtipo_planta', [1, 2, 4, 5, 6])
            ->whereIn('puesto.iddependencia', $IdsDependenciasHijas)
            ->pluck('agente.idagente')
            ->toArray();
        if (!empty($IdAgentes)) {
            $antiguedades = Antiguedad::whereIn('idagente', $IdAgentes)
                ->update(['vigente' => $vigente]);
        }
        if (empty($antiguedades)) {
            return $this->sendError("Antiguedades no encontradas");
        }
        return $this->sendResponse($antiguedades, 'Antiguedades actualizadas satisfactoriamente');
    }

    /**
     * Importar archivo excel con las antiguedades de los agentes
     */
    public function importar(Request $request)
    {
        if ($request->vigente == 'true') {
            Excel::import(new AgenteImport, $request->file('file'));
        } else {
            Excel::import(new AgenteImportNoVigente, $request->file('file'));
        }



        return $this->sendResponse([], 'Antiguedad actualizada');
    }
}
