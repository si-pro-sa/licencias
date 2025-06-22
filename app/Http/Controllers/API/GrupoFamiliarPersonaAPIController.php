<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGrupoFamiliarPersonaAPIRequest;
use App\Http\Requests\API\UpdateGrupoFamiliarPersonaAPIRequest;
use App\Models\GrupoFamiliarPersona;
use App\Repositories\GrupoFamiliarPersonaRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class GrupoFamiliarPersonaController
 * @package App\Http\Controllers\API
 */

class GrupoFamiliarPersonaAPIController extends AppBaseController
{
    /** @var  GrupoFamiliarPersonaRepository */
    private $grupoFamiliarPersonaRepository;

    public function __construct(GrupoFamiliarPersonaRepository $grupoFamiliarPersonaRepo)
    {
        $this->grupoFamiliarPersonaRepository = $grupoFamiliarPersonaRepo;
    }

    /**
     * Display a listing of the GrupoFamiliarPersona.
     * GET|HEAD /grupoFamiliarPersonas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->grupoFamiliarPersonaRepository->pushCriteria(new RequestCriteria($request));
        $this->grupoFamiliarPersonaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $grupoFamiliarPersonas = $this->grupoFamiliarPersonaRepository->all();

        return $this->sendResponse($grupoFamiliarPersonas->toArray(), 'Grupo Familiar Personas retrieved successfully');
    }

    /**
     * Store a newly created GrupoFamiliarPersona in storage.
     * POST /grupoFamiliarPersonas
     *
     * @param CreateGrupoFamiliarPersonaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateGrupoFamiliarPersonaAPIRequest $request)
    {
        $input = $request->all();

        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->create($input);

        return $this->sendResponse($grupoFamiliarPersona->toArray(), 'Grupo Familiar Persona saved successfully');
    }

    /**
     * Display the specified GrupoFamiliarPersona.
     * GET|HEAD /grupoFamiliarPersonas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var GrupoFamiliarPersona $grupoFamiliarPersona */
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            return $this->sendError('Grupo Familiar Persona not found');
        }

        return $this->sendResponse($grupoFamiliarPersona->toArray(), 'Grupo Familiar Persona retrieved successfully');
    }

    /**
     * Update the specified GrupoFamiliarPersona in storage.
     * PUT/PATCH /grupoFamiliarPersonas/{id}
     *
     * @param  int $id
     * @param UpdateGrupoFamiliarPersonaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGrupoFamiliarPersonaAPIRequest $request)
    {
        $input = $request->all();

        /** @var GrupoFamiliarPersona $grupoFamiliarPersona */
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            return $this->sendError('Grupo Familiar Persona not found');
        }

        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->update($input, $id);

        return $this->sendResponse($grupoFamiliarPersona->toArray(), 'GrupoFamiliarPersona updated successfully');
    }

    /**
     * Remove the specified GrupoFamiliarPersona from storage.
     * DELETE /grupoFamiliarPersonas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var GrupoFamiliarPersona $grupoFamiliarPersona */
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            return $this->sendError('Grupo Familiar Persona not found');
        }

        $grupoFamiliarPersona->delete();

        return $this->sendResponse($id, 'Grupo Familiar Persona deleted successfully');
    }
}
