<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoLicenciaAPIRequest;
use App\Http\Requests\API\UpdateTipoLicenciaAPIRequest;
use App\Models\TipoLicencia;
use App\Repositories\TipoLicenciaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoLicenciaController
 * @package App\Http\Controllers\API
 */

class TipoLicenciaAPIController extends AppBaseController
{
    /** @var  TipoLicenciaRepository */
    private $tipoLicenciaRepository;

    public function __construct(TipoLicenciaRepository $tipoLicenciaRepo)
    {
        $this->tipoLicenciaRepository = $tipoLicenciaRepo;
    }

    /**
     * Display a listing of the TipoLicencia.
     * GET|HEAD /tipoLicencias
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoLicenciaRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoLicenciaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoLicencias = $this->tipoLicenciaRepository->all();

        return $this->sendResponse($tipoLicencias->toArray(), 'Tipo Licencias retrieved successfully');
    }

    /**
     * Store a newly created TipoLicencia in storage.
     * POST /tipoLicencias
     *
     * @param CreateTipoLicenciaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoLicenciaAPIRequest $request)
    {
        $input = $request->all();

        $tipoLicencia = $this->tipoLicenciaRepository->create($input);

        return $this->sendResponse($tipoLicencia->toArray(), 'Tipo Licencia saved successfully');
    }

    /**
     * Display the specified TipoLicencia.
     * GET|HEAD /tipoLicencias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoLicencia $tipoLicencia */
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            return $this->sendError('Tipo Licencia not found');
        }

        return $this->sendResponse($tipoLicencia->toArray(), 'Tipo Licencia retrieved successfully');
    }

    /**
     * Update the specified TipoLicencia in storage.
     * PUT/PATCH /tipoLicencias/{id}
     *
     * @param  int $id
     * @param UpdateTipoLicenciaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoLicenciaAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoLicencia $tipoLicencia */
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            return $this->sendError('Tipo Licencia not found');
        }

        $tipoLicencia = $this->tipoLicenciaRepository->update($input, $id);

        return $this->sendResponse($tipoLicencia->toArray(), 'TipoLicencia updated successfully');
    }

    /**
     * Remove the specified TipoLicencia from storage.
     * DELETE /tipoLicencias/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoLicencia $tipoLicencia */
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            return $this->sendError('Tipo Licencia not found');
        }

        $tipoLicencia->delete();

        return $this->sendResponse($id, 'Tipo Licencia deleted successfully');
    }
}
