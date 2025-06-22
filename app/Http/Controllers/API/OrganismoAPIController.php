<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateOrganismoAPIRequest;
use App\Http\Requests\API\UpdateOrganismoAPIRequest;
use App\Models\Organismo;
use App\Repositories\OrganismoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class OrganismoController
 * @package App\Http\Controllers\API
 */
class OrganismoAPIController extends AppBaseController
{
    /** @var  OrganismoRepository */
    private $organismoRepository;

    public function __construct(OrganismoRepository $organismoRepo)
    {
        $this->organismoRepository = $organismoRepo;
    }

    /**
     * Display a listing of the Organismo.
     * GET|HEAD /organismos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $organismos = $this->organismoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($organismos->toArray(), 'Organismos retrieved successfully');
    }

    /**
     * Store a newly created Organismo in storage.
     * POST /organismos
     *
     * @param CreateOrganismoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateOrganismoAPIRequest $request)
    {
        $input = $request->all();

        $organismo = $this->organismoRepository->create($input);

        return $this->sendResponse($organismo->toArray(), 'Organismo saved successfully');
    }

    /**
     * Display the specified Organismo.
     * GET|HEAD /organismos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Organismo $organismo */
        $organismo = $this->organismoRepository->find($id);

        if (empty($organismo)) {
            return $this->sendError('Organismo not found');
        }

        return $this->sendResponse($organismo->toArray(), 'Organismo retrieved successfully');
    }

    /**
     * Update the specified Organismo in storage.
     * PUT/PATCH /organismos/{id}
     *
     * @param int $id
     * @param UpdateOrganismoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOrganismoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Organismo $organismo */
        $organismo = $this->organismoRepository->find($id);

        if (empty($organismo)) {
            return $this->sendError('Organismo not found');
        }

        $organismo = $this->organismoRepository->update($input, $id);

        return $this->sendResponse($organismo->toArray(), 'Organismo updated successfully');
    }

    /**
     * Remove the specified Organismo from storage.
     * DELETE /organismos/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Organismo $organismo */
        $organismo = $this->organismoRepository->find($id);

        if (empty($organismo)) {
            return $this->sendError('Organismo not found');
        }

        $organismo->delete();

        return $this->sendSuccess('Organismo deleted successfully');
    }
}
