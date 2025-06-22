<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePeriodoAPIRequest;
use App\Http\Requests\API\UpdatePeriodoAPIRequest;
use App\Models\Periodo;
use App\Repositories\PeriodoRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class PeriodoController
 * @package App\Http\Controllers\API
 */

class PeriodoAPIController extends AppBaseController
{
    /** @var  PeriodoRepository */
    private $periodoRepository;

    public function __construct(PeriodoRepository $periodoRepo)
    {
        $this->periodoRepository = $periodoRepo;
    }

    /**
     * Display a listing of the Periodo.
     * GET|HEAD /periodos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->periodoRepository->pushCriteria(new RequestCriteria($request));
        $this->periodoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $periodos = $this->periodoRepository->all();

        return $this->sendResponse($periodos->toArray(), 'Periodos retrieved successfully');
    }

    /**
     * Store a newly created Periodo in storage.
     * POST /periodos
     *
     * @param CreatePeriodoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePeriodoAPIRequest $request)
    {
        $input = $request->all();

        $periodos = $this->periodoRepository->create($input);

        return $this->sendResponse($periodos->toArray(), 'Periodo saved successfully');
    }

    /**
     * Display the specified Periodo.
     * GET|HEAD /periodos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Periodo $periodo */
        $periodo = $this->periodoRepository->findWithoutFail($id);

        if (empty($periodo)) {
            return $this->sendError('Periodo not found');
        }

        return $this->sendResponse($periodo->toArray(), 'Periodo retrieved successfully');
    }

    /**
     * Update the specified Periodo in storage.
     * PUT/PATCH /periodos/{id}
     *
     * @param  int $id
     * @param UpdatePeriodoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePeriodoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Periodo $periodo */
        $periodo = $this->periodoRepository->findWithoutFail($id);

        if (empty($periodo)) {
            return $this->sendError('Periodo not found');
        }

        $periodo = $this->periodoRepository->update($input, $id);

        return $this->sendResponse($periodo->toArray(), 'Periodo updated successfully');
    }

    /**
     * Remove the specified Periodo from storage.
     * DELETE /periodos/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Periodo $periodo */
        $periodo = $this->periodoRepository->findWithoutFail($id);

        if (empty($periodo)) {
            return $this->sendError('Periodo not found');
        }

        $periodo->delete();

        return $this->sendResponse($id, 'Periodo deleted successfully');
    }

    public function searchPeriodo(Request $request)
    {
        $periodo = $this->periodoRepository->getPeriodo($request->get('periodo'),$request->get('min'));

        if (empty($periodo)) {
            return $this->sendError('Periodo not found');
        }

        return $this->sendResponse($periodo->toArray(), 'Periodo retrieved successfully',200);
    }

    /**
     * Muestro listado de períodos utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $periodos = Periodo::orderBy('idperiodo')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idperiodo, 'label' => $model->periodo];
        });
        return $periodos;
    }
}
