<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSancionAPIRequest;
use App\Http\Requests\API\UpdateSancionAPIRequest;
use App\Imports\SancionImport;
use App\Models\Agente;
use App\Models\Dependencia;
use App\Models\Puesto;
use App\Models\Sancion;
use App\Repositories\SancionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Maatwebsite\Excel\Facades\Excel;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

/**
 * Class SancionController
 * @package App\Http\Controllers\API
 */

class SancionAPIController extends AppBaseController
{
    /** @var  SancionRepository */
    private $sancionRepository;

    public function __construct(SancionRepository $sancionRepo)
    {
        $this->sancionRepository = $sancionRepo;
    }

    /**
     * Display a listing of the Sancion.
     * GET|HEAD /sancions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->sancionRepository->pushCriteria(new RequestCriteria($request));
        $this->sancionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $sancions = $this->sancionRepository->all();

        return $this->sendResponse($sancions->toArray(), 'Sancions retrieved successfully');
    }

    /**
     * Display a listing of the Sancion.
     * GET|HEAD /sancions
     *
     * @param $idagente
     * @return Response
     */
    public function getSancionesPorAgente($idagente)
    {
        $sanciones = Sancion::where('sanciones.idagente', '=', $idagente)
            ->join('sistema.usuario', 'sanciones.idusuario', 'sistema.usuario.idusuario')
            ->select(
                'sanciones.idsancion as idsancion',
                'sanciones.resolucion as resolucion',
                'sanciones.reseña as reseña',
                'sanciones.acuerdo as acuerdo',
                'sanciones.expediente as expediente',
                'sanciones.conclusion as conclusion',
                'sanciones.fecha_inicio as fecha_inicio',
                'sanciones.fecha_final as fecha_final',
                'sanciones.created_at as created_at',
                'sistema.usuario.nombreusuario as nombreusuario'
            )
            ->get();
        return $this->sendResponse($sanciones->toArray(), 'sanciones retrieved successfully');
    }
    /**
     * Display a listing of the Sancion.
     * GET|HEAD /sancions
     *
     * @param $idagente
     * @return Response
     */
    public function existSanciones($idagente)
    {
        // Obtiene la fecha de hace dos años
        $twoYearsAgo = Carbon::now()->subYears(2);

        // Filtra las sanciones del agente que no tengan más de dos años
        $sanciones = Sancion::where('sanciones.idagente', '=', $idagente)
            ->where('created_at', '>=', $twoYearsAgo)
            ->first();

        if (!empty($sanciones)) {
            $sanciones = true;
            return $this->sendResponse($sanciones, 'Sanciones traídas con satisfacción');
        }

        $sanciones = false;
        return $this->sendResponse($sanciones, 'No hay sanciones preexistentes');
    }
    /**
     * Store a newly created Sancion in storage.
     * POST /sancions
     *
     * @param CreateSancionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSancionAPIRequest $request)
    {
        $input = $request->all();

        $sancion = $this->sancionRepository->create($input);

        return $this->sendResponse($sancion->toArray(), 'Sancion saved successfully');
    }
    /**
     * Display the specified Sancion.
     * GET|HEAD /sancions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Sancion $sancion */
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            return $this->sendError('Sancion not found');
        }

        return $this->sendResponse($sancion->toArray(), 'Sancion retrieved successfully');
    }

    /**
     * Update the specified Sancion in storage.
     * PUT/PATCH /sancions/{id}
     *
     * @param  int $id
     * @param UpdateSancionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSancionAPIRequest $request)
    {
        $input = $request->all();


        /** @var Sancion $sancion */
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            return $this->sendError('Sancion not found');
        }

        $sancion = $this->sancionRepository->update($input, $id);

        return $this->sendResponse($sancion->toArray(), 'Sancion updated successfully');
    }

    /**
     * Remove the specified Sancion from storage.
     * DELETE /sancions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Sancion $sancion */
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            return $this->sendError('Sancion not found');
        }

        $sancion->delete();

        return $this->sendResponse($id, 'Sancion deleted successfully');
    }


    /**
     * Custom query by efector for Consulta Sanciones.
     * GET|HEAD /sanciones/customQuery
     *
     * @param Request
     * @return Response
     */
    public function customQuery(Request $request)
    {
        $idEfector = $request->query('iddependencia');
        $idSancion = $request->query('idsancion');
        $idAgente = $request->query('idagente');

        $sanciones = Sancion::join('sistema.usuario', 'sanciones.idusuario', 'sistema.usuario.idusuario')
            ->join('public.agente', 'sanciones.idagente', 'agente.idagente');


        if ($idEfector > 0) {
            $Dependencia = Dependencia::where('iddependencia', '=', $idEfector)->first();
            $IdsDependenciasHijas = $Dependencia->getIdsDescendencia();
            $IdAgentes = Agente::join('puesto as p', function ($join) {
                $join->on('agente.idagente', '=', 'p.idagente');
            })->join(DB::raw('(select max(idpuesto) as idpuesto from puesto group by puesto.idagente) as pp'), 'pp.idpuesto', 'p.idpuesto')
                ->whereIn('p.iddependencia', $IdsDependenciasHijas)
                ->pluck('agente.idagente')
                ->toArray();

            $sanciones = $sanciones->whereIn('sanciones.idagente', $IdAgentes);
        }
        if ($idAgente > 0) {

            $puesto = Puesto::where('idagente', '=', $idAgente)
                ->where(function (Builder $query) {
                    return $query->where('puesto.fhasta', '=', null)
                        ->orWhere('puesto.fhasta', '!=', null);
                })->orderBy('puesto.fhasta', 'desc')
                ->limit(1);

            $sanciones = $sanciones->leftJoinSub($puesto, 'p', function ($join) {
                $join->on('agente.idagente', '=', 'p.idagente');
            })->where('agente.idagente', $idAgente)
                ->join('dependencia as d', 'd.iddependencia', 'p.iddependencia')
                ->select(
                    'sanciones.idagente as idagente',
                    'sanciones.idsancion as idsancion',
                    'sanciones.resolucion as resolucion',
                    'sanciones.reseña as reseña',
                    'sanciones.acuerdo as acuerdo',
                    'sanciones.expediente as expediente',
                    'sanciones.conclusion as conclusion',
                    'sanciones.fecha_inicio as fecha_inicio',
                    'sanciones.fecha_final as fecha_final',
                    'sanciones.created_at as created_at',
                    'agente.documento as documento',
                    'agente.nombre as nombre',
                    'agente.apellido as apellido',
                    'sistema.usuario.nombreusuario as nombreusuario',
                    'd.codigorrhh as codigo',
                    'd.dependencia as dependencia',
                )
                ->selectRaw("concat(apellido,' ',nombre) as apellido_nombre, concat(d.codigorrhh,' - ',d.dependencia) as codigo_nombre");

            $sanciones = $sanciones->where('agente.idagente', $idAgente);
        }
        if ($idSancion > 0) {
            $sanciones = $sanciones->where('sanciones.idsancion', $idSancion);
        }


        $sanciones = $sanciones->get();
        return $this->sendResponse($sanciones->toArray(), 'sanciones retrieved successfully');
    }

    /**
     * Importar archivo excel con las antiguedades de los agentes
     */
    public function importar(Request $request)
    {

        Excel::import(new SancionImport, $request->file('file'));


        return $this->sendResponse([], 'Sanciones actualizada');
    }
}
