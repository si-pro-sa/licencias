<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoEventoAPIRequest;
use App\Http\Requests\API\UpdateTipoEventoAPIRequest;
use App\Models\TipoEvento;
use App\Repositories\TipoEventoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Capacitacion;
use Response;

/**
 * Class TipoEventoController
 * @package App\Http\Controllers\API
 */

class TipoEventoAPIController extends AppBaseController
{
    /** @var  TipoEventoRepository */
    private $tipoEventoRepository;

    public function __construct(TipoEventoRepository $tipoEventoRepo)
    {
        $this->tipoEventoRepository = $tipoEventoRepo;
    }

    /**
     * Display a listing of the TipoEvento.
     * GET|HEAD /tipoEventos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tipoEventos = $this->tipoEventoRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($tipoEventos->toArray(), 'Tipo Eventos retrieved successfully');
    }

    /**
     * Store a newly created TipoEvento in storage.
     * POST /tipoEventos
     *
     * @param CreateTipoEventoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoEventoAPIRequest $request)
    {
        $input = $request->all();

        $tipoEvento = $this->tipoEventoRepository->create($input);

        return $this->sendResponse($tipoEvento->toArray(), 'Tipo Evento saved successfully');
    }

    /**
     * Display the specified TipoEvento.
     * GET|HEAD /tipoEventos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoEvento $tipoEvento */
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            return $this->sendError('Tipo Evento not found');
        }

        return $this->sendResponse($tipoEvento->toArray(), 'Tipo Evento retrieved successfully');
    }

    /**
     * Update the specified TipoEvento in storage.
     * PUT/PATCH /tipoEventos/{id}
     *
     * @param int $id
     * @param UpdateTipoEventoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoEventoAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoEvento $tipoEvento */
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            return $this->sendError('Tipo Evento not found');
        }

        $tipoEvento = $this->tipoEventoRepository->update($input, $id);

        return $this->sendResponse($tipoEvento->toArray(), 'TipoEvento updated successfully');
    }

    /**
     * Remove the specified TipoEvento from storage.
     * DELETE /tipoEventos/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoEvento $tipoEvento */
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            return $this->sendError('Tipo Evento not found');
        }
        $capacitacion = Capacitacion::where('idTipoEvento','=',$id)->first();
        if(isset($capacitacion)){
            return $this->sendError('Tipo Evento esta siendo usado');    
        } 
        $tipoEvento->delete();

        return $this->sendSuccess('Tipo Evento deleted successfully');
    }
}
