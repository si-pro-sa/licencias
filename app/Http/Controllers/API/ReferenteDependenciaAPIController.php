<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateReferenteDependenciaAPIRequest;
use App\Http\Requests\API\UpdateReferenteDependenciaAPIRequest;
use App\Models\ReferenteDependencia;
use App\Repositories\ReferenteDependenciaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ReferenteDependenciaController
 * @package App\Http\Controllers\API
 */

class ReferenteDependenciaAPIController extends AppBaseController
{
    /** @var  ReferenteDependenciaRepository */
    private $referenteDependenciaRepository;

    public function __construct(ReferenteDependenciaRepository $referenteDependenciaRepo)
    {
        $this->referenteDependenciaRepository = $referenteDependenciaRepo;
    }

    /**
     * Display a listing of the ReferenteDependencia.
     * GET|HEAD /referenteDependencias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->referenteDependenciaRepository->pushCriteria(new RequestCriteria($request));
        $this->referenteDependenciaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $referenteDependencias = $this->referenteDependenciaRepository->all();

        return $this->sendResponse($referenteDependencias->toArray(), 'Referente Dependencias retrieved successfully');
    }

    /**
     * Store a newly created ReferenteDependencia in storage.
     * POST /referenteDependencias
     *
     * @param CreateReferenteDependenciaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateReferenteDependenciaAPIRequest $request)
    {
        $input = $request->all();

        $referenteDependencias = $this->referenteDependenciaRepository->create($input);

        return $this->sendResponse($referenteDependencias->toArray(), 'Referente Dependencia saved successfully');
    }

    /**
     * Display the specified ReferenteDependencia.
     * GET|HEAD /referenteDependencias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ReferenteDependencia $referenteDependencia */
        $referenteDependencia = $this->referenteDependenciaRepository->findWithoutFail($id);

        if (empty($referenteDependencia)) {
            return $this->sendError('Referente Dependencia not found');
        }

        return $this->sendResponse($referenteDependencia->toArray(), 'Referente Dependencia retrieved successfully');
    }

    /**
     * Update the specified ReferenteDependencia in storage.
     * PUT/PATCH /referenteDependencias/{id}
     *
     * @param  int $id
     * @param UpdateReferenteDependenciaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReferenteDependenciaAPIRequest $request)
    {
        $input = $request->all();

        /** @var ReferenteDependencia $referenteDependencia */
        $referenteDependencia = $this->referenteDependenciaRepository->findWithoutFail($id);

        if (empty($referenteDependencia)) {
            return $this->sendError('Referente Dependencia not found');
        }

        $referenteDependencia = $this->referenteDependenciaRepository->update($input, $id);

        return $this->sendResponse($referenteDependencia->toArray(), 'ReferenteDependencia updated successfully');
    }

    /**
     * Remove the specified ReferenteDependencia from storage.
     * DELETE /referenteDependencias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ReferenteDependencia $referenteDependencia */
        $referenteDependencia = $this->referenteDependenciaRepository->findWithoutFail($id);

        if (empty($referenteDependencia)) {
            return $this->sendError('Referente Dependencia not found');
        }

        $referenteDependencia->delete();

        return $this->sendResponse($id, 'Referente Dependencia deleted successfully');
    }
}
