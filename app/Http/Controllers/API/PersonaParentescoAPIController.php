<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePersonaParentescoAPIRequest;
use App\Http\Requests\API\UpdatePersonaParentescoAPIRequest;
use App\Models\PersonaParentesco;
use App\Repositories\PersonaParentescoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PersonaParentescoController
 * @package App\Http\Controllers\API
 */

class PersonaParentescoAPIController extends AppBaseController
{
    /** @var  PersonaParentescoRepository */
    private $personaParentescoRepository;

    public function __construct(PersonaParentescoRepository $personaParentescoRepo)
    {
        $this->personaParentescoRepository = $personaParentescoRepo;
    }

    /**
     * Display a listing of the PersonaParentesco.
     * GET|HEAD /personaParentescos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $personaParentescos = $this->personaParentescoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($personaParentescos->toArray(), 'Persona Parentescos retrieved successfully');
    }

    /**
     * Store a newly created PersonaParentesco in storage.
     * POST /personaParentescos
     *
     * @param CreatePersonaParentescoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePersonaParentescoAPIRequest $request)
    {
        $input = $request->all();

        $personaParentesco = $this->personaParentescoRepository->create($input);

        return $this->sendResponse($personaParentesco->toArray(), 'Persona Parentesco saved successfully');
    }

    /**
     * Display the specified PersonaParentesco.
     * GET|HEAD /personaParentescos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var PersonaParentesco $personaParentesco */
        $personaParentesco = $this->personaParentescoRepository->find($id);

        if (empty($personaParentesco)) {
            return $this->sendError('Persona Parentesco not found');
        }

        return $this->sendResponse($personaParentesco->toArray(), 'Persona Parentesco retrieved successfully');
    }

    /**
     * Update the specified PersonaParentesco in storage.
     * PUT/PATCH /personaParentescos/{id}
     *
     * @param int $id
     * @param UpdatePersonaParentescoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePersonaParentescoAPIRequest $request)
    {
        $input = $request->all();

        /** @var PersonaParentesco $personaParentesco */
        $personaParentesco = $this->personaParentescoRepository->find($id);

        if (empty($personaParentesco)) {
            return $this->sendError('Persona Parentesco not found');
        }

        $personaParentesco = $this->personaParentescoRepository->update($input, $id);

        return $this->sendResponse($personaParentesco->toArray(), 'PersonaParentesco updated successfully');
    }

    /**
     * Remove the specified PersonaParentesco from storage.
     * DELETE /personaParentescos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var PersonaParentesco $personaParentesco */
        $personaParentesco = $this->personaParentescoRepository->find($id);

        if (empty($personaParentesco)) {
            return $this->sendError('Persona Parentesco not found');
        }

        $personaParentesco->delete();

        return $this->sendResponse($id, 'Persona Parentesco deleted successfully');
    }
}
