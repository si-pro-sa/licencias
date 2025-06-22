<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoParentescoAPIRequest;
use App\Http\Requests\API\UpdateTipoParentescoAPIRequest;
use App\Models\TipoParentesco;
use App\Repositories\TipoParentescoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TipoParentescoController
 * @package App\Http\Controllers\API
 */

class TipoParentescoAPIController extends AppBaseController
{
    /** @var  TipoParentescoRepository */
    private $tipoParentescoRepository;

    public function __construct(TipoParentescoRepository $tipoParentescoRepo)
    {
        $this->tipoParentescoRepository = $tipoParentescoRepo;
    }

    /**
     * Display a listing of the TipoParentesco.
     * GET|HEAD /tipoParentescos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tipoParentescos = $this->tipoParentescoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tipoParentescos->toArray(), 'Tipo Parentescos retrieved successfully');
    }

    /**
     * Store a newly created TipoParentesco in storage.
     * POST /tipoParentescos
     *
     * @param CreateTipoParentescoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoParentescoAPIRequest $request)
    {
        $input = $request->all();

        $tipoParentesco = $this->tipoParentescoRepository->create($input);

        return $this->sendResponse($tipoParentesco->toArray(), 'Tipo Parentesco saved successfully');
    }

    /**
     * Display the specified TipoParentesco.
     * GET|HEAD /tipoParentescos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoParentesco $tipoParentesco */
        $tipoParentesco = $this->tipoParentescoRepository->find($id);

        if (empty($tipoParentesco)) {
            return $this->sendError('Tipo Parentesco not found');
        }

        return $this->sendResponse($tipoParentesco->toArray(), 'Tipo Parentesco retrieved successfully');
    }

    /**
     * Update the specified TipoParentesco in storage.
     * PUT/PATCH /tipoParentescos/{id}
     *
     * @param int $id
     * @param UpdateTipoParentescoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoParentescoAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoParentesco $tipoParentesco */
        $tipoParentesco = $this->tipoParentescoRepository->find($id);

        if (empty($tipoParentesco)) {
            return $this->sendError('Tipo Parentesco not found');
        }

        $tipoParentesco = $this->tipoParentescoRepository->update($input, $id);

        return $this->sendResponse($tipoParentesco->toArray(), 'TipoParentesco updated successfully');
    }

    /**
     * Remove the specified TipoParentesco from storage.
     * DELETE /tipoParentescos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoParentesco $tipoParentesco */
        $tipoParentesco = $this->tipoParentescoRepository->find($id);

        if (empty($tipoParentesco)) {
            return $this->sendError('Tipo Parentesco not found');
        }

        $tipoParentesco->delete();

        return $this->sendResponse($id, 'Tipo Parentesco deleted successfully');
    }
}
