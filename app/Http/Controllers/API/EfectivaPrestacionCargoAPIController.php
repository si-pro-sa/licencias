<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEfectivaPrestacionCargoAPIRequest;
use App\Http\Requests\API\UpdateEfectivaPrestacionCargoAPIRequest;
use App\Models\EfectivaPrestacionCargo;
use App\Repositories\EfectivaPrestacionCargoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;
use App\DataTables\EfectivaPrestacionCargoDataTable;

/**
 * Class EfectivaPrestacionCargoController
 * @package App\Http\Controllers\API
 */
class EfectivaPrestacionCargoAPIController extends AppBaseController
{
    /** @var  EfectivaPrestacionCargoRepository */
    private $efectivaPrestacionCargoRepository;

    public function __construct(EfectivaPrestacionCargoRepository $efectivaPrestacionCargoRepo)
    {
        $this->efectivaPrestacionCargoRepository = $efectivaPrestacionCargoRepo;
    }

    /**
     * Display a listing of the EfectivaPrestacionCargo.
     * GET|HEAD /efectivaPrestacionCargos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $efectivaPrestacionCargos = $this->efectivaPrestacionCargoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($efectivaPrestacionCargos->toArray(), 'Efectiva Prestacion Cargos retrieved successfully');
    }

    /**
     * Store a newly created EfectivaPrestacionCargo in storage.
     * POST /efectivaPrestacionCargos
     *
     * @param CreateEfectivaPrestacionCargoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEfectivaPrestacionCargoAPIRequest $request)
    {
        $input = $request->all();

        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->create($input);

        return $this->sendResponse($efectivaPrestacionCargo->toArray(), 'Efectiva Prestacion Cargo saved successfully');
    }

    /**
     * Display the specified EfectivaPrestacionCargo.
     * GET|HEAD /efectivaPrestacionCargos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EfectivaPrestacionCargo $efectivaPrestacionCargo */
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            return $this->sendError('Efectiva Prestacion Cargo not found');
        }

        return $this->sendResponse($efectivaPrestacionCargo->toArray(), 'Efectiva Prestacion Cargo retrieved successfully');
    }

    /**
     * Update the specified EfectivaPrestacionCargo in storage.
     * PUT/PATCH /efectivaPrestacionCargos/{id}
     *
     * @param int $id
     * @param UpdateEfectivaPrestacionCargoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEfectivaPrestacionCargoAPIRequest $request)
    {
        $input = $request->all();

        /** @var EfectivaPrestacionCargo $efectivaPrestacionCargo */
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            return $this->sendError('Efectiva Prestacion Cargo not found');
        }

        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->update($input, $id);

        return $this->sendResponse($efectivaPrestacionCargo->toArray(), 'EfectivaPrestacionCargo updated successfully');
    }

    /**
     * Remove the specified EfectivaPrestacionCargo from storage.
     * DELETE /efectivaPrestacionCargos/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var EfectivaPrestacionCargo $efectivaPrestacionCargo */
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            return $this->sendError('Efectiva Prestacion Cargo not found');
        }

        $efectivaPrestacionCargo->delete();

        return $this->sendSuccess('Efectiva Prestacion Cargo deleted successfully');
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormulariosCargaDataTable(EfectivaPrestacionCargoDataTable $dataTable)
    {
        return $dataTable->dataTable(request())->toJson();
    }
}
