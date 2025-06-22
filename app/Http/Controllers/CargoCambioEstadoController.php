<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoCambioEstadoRequest;
use App\Http\Requests\UpdateCargoCambioEstadoRequest;
use App\Repositories\CargoCambioEstadoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoCambioEstadoController extends AppBaseController
{
    /** @var  CargoCambioEstadoRepository */
    private $cargoCambioEstadoRepository;

    public function __construct(CargoCambioEstadoRepository $cargoCambioEstadoRepo)
    {
        $this->cargoCambioEstadoRepository = $cargoCambioEstadoRepo;
    }

    /**
     * Display a listing of the CargoCambioEstado.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoCambioEstadoRepository->pushCriteria(new RequestCriteria($request));
        $cargoCambioEstados = $this->cargoCambioEstadoRepository->all();

        return view('cargo_cambio_estados.index')
            ->with('cargoCambioEstados', $cargoCambioEstados);
    }

    /**
     * Show the form for creating a new CargoCambioEstado.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_cambio_estados.create');
    }

    /**
     * Store a newly created CargoCambioEstado in storage.
     *
     * @param CreateCargoCambioEstadoRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoCambioEstadoRequest $request)
    {
        $input = $request->all();

        $cargoCambioEstado = $this->cargoCambioEstadoRepository->create($input);

        Flash::success('Cargo Cambio Estado saved successfully.');

        return redirect(route('cargoCambioEstados.index'));
    }

    /**
     * Display the specified CargoCambioEstado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoCambioEstado = $this->cargoCambioEstadoRepository->findWithoutFail($id);

        if (empty($cargoCambioEstado)) {
            Flash::error('Cargo Cambio Estado not found');

            return redirect(route('cargoCambioEstados.index'));
        }

        return view('cargo_cambio_estados.show')->with('cargoCambioEstado', $cargoCambioEstado);
    }

    /**
     * Show the form for editing the specified CargoCambioEstado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoCambioEstado = $this->cargoCambioEstadoRepository->findWithoutFail($id);

        if (empty($cargoCambioEstado)) {
            Flash::error('Cargo Cambio Estado not found');

            return redirect(route('cargoCambioEstados.index'));
        }

        return view('cargo_cambio_estados.edit')->with('cargoCambioEstado', $cargoCambioEstado);
    }

    /**
     * Update the specified CargoCambioEstado in storage.
     *
     * @param  int              $id
     * @param UpdateCargoCambioEstadoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoCambioEstadoRequest $request)
    {
        $cargoCambioEstado = $this->cargoCambioEstadoRepository->findWithoutFail($id);

        if (empty($cargoCambioEstado)) {
            Flash::error('Cargo Cambio Estado not found');

            return redirect(route('cargoCambioEstados.index'));
        }

        $cargoCambioEstado = $this->cargoCambioEstadoRepository->update($request->all(), $id);

        Flash::success('Cargo Cambio Estado updated successfully.');

        return redirect(route('cargoCambioEstados.index'));
    }

    /**
     * Remove the specified CargoCambioEstado from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoCambioEstado = $this->cargoCambioEstadoRepository->findWithoutFail($id);

        if (empty($cargoCambioEstado)) {
            Flash::error('Cargo Cambio Estado not found');

            return redirect(route('cargoCambioEstados.index'));
        }

        $this->cargoCambioEstadoRepository->delete($id);

        Flash::success('Cargo Cambio Estado deleted successfully.');

        return redirect(route('cargoCambioEstados.index'));
    }
}
