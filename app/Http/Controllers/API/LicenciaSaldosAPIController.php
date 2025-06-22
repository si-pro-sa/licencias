<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLicenciaSaldosAPIRequest;
use App\Http\Requests\API\UpdateLicenciaSaldosAPIRequest;
use App\Models\LicenciaSaldos;
use App\Repositories\LicenciaSaldosRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class LicenciaSaldosController
 * @package App\Http\Controllers\API
 */

class LicenciaSaldosAPIController extends AppBaseController
{
    /** @var  LicenciaSaldosRepository */
    private $licenciaSaldosRepository;

    public function __construct(LicenciaSaldosRepository $licenciaSaldosRepo)
    {
        $this->licenciaSaldosRepository = $licenciaSaldosRepo;
    }

    /**
     * Display a listing of the LicenciaSaldos.
     * GET|HEAD /licenciaSaldos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $licenciaSaldos = $this->licenciaSaldosRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($licenciaSaldos->toArray(), 'Licencia Saldos retrieved successfully');
    }

    /**
     * Store a newly created LicenciaSaldos in storage.
     * POST /licenciaSaldos
     *
     * @param CreateLicenciaSaldosAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenciaSaldosAPIRequest $request)
    {
        $input = $request->all();

        $licenciaSaldos = $this->licenciaSaldosRepository->create($input);

        return $this->sendResponse($licenciaSaldos->toArray(), 'Licencia Saldos saved successfully');
    }

    /**
     * Display the specified LicenciaSaldos.
     * GET|HEAD /licenciaSaldos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var LicenciaSaldos $licenciaSaldos */
        $licenciaSaldos = $this->licenciaSaldosRepository->find($id);

        if (empty($licenciaSaldos)) {
            return $this->sendError('Licencia Saldos not found');
        }

        return $this->sendResponse($licenciaSaldos->toArray(), 'Licencia Saldos retrieved successfully');
    }

    /**
     * Update the specified LicenciaSaldos in storage.
     * PUT/PATCH /licenciaSaldos/{id}
     *
     * @param int $id
     * @param UpdateLicenciaSaldosAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenciaSaldosAPIRequest $request)
    {
        $input = $request->all();

        /** @var LicenciaSaldos $licenciaSaldos */
        $licenciaSaldos = $this->licenciaSaldosRepository->find($id);

        if (empty($licenciaSaldos)) {
            return $this->sendError('Licencia Saldos not found');
        }

        $licenciaSaldos = $this->licenciaSaldosRepository->update($input, $id);

        return $this->sendResponse($licenciaSaldos->toArray(), 'LicenciaSaldos updated successfully');
    }

    /**
     * Remove the specified LicenciaSaldos from storage.
     * DELETE /licenciaSaldos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var LicenciaSaldos $licenciaSaldos */
        $licenciaSaldos = $this->licenciaSaldosRepository->find($id);

        if (empty($licenciaSaldos)) {
            return $this->sendError('Licencia Saldos not found');
        }

        $licenciaSaldos->delete();

        return $this->sendResponse($id, 'Licencia Saldos deleted successfully');
    }
}
