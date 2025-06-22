<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoSexoAPIRequest;
use App\Http\Requests\API\UpdateTipoSexoAPIRequest;
use App\Models\TipoSexo;
use App\Repositories\TipoSexoRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TipoSexoController
 * @package App\Http\Controllers\API
 */
class TipoSexoAPIController extends AppBaseController
{
    /** @var  TipoSexoRepository */
    private $tipoSexoRepository;

    public function __construct(TipoSexoRepository $tipoSexoRepo)
    {
        $this->tipoSexoRepository = $tipoSexoRepo;
    }

    /**
     * Display a listing of the TipoSexo.
     * GET|HEAD /tipoSexos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (User::tienePermiso('TipoSexoAPIController-index')) {
            $tipoSexos = $this->tipoSexoRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );
            $data = [];
            foreach ($tipoSexos as $key => $sexo) {
                $data[$key] = [
                    'value' => $sexo->idtipo_sexo,
                    'label' => $sexo->tiposexo,
                ];
            }
            return $this->sendResponse($data, 'Tipo Sexos retrieved successfully');
        } else {
            return $this->sendError('No tiene permisos', 403);
        }
    }

    /**
     * Store a newly created TipoSexo in storage.
     * POST /tipoSexos
     *
     * @param CreateTipoSexoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoSexoAPIRequest $request)
    {
        $input = $request->all();

        $tipoSexo = $this->tipoSexoRepository->create($input);

        return $this->sendResponse($tipoSexo->toArray(), 'Tipo Sexo saved successfully');
    }

    /**
     * Display the specified TipoSexo.
     * GET|HEAD /tipoSexos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoSexo $tipoSexo */
        $tipoSexo = $this->tipoSexoRepository->find($id);

        if (empty($tipoSexo)) {
            return $this->sendError('Tipo Sexo not found');
        }

        return $this->sendResponse($tipoSexo->toArray(), 'Tipo Sexo retrieved successfully');
    }

    /**
     * Update the specified TipoSexo in storage.
     * PUT/PATCH /tipoSexos/{id}
     *
     * @param int $id
     * @param UpdateTipoSexoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoSexoAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoSexo $tipoSexo */
        $tipoSexo = $this->tipoSexoRepository->find($id);

        if (empty($tipoSexo)) {
            return $this->sendError('Tipo Sexo not found');
        }

        $tipoSexo = $this->tipoSexoRepository->update($input, $id);

        return $this->sendResponse($tipoSexo->toArray(), 'TipoSexo updated successfully');
    }

    /**
     * Remove the specified TipoSexo from storage.
     * DELETE /tipoSexos/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var TipoSexo $tipoSexo */
        $tipoSexo = $this->tipoSexoRepository->find($id);

        if (empty($tipoSexo)) {
            return $this->sendError('Tipo Sexo not found');
        }

        $tipoSexo->delete();

        return $this->sendResponse($id, 'Tipo Sexo deleted successfully');
    }
}
