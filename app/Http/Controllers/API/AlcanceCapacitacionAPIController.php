<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAlcanceCapacitacionAPIRequest;
use App\Http\Requests\API\UpdateAlcanceCapacitacionAPIRequest;
use App\Models\AlcanceCapacitacion;
use App\Repositories\AlcanceCapacitacionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Capacitacion;
use Response;

/**
 * Class AlcanceCapacitacionController
 * @package App\Http\Controllers\API
 */

class AlcanceCapacitacionAPIController extends AppBaseController
{
    /** @var  AlcanceCapacitacionRepository */
    private $alcanceCapacitacionRepository;

    public function __construct(AlcanceCapacitacionRepository $alcanceCapacitacionRepo)
    {
        $this->alcanceCapacitacionRepository = $alcanceCapacitacionRepo;
    }

    /**
     * Display a listing of the AlcanceCapacitacion.
     * GET|HEAD /alcanceCapacitacions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $alcanceCapacitacions = $this->alcanceCapacitacionRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($alcanceCapacitacions->toArray(), 'Alcance Capacitacions retrieved successfully');
    }

    /**
     * Store a newly created AlcanceCapacitacion in storage.
     * POST /alcanceCapacitacions
     *
     * @param CreateAlcanceCapacitacionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAlcanceCapacitacionAPIRequest $request)
    {
        $input = $request->all();

        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->create($input);

        return $this->sendResponse($alcanceCapacitacion->toArray(), 'Alcance Capacitacion saved successfully');
    }

    /**
     * Display the specified AlcanceCapacitacion.
     * GET|HEAD /alcanceCapacitacions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AlcanceCapacitacion $alcanceCapacitacion */
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            return $this->sendError('Alcance Capacitacion not found');
        }

        return $this->sendResponse($alcanceCapacitacion->toArray(), 'Alcance Capacitacion retrieved successfully');
    }

    /**
     * Update the specified AlcanceCapacitacion in storage.
     * PUT/PATCH /alcanceCapacitacions/{id}
     *
     * @param int $id
     * @param UpdateAlcanceCapacitacionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAlcanceCapacitacionAPIRequest $request)
    {
        $input = $request->all();

        /** @var AlcanceCapacitacion $alcanceCapacitacion */
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            return $this->sendError('Alcance Capacitacion not found');
        }

        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->update($input, $id);

        return $this->sendResponse($alcanceCapacitacion->toArray(), 'AlcanceCapacitacion updated successfully');
    }

    /**
     * Remove the specified AlcanceCapacitacion from storage.
     * DELETE /alcanceCapacitacions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AlcanceCapacitacion $alcanceCapacitacion */
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            return $this->sendError('Alcance Capacitacion not found');
        }
        $capacitacion = Capacitacion::where('idCapacitacion','=',$id)->first();
        if(isset($capacitacion)){
            return $this->sendError('Alcance Capacitacion esta siendo usado');    
        } 
        $alcanceCapacitacion->delete();

        return $this->sendSuccess('Alcance Capacitacion deleted successfully');
    }
}
