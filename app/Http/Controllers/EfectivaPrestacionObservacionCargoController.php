<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEfectivaPrestacionObservacionCargoRequest;
use App\Http\Requests\UpdateEfectivaPrestacionObservacionCargoRequest;
use App\Repositories\EfectivaPrestacionObservacionCargoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EfectivaPrestacionObservacionCargoController extends AppBaseController
{
    /** @var  EfectivaPrestacionObservacionCargoRepository */
    private $efectivaPrestacionObservacionCargoRepository;

    public function __construct(EfectivaPrestacionObservacionCargoRepository $efectivaPrestacionObservacionCargoRepo)
    {
        $this->efectivaPrestacionObservacionCargoRepository = $efectivaPrestacionObservacionCargoRepo;
    }

    /**
     * Display a listing of the EfectivaPrestacionObservacionCargo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $efectivaPrestacionObservacionCargos = $this->efectivaPrestacionObservacionCargoRepository->all();

        return view('efectiva_prestacion_observacion_cargos.index')
            ->with('efectivaPrestacionObservacionCargos', $efectivaPrestacionObservacionCargos);
    }

    /**
     * Show the form for creating a new EfectivaPrestacionObservacionCargo.
     *
     * @return Response
     */
    public function create()
    {
        return view('efectiva_prestacion_observacion_cargos.create');
    }

    /**
     * Store a newly created EfectivaPrestacionObservacionCargo in storage.
     *
     * @param CreateEfectivaPrestacionObservacionCargoRequest $request
     *
     * @return Response
     */
    public function store(CreateEfectivaPrestacionObservacionCargoRequest $request)
    {
        $input = $request->all();

        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->create($input);

        Flash::success('Efectiva Prestacion Observacion Cargo saved successfully.');

        return redirect(route('efectivaPrestacionObservacionCargos.index'));
    }

    /**
     * Display the specified EfectivaPrestacionObservacionCargo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            Flash::error('Efectiva Prestacion Observacion Cargo not found');

            return redirect(route('efectivaPrestacionObservacionCargos.index'));
        }

        return view('efectiva_prestacion_observacion_cargos.show')->with('efectivaPrestacionObservacionCargo', $efectivaPrestacionObservacionCargo);
    }

    /**
     * Show the form for editing the specified EfectivaPrestacionObservacionCargo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            Flash::error('Efectiva Prestacion Observacion Cargo not found');

            return redirect(route('efectivaPrestacionObservacionCargos.index'));
        }

        return view('efectiva_prestacion_observacion_cargos.edit')->with('efectivaPrestacionObservacionCargo', $efectivaPrestacionObservacionCargo);
    }

    /**
     * Update the specified EfectivaPrestacionObservacionCargo in storage.
     *
     * @param int $id
     * @param UpdateEfectivaPrestacionObservacionCargoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEfectivaPrestacionObservacionCargoRequest $request)
    {
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            Flash::error('Efectiva Prestacion Observacion Cargo not found');

            return redirect(route('efectivaPrestacionObservacionCargos.index'));
        }

        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->update($request->all(), $id);

        Flash::success('Efectiva Prestacion Observacion Cargo updated successfully.');

        return redirect(route('efectivaPrestacionObservacionCargos.index'));
    }

    /**
     * Remove the specified EfectivaPrestacionObservacionCargo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $efectivaPrestacionObservacionCargo = $this->efectivaPrestacionObservacionCargoRepository->find($id);

        if (empty($efectivaPrestacionObservacionCargo)) {
            Flash::error('Efectiva Prestacion Observacion Cargo not found');

            return redirect(route('efectivaPrestacionObservacionCargos.index'));
        }

        $this->efectivaPrestacionObservacionCargoRepository->delete($id);

        Flash::success('Efectiva Prestacion Observacion Cargo deleted successfully.');

        return redirect(route('efectivaPrestacionObservacionCargos.index'));
    }
}
