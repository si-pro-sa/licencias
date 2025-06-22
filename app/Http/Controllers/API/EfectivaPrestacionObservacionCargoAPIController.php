<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEfectivaPrestacionObservacionCargoAPIRequest;
use App\Http\Requests\API\UpdateEfectivaPrestacionObservacionCargoAPIRequest;
use App\Models\EfectivaPrestacionObservacionCargo;
use App\Repositories\EfectivaPrestacionObservacionCargoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class EfectivaPrestacionObservacionCargoController
 * @package App\Http\Controllers\API
 */
class EfectivaPrestacionObservacionCargoAPIController extends AppBaseController
{
    /** @var  EfectivaPrestacionObservacionCargoRepository */
    private $efectivaPrestacionObservacionCargoRepository;

    public function __construct(EfectivaPrestacionObservacionCargoRepository $efectivaPrestacionObservacionCargoRepo)
    {
        $this->efectivaPrestacionObservacionCargoRepository = $efectivaPrestacionObservacionCargoRepo;
    }

    /**
     * Display a listing of the EfectivaPrestacionObservacionCargo.
     * GET|HEAD /efectivaPrestacionObservacionCargos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $efectivaPrestacionObservacionCargos = $this->efectivaPrestacionObservacionCargoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($efectivaPrestacionObservacionCargos->toArray(), 'Efectiva Prestacion Observacion Cargos retrieved successfully');
    }

    /**
     * Store a newly created EfectivaPrestacionObservacionCargo in storage.
     * POST /efectivaPrestacionObservacionCargos
     *
     * @param CreateEfectivaPrestacionObservacionCargoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEfectivaPrestacionObservacionCargoAPIRequest $request)
    {
        $input = $request->all();

        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->create($input);

        return $this->sendResponse($efectivaPrestacionObservacionCargo->toArray(), 'Efectiva Prestacion Observacion Cargo saved successfully');
    }

    /**
     * Display the specified EfectivaPrestacionObservacionCargo.
     * GET|HEAD /efectivaPrestacionObservacionCargos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EfectivaPrestacionObservacionCargo $efectivaPrestacionObservacionCargo */
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            return $this->sendError('Efectiva Prestacion Observacion Cargo not found');
        }

        return $this->sendResponse($efectivaPrestacionObservacionCargo->toArray(), 'Efectiva Prestacion Observacion Cargo retrieved successfully');
    }

    /**
     * Update the specified EfectivaPrestacionObservacionCargo in storage.
     * PUT/PATCH /efectivaPrestacionObservacionCargos/{id}
     *
     * @param int $id
     * @param UpdateEfectivaPrestacionObservacionCargoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEfectivaPrestacionObservacionCargoAPIRequest $request)
    {
        $input = $request->all();

        /** @var EfectivaPrestacionObservacionCargo $efectivaPrestacionObservacionCargo */
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            return $this->sendError('Efectiva Prestacion Observacion Cargo not found');
        }

        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->update($input, $id);

        return $this->sendResponse($efectivaPrestacionObservacionCargo->toArray(), 'EfectivaPrestacionObservacionCargo updated successfully');
    }

    /**
     * Remove the specified EfectivaPrestacionObservacionCargo from storage.
     * DELETE /efectivaPrestacionObservacionCargos/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var EfectivaPrestacionObservacionCargo $efectivaPrestacionObservacionCargo */
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            return $this->sendError('Efectiva Prestacion Observacion Cargo not found');
        }

        $efectivaPrestacionObservacionCargo->delete();

        return $this->sendSuccess('Efectiva Prestacion Observacion Cargo deleted successfully');
    }
}
