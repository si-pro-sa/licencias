<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoFuncionAPIRequest;
use App\Http\Requests\API\UpdateTipoFuncionAPIRequest;
use App\Models\TipoFuncion;
use App\Repositories\TipoFuncionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoFuncionController
 * @package App\Http\Controllers\API
 */

class TipoFuncionAPIController extends AppBaseController
{
    /** @var  TipoFuncionRepository */
    private $tipoFuncionRepository;

    public function __construct(TipoFuncionRepository $tipoFuncionRepo)
    {
        $this->tipoFuncionRepository = $tipoFuncionRepo;
    }

    /**
     * Display a listing of the TipoFuncion.
     * GET|HEAD /tipoFuncions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoFuncionRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoFuncionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoFuncions = $this->tipoFuncionRepository->all();

        return $this->sendResponse($tipoFuncions->toArray(), 'Tipo Funcions retrieved successfully');
    }

    /**
     * Store a newly created TipoFuncion in storage.
     * POST /tipoFuncions
     *
     * @param CreateTipoFuncionAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoFuncionAPIRequest $request)
    {
        $input = $request->all();

        $tipoFuncions = $this->tipoFuncionRepository->create($input);

        return $this->sendResponse($tipoFuncions->toArray(), 'Tipo Funcion saved successfully');
    }

    /**
     * Display the specified TipoFuncion.
     * GET|HEAD /tipoFuncions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoFuncion $tipoFuncion */
        $tipoFuncion = $this->tipoFuncionRepository->findWithoutFail($id);

        if (empty($tipoFuncion)) {
            return $this->sendError('Tipo Funcion not found');
        }

        return $this->sendResponse($tipoFuncion->toArray(), 'Tipo Funcion retrieved successfully');
    }

    /**
     * Update the specified TipoFuncion in storage.
     * PUT/PATCH /tipoFuncions/{id}
     *
     * @param  int $id
     * @param UpdateTipoFuncionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoFuncionAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoFuncion $tipoFuncion */
        $tipoFuncion = $this->tipoFuncionRepository->findWithoutFail($id);

        if (empty($tipoFuncion)) {
            return $this->sendError('Tipo Funcion not found');
        }

        $tipoFuncion = $this->tipoFuncionRepository->update($input, $id);

        return $this->sendResponse($tipoFuncion->toArray(), 'TipoFuncion updated successfully');
    }

    /**
     * Remove the specified TipoFuncion from storage.
     * DELETE /tipoFuncions/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoFuncion $tipoFuncion */
        $tipoFuncion = $this->tipoFuncionRepository->findWithoutFail($id);

        if (empty($tipoFuncion)) {
            return $this->sendError('Tipo Funcion not found');
        }

        $tipoFuncion->delete();

        return $this->sendResponse($id, 'Tipo Funcion deleted successfully');
    }

    public function searchTipoFuncion(Request $request)
    {
        $tipo_funcion = $this->tipoFuncionRepository->getTipoFuncion($request->get('tipofuncion'));

        if (empty($tipo_funcion)) {
            return $this->sendError('No se encontró la función');
        }

        return $this->sendResponse($tipo_funcion->toArray(), 'Tipo Funcion retrieved successfully',200);
    }

    public function listar()
    {
        $funciones = TipoFuncion::orderBy('tipofuncion')->get();
        $data = [];
        foreach($funciones as $key => $funcion)
        {
            if($funcion->idtipo_funcion === 490)
                $funcion->tipofuncion = "Otro";

            $data[$key] = [
                'value' => $funcion->idtipo_funcion,
                'label' => $funcion->tipofuncion,
            ];
        }

        return response(['data' => $data],200);
    }

    /**
     * Muestro listado de tipos de función utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tipoFunciones = TipoFuncion::orderBy('tipofuncion')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return $model->idtipo_funcion === 490 ? ['value' => $model->idtipo_funcion, 'label' => 'Otro'] : ['value' => $model->idtipo_funcion, 'label' => $model->tipofuncion];
        });
        return $tipoFunciones;
    }
}
