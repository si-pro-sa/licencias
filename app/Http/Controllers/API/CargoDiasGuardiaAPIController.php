<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoDiasGuardiaAPIRequest;
use App\Http\Requests\API\UpdateCargoDiasGuardiaAPIRequest;
use App\Models\CargoDiasGuardia;
use App\Repositories\CargoDiasGuardiaRepository;
use App\Tables\Builders\CargoDiasGuardiaTable;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CargoDiasGuardiaController
 * @package App\Http\Controllers\API
 */

class CargoDiasGuardiaAPIController extends AppBaseController
{
    use Datatable,Excel;
    protected $tableClass = CargoDiasGuardiaTable::class;
    /** @var  CargoDiasGuardiaRepository */
    private $cargoDiasGuardiaRepository;

    public function __construct(CargoDiasGuardiaRepository $cargoDiasGuardiaRepo)
    {
        $this->cargoDiasGuardiaRepository = $cargoDiasGuardiaRepo;
    }

    /**
     * Display a listing of the CargoDiasGuardia.
     * GET|HEAD /cargoDiasGuardias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoDiasGuardiaRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoDiasGuardiaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cargoDiasGuardias = $this->cargoDiasGuardiaRepository->all();

        return $this->sendResponse($cargoDiasGuardias->toArray(), 'Cargo Dias Guardias retrieved successfully');
    }

    /**
     * Display a listing of the Cargo.
     * POST|HEAD /cargos-table-init
     *
     * @return Response
     */
    public function init()
    {
        $response = (new $this->tableClass())
            ->init();
        $response['template']->readPath = env('APP_URL').$response['template']->readPath;
        $response['message'] = 'Se ha validado correctamente la configuración de la tabla';
        return $response;
    }

    /**
     * Display a listing of the Cargo.
     * POST|HEAD /cargos/tableData
     *
     * @param Request $request
     * @return Response
     */
    public function table(Request $request)
    {
        $input = $request->all();
        $this->cargoDiasGuardiaRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoDiasGuardiaRepository->pushCriteria(new LimitOffsetCriteria($request));
//        relaciones que devuelve
//        $this->$this->$this->cargoDiasGuardiaRepository->with('agenteReemplazante');
//        scope desde repositorio
        if($request->get('searchParam')['cargodiasguardia'] != null)
            $cargoTipoHorario = $this->cargoDiasGuardiaRepository->cargodiasguardia($request->get('searchParam')['cargodiasguardia']);

        $CargoDiasGuardia = $this->cargoDiasGuardiaRepository->paginate(15);

        return $this->sendResponse($CargoDiasGuardia->toArray(), 'Cargos retrieved successfully');
    }

    /**
     * Store a newly created CargoDiasGuardia in storage.
     * POST /cargoDiasGuardias
     *
     * @param CreateCargoDiasGuardiaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoDiasGuardiaAPIRequest $request)
    {
        $input = $request->all();

        $cargoDiasGuardia = $this->cargoDiasGuardiaRepository->create($input);

        return $this->sendResponse($cargoDiasGuardia->toArray(), 'Cargo Dias Guardia ha sido guardado correctamente');
    }

    /**
     * Display the specified CargoDiasGuardia.
     * GET|HEAD /cargoDiasGuardias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CargoDiasGuardia $cargoDiasGuardia */
        $cargoDiasGuardia = $this->cargoDiasGuardiaRepository->findWithoutFail($id);

        if (empty($cargoDiasGuardia)) {
            return $this->sendError('Cargo Dias Guardia no se encontró');
        }

        return $this->sendResponse($cargoDiasGuardia->toArray(), 'Cargo Dias Guardia retrieved successfully');
    }

    /**
     * Update the specified CargoDiasGuardia in storage.
     * PUT/PATCH /cargoDiasGuardias/{id}
     *
     * @param  int $id
     * @param UpdateCargoDiasGuardiaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoDiasGuardiaAPIRequest $request)
    {
        $input = $request->all();

        /** @var CargoDiasGuardia $cargoDiasGuardia */
        $cargoDiasGuardia = $this->cargoDiasGuardiaRepository->findWithoutFail($id);

        if (empty($cargoDiasGuardia)) {
            return $this->sendError('Cargo Dias Guardia not found');
        }

        $cargoDiasGuardia = $this->cargoDiasGuardiaRepository->update($input, $id);

        return $this->sendResponse($cargoDiasGuardia->toArray(), 'CargoDiasGuardia ha sido actualizado correctamente');
    }

    /**
     * Remove the specified CargoDiasGuardia from storage.
     * DELETE /cargoDiasGuardias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CargoDiasGuardia $cargoDiasGuardia */
        $cargoDiasGuardia = $this->cargoDiasGuardiaRepository->findWithoutFail($id);

        if (empty($cargoDiasGuardia)) {
            return $this->sendError('Cargo Dias Guardia no se encontró');
        }

        $cargoDiasGuardia->delete();

        return $this->sendResponse($id, 'Cargo Dias Guardia ha sido eliminado correctamente');
    }
}
