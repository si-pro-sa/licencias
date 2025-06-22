<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoCampaniaAPIRequest;
use App\Http\Requests\API\UpdateTipoCampaniaAPIRequest;
use App\Models\TipoCampania;
use App\Repositories\TipoCampaniaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoCampaniaController
 * @package App\Http\Controllers\API
 */

class TipoCampaniaAPIController extends AppBaseController
{
    /** @var  TipoCampaniaRepository */
    private $tipoCampaniaRepository;

    public function __construct(TipoCampaniaRepository $tipoCampaniaRepo)
    {
        $this->tipoCampaniaRepository = $tipoCampaniaRepo;
    }

    /**
     * Display a listing of the TipoCampania.
     * GET|HEAD /tipoCampanias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoCampaniaRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoCampaniaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoCampanias = $this->tipoCampaniaRepository->all();

        return $this->sendResponse($tipoCampanias->toArray(), 'Tipo Agrupamientos retrieved successfully');
    }

    /**
     * Store a newly created TipoCampania in storage.
     * POST /tipoCampanias
     *
     * @param CreateTipoCampaniaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoCampaniaAPIRequest $request)
    {
        $input = $request->all();

        $tipoCampanias = $this->tipoCampaniaRepository->create($input);

        return $this->sendResponse($tipoCampanias->toArray(), 'Tipo Campaña saved successfully');
    }

    /**
     * Display the specified TipoCampania.
     * GET|HEAD /tipoCampanias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoCampania $tipoCampania */
        $tipoCampania = $this->tipoCampaniaRepository->findWithoutFail($id);

        if (empty($tipoCampania)) {
            return $this->sendError('Tipo Campaña not found');
        }

        return $this->sendResponse($tipoCampania->toArray(), 'Tipo Campaña retrieved successfully');
    }

    /**
     * Update the specified TipoCampania in storage.
     * PUT/PATCH /tipoCampanias/{id}
     *
     * @param  int $id
     * @param UpdateTipoCampaniaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoCampaniaAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoCampania $tipoCampania */
        $tipoCampania = $this->tipoCampaniaRepository->findWithoutFail($id);

        if (empty($tipoCampania)) {
            return $this->sendError('Tipo Campaña not found');
        }

        $tipoCampania = $this->tipoCampaniaRepository->update($input, $id);

        return $this->sendResponse($tipoCampania->toArray(), 'TipoCampania updated successfully');
    }

    /**
     * Remove the specified TipoCampania from storage.
     * DELETE /tipoCampanias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoCampania $tipoCampania */
        $tipoCampania = $this->tipoCampaniaRepository->findWithoutFail($id);

        if (empty($tipoCampania)) {
            return $this->sendError('Tipo Campaña not found');
        }

        $tipoCampania->delete();

        return $this->sendResponse($id, 'Tipo Campaña deleted successfully');
    }

    public function searchTipoCampania(Request $request)
    {
        $tipocampania = $this->tipoCampaniaRepository->getTipoCampania($request->get('tipocampania'));

        if (empty($tipocampania)) {
            return $this->sendError('Tipo Campaña not found');
        }

        return $this->sendResponse($tipocampania->toArray(), 'Tipo Campaña retrieved successfully', 200);
    }

    /**
     * Muestro listado de tipos de agrupamiento utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposCampania = TipoCampania::orderBy('tipocampania')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idtipo_campania, 'label' => $model->tipocampania];
        });
        return $tiposCampania;
    }
}
