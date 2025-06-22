<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoCeseAPIRequest;
use App\Http\Requests\API\UpdateTipoCeseAPIRequest;
use App\Models\TipoCese;
use App\Repositories\TipoCeseRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoCeseController
 * @package App\Http\Controllers\API
 */

class TipoCeseAPIController extends AppBaseController
{
    /** @var  TipoCeseRepository */
    private $tipoCeseRepository;

    public function __construct(TipoCeseRepository $tipoCeseRepo)
    {
        $this->tipoCeseRepository = $tipoCeseRepo;
    }

    /**
     * Display a listing of the TipoCese.
     * GET|HEAD /tipoCeses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoCeseRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoCeseRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoCeses = $this->tipoCeseRepository->all();

        return $this->sendResponse($tipoCeses->toArray(), 'Tipo Ceses retrieved successfully');
    }

    /**
     * Store a newly created TipoCese in storage.
     * POST /tipoCeses
     *
     * @param CreateTipoCeseAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoCeseAPIRequest $request)
    {
        $input = $request->all();

        $tipoCeses = $this->tipoCeseRepository->create($input);

        return $this->sendResponse($tipoCeses->toArray(), 'Tipo Cese saved successfully');
    }

    /**
     * Display the specified TipoCese.
     * GET|HEAD /tipoCeses/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoCese $tipoCese */
        $tipoCese = $this->tipoCeseRepository->findWithoutFail($id);

        if (empty($tipoCese)) {
            return $this->sendError('Tipo Cese not found');
        }

        return $this->sendResponse($tipoCese->toArray(), 'Tipo Cese retrieved successfully');
    }

    /**
     * Update the specified TipoCese in storage.
     * PUT/PATCH /tipoCeses/{id}
     *
     * @param  int $id
     * @param UpdateTipoCeseAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoCeseAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoCese $tipoCese */
        $tipoCese = $this->tipoCeseRepository->findWithoutFail($id);

        if (empty($tipoCese)) {
            return $this->sendError('Tipo Cese not found');
        }

        $tipoCese = $this->tipoCeseRepository->update($input, $id);

        return $this->sendResponse($tipoCese->toArray(), 'TipoCese updated successfully');
    }

    /**
     * Remove the specified TipoCese from storage.
     * DELETE /tipoCeses/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoCese $tipoCese */
        $tipoCese = $this->tipoCeseRepository->findWithoutFail($id);

        if (empty($tipoCese)) {
            return $this->sendError('Tipo Cese not found');
        }

        $tipoCese->delete();

        return $this->sendResponse($id, 'Tipo Cese deleted successfully');
    }

    public function searchTipoCese(Request $request)
    {
        $tipocese = $this->tipoCeseRepository->getTipoCese($request->get('tipo_cese'));

        if (empty($tipocese)) {
            return $this->sendError('Tipo Cese not found');
        }

        return $this->sendResponse($tipocese->toArray(), 'Tipo Cese retrieved successfully',200);
    }

    /**
     * Muestro listado de tipos de Cese utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposCese = TipoCese::orderBy('tipocese')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idtipo_cese, 'label' => $model->tipocese];
        });
        return $tiposCese;
    }
}
