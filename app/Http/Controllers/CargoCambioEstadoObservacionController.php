<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoCambioEstadoObservacionRequest;
use App\Http\Requests\UpdateCargoCambioEstadoObservacionRequest;
use App\Repositories\CargoCambioEstadoObservacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoCambioEstadoObservacionController extends AppBaseController
{
    /** @var  CargoCambioEstadoObservacionRepository */
    private $cargoCambioEstadoObservacionRepository;

    public function __construct(CargoCambioEstadoObservacionRepository $cargoCambioEstadoObservacionRepo)
    {
        $this->cargoCambioEstadoObservacionRepository = $cargoCambioEstadoObservacionRepo;
    }

    /**
     * Display a listing of the CargoCambioEstadoObservacion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoCambioEstadoObservacionRepository->pushCriteria(new RequestCriteria($request));
        $cargoCambioEstadoObservacions = $this->cargoCambioEstadoObservacionRepository->all();

        return view('cargo_cambio_estado_observacions.index')
            ->with('cargoCambioEstadoObservacions', $cargoCambioEstadoObservacions);
    }

    /**
     * Show the form for creating a new CargoCambioEstadoObservacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_cambio_estado_observacions.create');
    }

    /**
     * Store a newly created CargoCambioEstadoObservacion in storage.
     *
     * @param CreateCargoCambioEstadoObservacionRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoCambioEstadoObservacionRequest $request)
    {
        $input = $request->all();

        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->create($input);

        Flash::success('Cargo Cambio Estado Observacion saved successfully.');

        return redirect(route('cargoCambioEstadoObservacions.index'));
    }

    /**
     * Display the specified CargoCambioEstadoObservacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->findWithoutFail($id);

        if (empty($cargoCambioEstadoObservacion)) {
            Flash::error('Cargo Cambio Estado Observacion not found');

            return redirect(route('cargoCambioEstadoObservacions.index'));
        }

        return view('cargo_cambio_estado_observacions.show')->with('cargoCambioEstadoObservacion', $cargoCambioEstadoObservacion);
    }

    /**
     * Show the form for editing the specified CargoCambioEstadoObservacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->findWithoutFail($id);

        if (empty($cargoCambioEstadoObservacion)) {
            Flash::error('Cargo Cambio Estado Observacion not found');

            return redirect(route('cargoCambioEstadoObservacions.index'));
        }

        return view('cargo_cambio_estado_observacions.edit')->with('cargoCambioEstadoObservacion', $cargoCambioEstadoObservacion);
    }

    /**
     * Update the specified CargoCambioEstadoObservacion in storage.
     *
     * @param  int              $id
     * @param UpdateCargoCambioEstadoObservacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoCambioEstadoObservacionRequest $request)
    {
        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->findWithoutFail($id);

        if (empty($cargoCambioEstadoObservacion)) {
            Flash::error('Cargo Cambio Estado Observacion not found');

            return redirect(route('cargoCambioEstadoObservacions.index'));
        }

        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->update($request->all(), $id);

        Flash::success('Cargo Cambio Estado Observacion updated successfully.');

        return redirect(route('cargoCambioEstadoObservacions.index'));
    }

    /**
     * Remove the specified CargoCambioEstadoObservacion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoCambioEstadoObservacion = $this->cargoCambioEstadoObservacionRepository->findWithoutFail($id);

        if (empty($cargoCambioEstadoObservacion)) {
            Flash::error('Cargo Cambio Estado Observacion not found');

            return redirect(route('cargoCambioEstadoObservacions.index'));
        }

        $this->cargoCambioEstadoObservacionRepository->delete($id);

        Flash::success('Cargo Cambio Estado Observacion deleted successfully.');

        return redirect(route('cargoCambioEstadoObservacions.index'));
    }
}
