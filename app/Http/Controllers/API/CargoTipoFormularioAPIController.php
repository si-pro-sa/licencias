<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoTipoFormularioAPIRequest;
use App\Http\Requests\API\UpdateCargoTipoFormularioAPIRequest;
use App\Models\CargoTipoFormulario;
use App\Repositories\CargoTipoFormularioRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CargoTipoFormularioController
 * @package App\Http\Controllers\API
 */
class CargoTipoFormularioAPIController extends AppBaseController
{
    /** @var  CargoTipoFormularioRepository */
    private $cargoTipoFormularioRepository;

    public function __construct(CargoTipoFormularioRepository $cargoTipoFormularioRepo)
    {
        $this->cargoTipoFormularioRepository = $cargoTipoFormularioRepo;
    }

    /**
     * Display a listing of the CargoTipoFormulario.
     * GET|HEAD /cargoTipoFormularios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoFormularioRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoTipoFormularioRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cargoTipoFormularios = $this->cargoTipoFormularioRepository->all();

        return $this->sendResponse($cargoTipoFormularios->toArray(), 'Tipos de Formulario de Cargos retrieved successfully');
    }

    /**
     * Store a newly created CargoTipoFormulario in storage.
     * POST /cargoTipoFormularios
     *
     * @param CreateCargoTipoFormularioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoFormularioAPIRequest $request)
    {
        $input = $request->all();

        $cargoTipoFormularios = $this->cargoTipoFormularioRepository->create($input);

        return $this->sendResponse($cargoTipoFormularios->toArray(), 'Tipos de Formulario de Cargo saved successfully');
    }

    /**
     * Display the specified CargoTipoFormulario.
     * GET|HEAD /cargoTipoFormularios/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CargoTipoFormulario $cargoTipoFormulario */
        $cargoTipoFormulario = $this->cargoTipoFormularioRepository->findWithoutFail($id);

        if (empty($cargoTipoFormulario)) {
            return $this->sendError('Tipos de Formulario de Cargo not found');
        }

        return $this->sendResponse($cargoTipoFormulario->toArray(), 'Tipos de Formulario de Cargo retrieved successfully');
    }

    /**
     * Update the specified CargoTipoFormulario in storage.
     * PUT/PATCH /cargoTipoFormularios/{id}
     *
     * @param int $id
     * @param UpdateCargoTipoFormularioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoFormularioAPIRequest $request)
    {
        $input = $request->all();

        /** @var CargoTipoFormulario $cargoTipoFormulario */
        $cargoTipoFormulario = $this->cargoTipoFormularioRepository->findWithoutFail($id);

        if (empty($cargoTipoFormulario)) {
            return $this->sendError('Tipos de Formulario de Cargo not found');
        }

        $cargoTipoFormulario = $this->cargoTipoFormularioRepository->update($input, $id);

        return $this->sendResponse($cargoTipoFormulario->toArray(), 'CargoTipoFormulario updated successfully');
    }

    /**
     * Remove the specified CargoTipoFormulario from storage.
     * DELETE /cargoTipoFormularios/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CargoTipoFormulario $cargoTipoFormulario */
        $cargoTipoFormulario = $this->cargoTipoFormularioRepository->findWithoutFail($id);

        if (empty($cargoTipoFormulario)) {
            return $this->sendError('Tipos de Formulario de Cargo not found');
        }

        $cargoTipoFormulario->delete();

        return $this->sendResponse($id, 'Tipos de Formulario de Cargo deleted successfully');
    }

    public function searchCargoTipoFormulario(Request $request)
    {
        $cargotipo_formulario = $this->cargoTipoFormularioRepository->getCargoTipoFormulario($request->get('cargotipo_formulario'));

        if (empty($cargotipo_formulario)) {
            return $this->sendError('Tipos de Formulario de Cargo not found');
        }

        return $this->sendResponse($cargotipo_formulario->toArray(), 'Tipos de Formulario de Cargo retrieved successfully', 200);
    }

    /**
     * Muestro listado de tipos de formularios de cargo utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $cargoTiposFormulario = CargoTipoFormulario::where('idcargo_tipo_formulario', '>', '2')->orderBy('cargotipo_formulario')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idcargo_tipo_formulario, 'label' => $model->cargotipo_formulario];
        });
        return $cargoTiposFormulario;
    }
}
