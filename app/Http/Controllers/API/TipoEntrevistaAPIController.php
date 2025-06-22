<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoEntrevistaAPIRequest;
use App\Http\Requests\API\UpdateTipoEntrevistaAPIRequest;
use App\Models\TipoEntrevista;
use App\Repositories\TipoEntrevistaRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TipoEntrevistaController
 * @package App\Http\Controllers\API
 */
class TipoEntrevistaAPIController extends AppBaseController
{
    /** @var  TipoEntrevistaRepository */
    private $tipoEntrevistaRepository;

    public function __construct(TipoEntrevistaRepository $tipoEntrevistaRepo)
    {
        $this->tipoEntrevistaRepository = $tipoEntrevistaRepo;
    }

    /**
     * Display a listing of the TipoEntrevista.
     * GET|HEAD /tipoEntrevistas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (User::tienePermiso('TipoEntrevistaAPIController-index')) {
            $tipoEntrevistas = $this->tipoEntrevistaRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit'),
                ['idtipo_entrevista', 'tipoentrevista']
            );

            return $this->sendResponse($tipoEntrevistas->toArray(), 'Tipo Entrevistas retrieved successfully');
        } else {
            return $this->sendError('No tiene permisos', 403);
        }
    }

    /**
     * Store a newly created TipoEntrevista in storage.
     * POST /tipoEntrevistas
     *
     * @param CreateTipoEntrevistaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoEntrevistaAPIRequest $request)
    {
        $input = $request->all();

        $tipoEntrevista = $this->tipoEntrevistaRepository->create($input);

        return $this->sendResponse($tipoEntrevista->toArray(), 'Tipo Entrevista saved successfully');
    }

    /**
     * Display the specified TipoEntrevista.
     * GET|HEAD /tipoEntrevistas/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoEntrevista $tipoEntrevista */
        $tipoEntrevista = $this->tipoEntrevistaRepository->find($id);

        if (empty($tipoEntrevista)) {
            return $this->sendError('Tipo Entrevista not found');
        }

        return $this->sendResponse($tipoEntrevista->toArray(), 'Tipo Entrevista retrieved successfully');
    }

    /**
     * Update the specified TipoEntrevista in storage.
     * PUT/PATCH /tipoEntrevistas/{id}
     *
     * @param int $id
     * @param UpdateTipoEntrevistaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoEntrevistaAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoEntrevista $tipoEntrevista */
        $tipoEntrevista = $this->tipoEntrevistaRepository->find($id);

        if (empty($tipoEntrevista)) {
            return $this->sendError('Tipo Entrevista not found');
        }

        $tipoEntrevista = $this->tipoEntrevistaRepository->update($input, $id);

        return $this->sendResponse($tipoEntrevista->toArray(), 'TipoEntrevista updated successfully');
    }

    /**
     * Remove the specified TipoEntrevista from storage.
     * DELETE /tipoEntrevistas/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var TipoEntrevista $tipoEntrevista */
        $tipoEntrevista = $this->tipoEntrevistaRepository->find($id);

        if (empty($tipoEntrevista)) {
            return $this->sendError('Tipo Entrevista not found');
        }

        $tipoEntrevista->delete();

        return $this->sendSuccess('Tipo Entrevista deleted successfully');
    }
}
