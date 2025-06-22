<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoAgrupamientoAPIRequest;
use App\Http\Requests\API\UpdateTipoAgrupamientoAPIRequest;
use App\Models\TipoAgrupamiento;
use App\Repositories\TipoAgrupamientoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoAgrupamientoController
 * @package App\Http\Controllers\API
 */

class TipoAgrupamientoAPIController extends AppBaseController
{
    /** @var  TipoAgrupamientoRepository */
    private $tipoAgrupamientoRepository;

    public function __construct(TipoAgrupamientoRepository $tipoAgrupamientoRepo)
    {
        $this->tipoAgrupamientoRepository = $tipoAgrupamientoRepo;
    }

    /**
     * Display a listing of the TipoAgrupamiento.
     * GET|HEAD /tipoAgrupamientos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoAgrupamientoRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoAgrupamientoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoAgrupamientos = $this->tipoAgrupamientoRepository->all();

        return $this->sendResponse($tipoAgrupamientos->toArray(), 'Tipo Agrupamientos retrieved successfully');
    }

    /**
     * Store a newly created TipoAgrupamiento in storage.
     * POST /tipoAgrupamientos
     *
     * @param CreateTipoAgrupamientoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoAgrupamientoAPIRequest $request)
    {
        $input = $request->all();

        $tipoAgrupamientos = $this->tipoAgrupamientoRepository->create($input);

        return $this->sendResponse($tipoAgrupamientos->toArray(), 'Tipo Agrupamiento saved successfully');
    }

    /**
     * Display the specified TipoAgrupamiento.
     * GET|HEAD /tipoAgrupamientos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoAgrupamiento $tipoAgrupamiento */
        $tipoAgrupamiento = $this->tipoAgrupamientoRepository->findWithoutFail($id);

        if (empty($tipoAgrupamiento)) {
            return $this->sendError('Tipo Agrupamiento not found');
        }

        return $this->sendResponse($tipoAgrupamiento->toArray(), 'Tipo Agrupamiento retrieved successfully');
    }

    /**
     * Update the specified TipoAgrupamiento in storage.
     * PUT/PATCH /tipoAgrupamientos/{id}
     *
     * @param  int $id
     * @param UpdateTipoAgrupamientoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoAgrupamientoAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoAgrupamiento $tipoAgrupamiento */
        $tipoAgrupamiento = $this->tipoAgrupamientoRepository->findWithoutFail($id);

        if (empty($tipoAgrupamiento)) {
            return $this->sendError('Tipo Agrupamiento not found');
        }

        $tipoAgrupamiento = $this->tipoAgrupamientoRepository->update($input, $id);

        return $this->sendResponse($tipoAgrupamiento->toArray(), 'TipoAgrupamiento updated successfully');
    }

    /**
     * Remove the specified TipoAgrupamiento from storage.
     * DELETE /tipoAgrupamientos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoAgrupamiento $tipoAgrupamiento */
        $tipoAgrupamiento = $this->tipoAgrupamientoRepository->findWithoutFail($id);

        if (empty($tipoAgrupamiento)) {
            return $this->sendError('Tipo Agrupamiento not found');
        }

        $tipoAgrupamiento->delete();

        return $this->sendResponse($id, 'Tipo Agrupamiento deleted successfully');
    }
    public function searchTipoAgrupamiento(Request $request)
    {
        $tipoagrupamiento = $this->tipoAgrupamientoRepository->getTipoAgrupamiento($request->get('tipoagrupamiento'));

        if (empty($tipoagrupamiento)) {
            return $this->sendError('Tipo Agrupamiento not found');
        }

        return $this->sendResponse($tipoagrupamiento->toArray(), 'Tipo Agrupamiento retrieved successfully',200);
    }
    /**
     * Muestro listado de tipos de agrupamiento utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposAgrupamiento = TipoAgrupamiento::orderBy('tipoagrupamiento')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idtipo_agrupamiento, 'label' => $model->tipoagrupamiento];
        });
        return $tiposAgrupamiento;
    }
}
