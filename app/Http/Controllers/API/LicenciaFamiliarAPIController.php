<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLicenciaFamiliarAPIRequest;
use App\Http\Requests\API\UpdateLicenciaFamiliarAPIRequest;
use App\Models\LicenciaFamiliar;
use App\Repositories\LicenciaFamiliarRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class LicenciaFamiliarController
 * @package App\Http\Controllers\API
 */

class LicenciaFamiliarAPIController extends AppBaseController
{
    /** @var  LicenciaFamiliarRepository */
    private $licenciaFamiliarRepository;

    public function __construct(LicenciaFamiliarRepository $licenciaFamiliarRepo)
    {
        $this->licenciaFamiliarRepository = $licenciaFamiliarRepo;
    }

    /**
     * Display a listing of the LicenciaFamiliar.
     * GET|HEAD /licenciaFamiliars
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->licenciaFamiliarRepository->pushCriteria(new RequestCriteria($request));
        $this->licenciaFamiliarRepository->pushCriteria(new LimitOffsetCriteria($request));
        $licenciaFamiliars = $this->licenciaFamiliarRepository->all();

        return $this->sendResponse($licenciaFamiliars->toArray(), 'Licencia Familiars retrieved successfully');
    }

    /**
     * Store a newly created LicenciaFamiliar in storage.
     * POST /licenciaFamiliars
     *
     * @param CreateLicenciaFamiliarAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenciaFamiliarAPIRequest $request)
    {
        $input = $request->all();

        $licenciaFamiliar = $this->licenciaFamiliarRepository->create($input);

        return $this->sendResponse($licenciaFamiliar->toArray(), 'Licencia Familiar saved successfully');
    }

    /**
     * Display the specified LicenciaFamiliar.
     * GET|HEAD /licenciaFamiliars/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var LicenciaFamiliar $licenciaFamiliar */
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            return $this->sendError('Licencia Familiar not found');
        }

        return $this->sendResponse($licenciaFamiliar->toArray(), 'Licencia Familiar retrieved successfully');
    }

    /**
     * Update the specified LicenciaFamiliar in storage.
     * PUT/PATCH /licenciaFamiliars/{id}
     *
     * @param  int $id
     * @param UpdateLicenciaFamiliarAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenciaFamiliarAPIRequest $request)
    {
        $input = $request->all();

        /** @var LicenciaFamiliar $licenciaFamiliar */
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            return $this->sendError('Licencia Familiar not found');
        }

        $licenciaFamiliar = $this->licenciaFamiliarRepository->update($input, $id);

        return $this->sendResponse($licenciaFamiliar->toArray(), 'LicenciaFamiliar updated successfully');
    }

    /**
     * Remove the specified LicenciaFamiliar from storage.
     * DELETE /licenciaFamiliars/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var LicenciaFamiliar $licenciaFamiliar */
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            return $this->sendError('Licencia Familiar not found');
        }

        $licenciaFamiliar->delete();

        return $this->sendResponse($id, 'Licencia Familiar deleted successfully');
    }
}
