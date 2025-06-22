<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCapacitacionAgenteAPIRequest;
use App\Http\Requests\API\UpdateCapacitacionAgenteAPIRequest;
use App\Models\CapacitacionAgente;
use App\Repositories\CapacitacionAgenteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CapacitacionAgenteController
 * @package App\Http\Controllers\API
 */

class CapacitacionAgenteAPIController extends AppBaseController
{
    /** @var  CapacitacionAgenteRepository */
    private $capacitacionAgenteRepository;

    public function __construct(CapacitacionAgenteRepository $capacitacionAgenteRepo)
    {
        $this->capacitacionAgenteRepository = $capacitacionAgenteRepo;
    }

    /**
     * Display a listing of the CapacitacionAgente.
     * GET|HEAD /capacitacionAgentes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $capacitacionAgentes = $this->capacitacionAgenteRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($capacitacionAgentes->toArray(), 'Capacitacion Agentes retrieved successfully');
    }

    /**
     * Store a newly created CapacitacionAgente in storage.
     * POST /capacitacionAgentes
     *
     * @param CreateCapacitacionAgenteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCapacitacionAgenteAPIRequest $request)
    {
        $input = $request->all();

        $capacitacionAgente = $this->capacitacionAgenteRepository->create($input);

        return $this->sendResponse($capacitacionAgente->toArray(), 'Capacitacion Agente saved successfully');
    }

    /**
     * Display the specified CapacitacionAgente.
     * GET|HEAD /capacitacionAgentes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CapacitacionAgente $capacitacionAgente */
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            return $this->sendError('Capacitacion Agente not found');
        }

        return $this->sendResponse($capacitacionAgente->toArray(), 'Capacitacion Agente retrieved successfully');
    }

    /**
     * Update the specified CapacitacionAgente in storage.
     * PUT/PATCH /capacitacionAgentes/{id}
     *
     * @param int $id
     * @param UpdateCapacitacionAgenteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCapacitacionAgenteAPIRequest $request)
    {
        $input = $request->all();

        /** @var CapacitacionAgente $capacitacionAgente */
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            return $this->sendError('Capacitacion Agente not found');
        }

        $capacitacionAgente = $this->capacitacionAgenteRepository->update($input, $id);

        return $this->sendResponse($capacitacionAgente->toArray(), 'CapacitacionAgente updated successfully');
    }

    /**
     * Remove the specified CapacitacionAgente from storage.
     * DELETE /capacitacionAgentes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CapacitacionAgente $capacitacionAgente */
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            return $this->sendError('Capacitacion Agente not found');
        }

        $capacitacionAgente->delete();

        return $this->sendSuccess('Capacitacion Agente deleted successfully');
    }
}
