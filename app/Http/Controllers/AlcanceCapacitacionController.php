<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAlcanceCapacitacionRequest;
use App\Http\Requests\UpdateAlcanceCapacitacionRequest;
use App\Repositories\AlcanceCapacitacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class AlcanceCapacitacionController extends AppBaseController
{
    /** @var  AlcanceCapacitacionRepository */
    private $alcanceCapacitacionRepository;

    public function __construct(AlcanceCapacitacionRepository $alcanceCapacitacionRepo)
    {
        $this->alcanceCapacitacionRepository = $alcanceCapacitacionRepo;
    }

    /**
     * Display a listing of the AlcanceCapacitacion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $alcanceCapacitacions = $this->alcanceCapacitacionRepository->all();

        return view('alcance_capacitacions.index')
            ->with('alcanceCapacitacions', $alcanceCapacitacions);
    }

    /**
     * Show the form for creating a new AlcanceCapacitacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('alcance_capacitacions.create');
    }

    /**
     * Store a newly created AlcanceCapacitacion in storage.
     *
     * @param CreateAlcanceCapacitacionRequest $request
     *
     * @return Response
     */
    public function store(CreateAlcanceCapacitacionRequest $request)
    {
        $input = $request->all();

        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->create($input);

        Flash::success('Alcance Capacitacion saved successfully.');

        return redirect(route('alcanceCapacitacions.index'));
    }

    /**
     * Display the specified AlcanceCapacitacion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            Flash::error('Alcance Capacitacion not found');

            return redirect(route('alcanceCapacitacions.index'));
        }

        return view('alcance_capacitacions.show')->with('alcanceCapacitacion', $alcanceCapacitacion);
    }

    /**
     * Show the form for editing the specified AlcanceCapacitacion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            Flash::error('Alcance Capacitacion not found');

            return redirect(route('alcanceCapacitacions.index'));
        }

        return view('alcance_capacitacions.edit')->with('alcanceCapacitacion', $alcanceCapacitacion);
    }

    /**
     * Update the specified AlcanceCapacitacion in storage.
     *
     * @param int $id
     * @param UpdateAlcanceCapacitacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAlcanceCapacitacionRequest $request)
    {
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            Flash::error('Alcance Capacitacion not found');

            return redirect(route('alcanceCapacitacions.index'));
        }

        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->update($request->all(), $id);

        Flash::success('Alcance Capacitacion updated successfully.');

        return redirect(route('alcanceCapacitacions.index'));
    }

    /**
     * Remove the specified AlcanceCapacitacion from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $alcanceCapacitacion = $this->alcanceCapacitacionRepository->find($id);

        if (empty($alcanceCapacitacion)) {
            Flash::error('Alcance Capacitacion not found');

            return redirect(route('alcanceCapacitacions.index'));
        }

        $this->alcanceCapacitacionRepository->delete($id);

        Flash::success('Alcance Capacitacion deleted successfully.');

        return redirect(route('alcanceCapacitacions.index'));
    }
}
