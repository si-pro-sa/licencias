<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCualquieraAPIRequest;
use App\Http\Requests\API\UpdateCualquieraAPIRequest;
use App\Models\Cualquiera;
use App\Repositories\CualquieraRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CualquieraController
 * @package App\Http\Controllers\API
 */

class CualquieraAPIController extends AppBaseController
{
    /** @var  CualquieraRepository */
    private $cualquieraRepository;

    public function __construct(CualquieraRepository $cualquieraRepo)
    {
        $this->cualquieraRepository = $cualquieraRepo;
    }

    /**
     * Display a listing of the Cualquiera.
     * GET|HEAD /cualquieras
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $cualquieras = $this->cualquieraRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($cualquieras->toArray(), 'Cualquieras retrieved successfully');
    }

    /**
     * Store a newly created Cualquiera in storage.
     * POST /cualquieras
     *
     * @param CreateCualquieraAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCualquieraAPIRequest $request)
    {
        $input = $request->all();

        $cualquiera = $this->cualquieraRepository->create($input);

        return $this->sendResponse($cualquiera->toArray(), 'Cualquiera saved successfully');
    }

    /**
     * Display the specified Cualquiera.
     * GET|HEAD /cualquieras/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Cualquiera $cualquiera */
        $cualquiera = $this->cualquieraRepository->find($id);

        if (empty($cualquiera)) {
            return $this->sendError('Cualquiera not found');
        }

        return $this->sendResponse($cualquiera->toArray(), 'Cualquiera retrieved successfully');
    }

    /**
     * Update the specified Cualquiera in storage.
     * PUT/PATCH /cualquieras/{id}
     *
     * @param int $id
     * @param UpdateCualquieraAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCualquieraAPIRequest $request)
    {
        $input = $request->all();

        /** @var Cualquiera $cualquiera */
        $cualquiera = $this->cualquieraRepository->find($id);

        if (empty($cualquiera)) {
            return $this->sendError('Cualquiera not found');
        }

        $cualquiera = $this->cualquieraRepository->update($input, $id);

        return $this->sendResponse($cualquiera->toArray(), 'Cualquiera updated successfully');
    }

    /**
     * Remove the specified Cualquiera from storage.
     * DELETE /cualquieras/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Cualquiera $cualquiera */
        $cualquiera = $this->cualquieraRepository->find($id);

        if (empty($cualquiera)) {
            return $this->sendError('Cualquiera not found');
        }

        $cualquiera->delete();

        return $this->sendSuccess('Cualquiera deleted successfully');
    }
}
