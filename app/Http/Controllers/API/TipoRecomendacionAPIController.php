<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoRecomendacionAPIRequest;
use App\Http\Requests\API\UpdateTipoRecomendacionAPIRequest;
use App\Models\TipoEntrevista;
use App\Models\TipoRecomendacion;
use App\Repositories\TipoRecomendacionRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TipoRecomendacionController
 * @package App\Http\Controllers\API
 */
class TipoRecomendacionAPIController extends AppBaseController
{
    /** @var  TipoRecomendacionRepository */
    private $tipoRecomendacionRepository;

    public function __construct(TipoRecomendacionRepository $tipoRecomendacionRepo)
    {
        $this->tipoRecomendacionRepository = $tipoRecomendacionRepo;
    }

    /**
     * Display a listing of the TipoRecomendacion.
     * GET|HEAD /tipoRecomendacions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (User::tienePermiso('TipoRecomendacionAPIController-index')) {
            if (empty($request->get('idtipo_entrevista')) && $request->get('idtipo_entrevista') != 1) {
                $tipoRecomendacions = $this->tipoRecomendacionRepository->all(
                    $request->except(['skip', 'limit']),
                    $request->get('skip'),
                    $request->get('limit'),
                    ['idtipo_recomendacion', 'tiporecomendacion']
                );
            } else {
                $tipoRecomendacions = TipoRecomendacion::where('idtipo_recomendacion', '<>', 4)->get(['idtipo_recomendacion', 'tiporecomendacion']);
            }
            return $this->sendResponse($tipoRecomendacions->toArray(), 'Tipo Recomendacions retrieved successfully');
        } else {
            return $this->sendError('No tiene permisos', 403);
        }
    }

    /**
     * Store a newly created TipoRecomendacion in storage.
     * POST /tipoRecomendacions
     *
     * @param CreateTipoRecomendacionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoRecomendacionAPIRequest $request)
    {
        $input = $request->all();

        $tipoRecomendacion = $this->tipoRecomendacionRepository->create($input);

        return $this->sendResponse($tipoRecomendacion->toArray(), 'Tipo Recomendacion saved successfully');
    }

    /**
     * Display the specified TipoRecomendacion.
     * GET|HEAD /tipoRecomendacions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoRecomendacion $tipoRecomendacion */
        $tipoRecomendacion = $this->tipoRecomendacionRepository->find($id);

        if (empty($tipoRecomendacion)) {
            return $this->sendError('Tipo Recomendacion not found');
        }

        return $this->sendResponse($tipoRecomendacion->toArray(), 'Tipo Recomendacion retrieved successfully');
    }

    /**
     * Update the specified TipoRecomendacion in storage.
     * PUT/PATCH /tipoRecomendacions/{id}
     *
     * @param int $id
     * @param UpdateTipoRecomendacionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoRecomendacionAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoRecomendacion $tipoRecomendacion */
        $tipoRecomendacion = $this->tipoRecomendacionRepository->find($id);

        if (empty($tipoRecomendacion)) {
            return $this->sendError('Tipo Recomendacion not found');
        }

        $tipoRecomendacion = $this->tipoRecomendacionRepository->update($input, $id);

        return $this->sendResponse($tipoRecomendacion->toArray(), 'TipoRecomendacion updated successfully');
    }

    /**
     * Remove the specified TipoRecomendacion from storage.
     * DELETE /tipoRecomendacions/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var TipoRecomendacion $tipoRecomendacion */
        $tipoRecomendacion = $this->tipoRecomendacionRepository->find($id);

        if (empty($tipoRecomendacion)) {
            return $this->sendError('Tipo Recomendacion not found');
        }

        $tipoRecomendacion->delete();

        return $this->sendSuccess('Tipo Recomendacion deleted successfully');
    }
}
