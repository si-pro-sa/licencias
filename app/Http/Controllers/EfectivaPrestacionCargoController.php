<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEfectivaPrestacionCargoRequest;
use App\Http\Requests\UpdateEfectivaPrestacionCargoRequest;
use App\Repositories\EfectivaPrestacionCargoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class EfectivaPrestacionCargoController extends AppBaseController
{
    /** @var  EfectivaPrestacionCargoRepository */
    private $efectivaPrestacionCargoRepository;

    public function __construct(EfectivaPrestacionCargoRepository $efectivaPrestacionCargoRepo)
    {
        $this->efectivaPrestacionCargoRepository = $efectivaPrestacionCargoRepo;
    }

    /**
     * Display a listing of the EfectivaPrestacionCargo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $efectivaPrestacionCargos = $this->efectivaPrestacionCargoRepository->all();

        return view('efectiva_prestacion_cargos.index')
            ->with('efectivaPrestacionCargos', $efectivaPrestacionCargos);
    }

    /**
     * Show the form for creating a new EfectivaPrestacionCargo.
     *
     * @return Response
     */
    public function create()
    {
        return view('efectiva_prestacion_cargos.create');
    }

    /**
     * Store a newly created EfectivaPrestacionCargo in storage.
     *
     * @param CreateEfectivaPrestacionCargoRequest $request
     *
     * @return Response
     */
    public function store(CreateEfectivaPrestacionCargoRequest $request)
    {
        $input = $request->all();

        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->create($input);

        Flash::success('Efectiva Prestacion Cargo saved successfully.');

        return redirect(route('efectivaPrestacionCargos.index'));
    }

    /**
     * Display the specified EfectivaPrestacionCargo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            Flash::error('Efectiva Prestacion Cargo not found');

            return redirect(route('efectivaPrestacionCargos.index'));
        }

        return view('efectiva_prestacion_cargos.show')->with('efectivaPrestacionCargo', $efectivaPrestacionCargo);
    }

    /**
     * Show the form for editing the specified EfectivaPrestacionCargo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            Flash::error('Efectiva Prestacion Cargo not found');

            return redirect(route('efectivaPrestacionCargos.index'));
        }

        return view('efectiva_prestacion_cargos.edit')->with('efectivaPrestacionCargo', $efectivaPrestacionCargo);
    }

    /**
     * Update the specified EfectivaPrestacionCargo in storage.
     *
     * @param int $id
     * @param UpdateEfectivaPrestacionCargoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEfectivaPrestacionCargoRequest $request)
    {
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            Flash::error('Efectiva Prestacion Cargo not found');

            return redirect(route('efectivaPrestacionCargos.index'));
        }

        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->update($request->all(), $id);

        Flash::success('Efectiva Prestacion Cargo updated successfully.');

        return redirect(route('efectivaPrestacionCargos.index'));
    }

    /**
     * Remove the specified EfectivaPrestacionCargo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $efectivaPrestacionCargo = $this->efectivaPrestacionCargoRepository->find($id);

        if (empty($efectivaPrestacionCargo)) {
            Flash::error('Efectiva Prestacion Cargo not found');

            return redirect(route('efectivaPrestacionCargos.index'));
        }

        $this->efectivaPrestacionCargoRepository->delete($id);

        Flash::success('Efectiva Prestacion Cargo deleted successfully.');

        return redirect(route('efectivaPrestacionCargos.index'));
    }
}
