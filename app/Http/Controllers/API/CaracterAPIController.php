<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCaracterAPIRequest;
use App\Http\Requests\API\UpdateCaracterAPIRequest;
use App\Models\Caracter;
use App\Models\Licencia;
use App\Repositories\CaracterRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class CaracterController
 * @package App\Http\Controllers\API
 */

class CaracterAPIController extends AppBaseController
{
    /** @var  CaracterRepository */
    private $caracterRepository;

    public function __construct(CaracterRepository $caracterRepo)
    {
        $this->caracterRepository = $caracterRepo;
    }

    /**
     * Display a listing of the Caracter.
     * GET|HEAD /caracters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $caracters = $this->caracterRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($caracters->toArray(), 'Caracters retrieved successfully');
    }

    /**
     * Store a newly created Caracter in storage.
     * POST /caracters
     *
     * @param CreateCaracterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCaracterAPIRequest $request)
    {
        $input = $request->all();

        $caracter = $this->caracterRepository->create($input);

        return $this->sendResponse($caracter->toArray(), 'Caracter saved successfully');
    }

    /**
     * Display the specified Caracter.
     * GET|HEAD /caracters/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Caracter $caracter */
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            return $this->sendError('Caracter not found');
        }

        return $this->sendResponse($caracter->toArray(), 'Caracter retrieved successfully');
    }

    /**
     * Update the specified Caracter in storage.
     * PUT/PATCH /caracters/{id}
     *
     * @param int $id
     * @param UpdateCaracterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCaracterAPIRequest $request)
    {
        $input = $request->all();

        /** @var Caracter $caracter */
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            return $this->sendError('Caracter not found');
        }

        $caracter = $this->caracterRepository->update($input, $id);

        return $this->sendResponse($caracter->toArray(), 'Caracter updated successfully');
    }

    /**
     * Remove the specified Caracter from storage.
     * DELETE /caracters/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Caracter $caracter */
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            return $this->sendError('Caracter not found');
        }
        $licencia = Licencia::where('caracter','=',$caracter->descripcion)->first();
        if(isset($licencia)){
            return $this->sendError('Caracter esta siendo usado');    
        } 

        $caracter->delete();

        return $this->sendSuccess('Caracter deleted successfully');
    }
}
