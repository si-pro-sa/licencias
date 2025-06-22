<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoAPIRequest;
use App\Http\Requests\API\UpdateCargoAPIRequest;
use App\Models\Cargo;
use App\Models\CargoCambioEstado;
use App\Repositories\CargoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Yajra\Datatables\Datatables;

/**
 * Class CargoController
 * @package App\Http\Controllers\API
 */

class CargoAPIController extends AppBaseController
{
    protected $tableClass = CargosAltaTable::class;
    /** @var  CargoRepository */
    private $cargoRepository;

    public function __construct(CargoRepository $cargoRepo)
    {
        $this->cargoRepository = $cargoRepo;
    }

    /**
     * Display a listing of the Cargo.
     * GET|HEAD /cargos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cargoVacantes = $this->cargoRepository->all();

        return $this->sendResponse($cargoVacantes->toArray(), 'Cargo Vacantes retrieved successfully');
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
        $response['template']->readPath = env('APP_URL') . $response['template']->readPath;
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
        $this->cargoRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoRepository->pushCriteria(new LimitOffsetCriteria($request));
//        relaciones que devuelve
        $this->cargoRepository->with('agenteReemplazante');
        $this->cargoRepository->with('agenteReemplazante.puestos');
        $this->cargoRepository->with('agenteReemplazante.puestos.dependencia');
        $this->cargoRepository->with('agenteReemplazante.puestos.tipoPlanta');
        $this->cargoRepository->with('agenteReemplazante.puestos.tipoFuncion');
        $this->cargoRepository->with('agenteReemplazante.puestos.tipoNivel');
        $this->cargoRepository->with('agenteReemplazado');
        $this->cargoRepository->with('agenteReemplazado.puestos');
        $this->cargoRepository->with('agenteReemplazado.puestos.dependencia');
        $this->cargoRepository->with('agenteReemplazado.puestos.tipoPlanta');
        $this->cargoRepository->with('agenteReemplazado.puestos.tipoFuncion');
        $this->cargoRepository->with('agenteReemplazado.puestos.tipoNivel');
        $this->cargoRepository->with('puestoAgenteReemplazante');
        $this->cargoRepository->with('puestoAgenteReemplazante.dependencia');
        $this->cargoRepository->with('puestoAgenteReemplazado');
        $this->cargoRepository->with('dependencia');
        $this->cargoRepository->with('tipoCese');
        $this->cargoRepository->with('cargoTipoFuncion');
//        scope desde repositorio
        if ($request->get('searchParam')['dependencia'] != null) {
            $this->cargoRepository->dependencia($request->get('searchParam')['dependencia']);
        }
        if ($request->get('searchParam')['tipocargo'] != null) {
            $this->cargoRepository->tipoCargo($request->get('searchParam')['tipocargo']);
        }
        if ($request->get('searchParam')['tipofuncion'] != null) {
            $this->cargoRepository->tipoFuncion($request->get('searchParam')['tipofuncion']);
        }
        if ($request->get('searchParam')['agente_reemplazado'] != null) {
            $this->cargoRepository->dependencia($request->get('searchParam')['agente_reemplazado']);
        }

        $cargoVacantes = $this->cargoRepository->paginate(15);

        return $this->sendResponse($cargoVacantes->toArray(), 'Cargos retrieved successfully');
    }

    /**
     * Store a newly created Cargo in storage.
     * POST /cargos
     *
     * @param CreateCargoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoAPIRequest $request)
    {
        $cargo = $this->cargoRepository->create($request->all());

        if ($cargo) {
            return $this->sendResponse($cargo, 'El cargo fue creado correctamente.');
        }
        return $this->sendError('Ocurrió un error al crear el cargo');
    }

    /**
     * Display the specified Cargo.
     * GET|HEAD /cargos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Cargo $cargoVacante */
        $cargoVacante = $this->cargoRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            return $this->sendError('Cargo Vacante not found');
        }

        return $this->sendResponse($cargoVacante->toArray(), 'Cargo Vacante retrieved successfully');
    }

    /**
     * Update the specified Cargo in storage.
     * PUT/PATCH /cargos/{id}
     *
     * @param  int $id
     * @param UpdateCargoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Cargo $cargoVacante */
        $cargoVacante = $this->cargoRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            return $this->sendError('Cargo Vacante not found');
        }

        $cargoVacante = $this->cargoRepository->update($input, $id);

        return $this->sendResponse($cargoVacante->toArray(), 'Cargo updated successfully');
    }

    /**
     * Remove the specified Cargo from storage.
     * DELETE /cargos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Cargo $cargoVacante */
        $cargoVacante = $this->cargoRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            return $this->sendError('Cargo Vacante not found');
        }

        $cargoVacante->delete();

        return $this->sendResponse($id, 'Cargo Vacante deleted successfully');
    }

    /**
    * Process datatables ajax request.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function getPendientes()
    {
        $model = CargoCambioEstado::where('idtipo_formulario', 1)
        ->with('cargo')
        ->with('cargoTipoVisado')
        ->with('cargoCambioEstadoObs')
        ->with('cargo.recomendacionCandidato')
        ->with('cargo.efector')
        ->with('cargo.servicio')
        ->with('cargo.tipoFuncion')
        ->with('cargo.tipoEspecialidad')
        ->with('cargo.tipoNivel')
        ->with('cargo.tipoAgrupamiento')
        ->with('cargo.tipoCampania')
        ->with('cargo.titulo')
        ->with('cargo.tipoEspecialidad')
        ->with('cargo.cargoReemplazado');

        return \DataTables::eloquent($model)
                ->addColumn('cargo.recomendacionCandidato', function (CargoCambioEstado $cambioEstado) {
                    return $cambioEstado->cargoCambioEstadoObs->map(function ($observacion) {
                        return $observacion->cargoTipoObservacion->cargotipo_observacion;
                    })->implode('<br>');
                })
                ->toJson();
    }
}
