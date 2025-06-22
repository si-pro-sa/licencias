<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoTipoFuncionAPIRequest;
use App\Http\Requests\API\UpdateCargoTipoFuncionAPIRequest;
use App\Models\CargoTipoFuncion;
use App\Repositories\CargoTipoFuncionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CargoTipoFuncionController
 * @package App\Http\Controllers\API
 */

class CargoTipoFuncionAPIController extends AppBaseController
{
    /** @var  CargoTipoFuncionRepository */
    private $cargoTipoFuncionRepository;

    public function __construct(CargoTipoFuncionRepository $cargoTipoFuncionRepo)
    {
        $this->cargoTipoFuncionRepository = $cargoTipoFuncionRepo;
    }

    /**
     * Display a listing of the CargoTipoFuncion.
     * GET|HEAD /cargoTipoFuncions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoFuncionRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoTipoFuncionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cargoTipoFuncions = $this->cargoTipoFuncionRepository->all();

        return $this->sendResponse($cargoTipoFuncions->toArray(), 'Cargo Tipo Funcions retrieved successfully');
    }

    /**
     * Store a newly created CargoTipoFuncion in storage.
     * POST /cargoTipoFuncions
     *
     * @param CreateCargoTipoFuncionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoFuncionAPIRequest $request)
    {
        $input = $request->all();

        $cargoTipoFuncions = $this->cargoTipoFuncionRepository->create($input);

        return $this->sendResponse($cargoTipoFuncions->toArray(), 'Cargo Tipo Funcion saved successfully');
    }

    /**
     * Display the specified CargoTipoFuncion.
     * GET|HEAD /cargoTipoFuncions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CargoTipoFuncion $cargoTipoFuncion */
        $cargoTipoFuncion = $this->cargoTipoFuncionRepository->findWithoutFail($id);

        if (empty($cargoTipoFuncion)) {
            return $this->sendError('Cargo Tipo Funcion not found');
        }

        return $this->sendResponse($cargoTipoFuncion->toArray(), 'Cargo Tipo Funcion retrieved successfully');
    }

    /**
     * Update the specified CargoTipoFuncion in storage.
     * PUT/PATCH /cargoTipoFuncions/{id}
     *
     * @param  int $id
     * @param UpdateCargoTipoFuncionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoFuncionAPIRequest $request)
    {
        $input = $request->all();

        /** @var CargoTipoFuncion $cargoTipoFuncion */
        $cargoTipoFuncion = $this->cargoTipoFuncionRepository->findWithoutFail($id);

        if (empty($cargoTipoFuncion)) {
            return $this->sendError('Cargo Tipo Funcion not found');
        }

        $cargoTipoFuncion = $this->cargoTipoFuncionRepository->update($input, $id);

        return $this->sendResponse($cargoTipoFuncion->toArray(), 'CargoTipoFuncion updated successfully');
    }

    /**
     * Remove the specified CargoTipoFuncion from storage.
     * DELETE /cargoTipoFuncions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CargoTipoFuncion $cargoTipoFuncion */
        $cargoTipoFuncion = $this->cargoTipoFuncionRepository->findWithoutFail($id);

        if (empty($cargoTipoFuncion)) {
            return $this->sendError('Cargo Tipo Funcion not found');
        }

        $cargoTipoFuncion->delete();

        return $this->sendResponse($id, 'Cargo Tipo Funcion deleted successfully');
    }

    public function searchTipoFuncion(Request $request)
    {
        $this->cargoTipoFuncionRepository->with('funciones');
        $this->cargoTipoFuncionRepository->with('funciones.tipoFuncion');
        $cargo_tipo_funcion = $this->cargoTipoFuncionRepository->searchTipoFuncion($request->get("funcionesList"));

        if (empty($cargo_tipo_funcion)) {
            return $this->sendError('No se encontró la función');
        }

        return $this->sendResponse($cargo_tipo_funcion->toArray(), 'Tipo Funcion retrieved successfully',200);
    }
}
