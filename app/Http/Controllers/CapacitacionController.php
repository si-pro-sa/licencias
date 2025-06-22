<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCapacitacionRequest;
use App\Http\Requests\UpdateCapacitacionRequest;
use App\Repositories\CapacitacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CapacitacionController extends AppBaseController
{
    /** @var  CapacitacionRepository */
    private $capacitacionRepository;

    public function __construct(CapacitacionRepository $capacitacionRepo)
    {
        $this->capacitacionRepository = $capacitacionRepo;
    }

    /**
     * Display a listing of the Capacitacion.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $capacitacions = $this->capacitacionRepository->all();

        return view('capacitacions.index')
            ->with('capacitacions', $capacitacions);
    }

    /**
     * Show the form for creating a new Capacitacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('capacitacions.create');
    }

    /**
     * Store a newly created Capacitacion in storage.
     *
     * @param CreateCapacitacionRequest $request
     *
     * @return Response
     */
    public function store(CreateCapacitacionRequest $request)
    {
        $input = $request->all();

        $capacitacion = $this->capacitacionRepository->create($input);

        Flash::success('Capacitacion saved successfully.');

        return redirect(route('capacitacions.index'));
    }

    /**
     * Display the specified Capacitacion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            Flash::error('Capacitacion not found');

            return redirect(route('capacitacions.index'));
        }

        return view('capacitacions.show')->with('capacitacion', $capacitacion);
    }

    /**
     * Show the form for editing the specified Capacitacion.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            Flash::error('Capacitacion not found');

            return redirect(route('capacitacions.index'));
        }

        return view('capacitacions.edit')->with('capacitacion', $capacitacion);
    }

    /**
     * Update the specified Capacitacion in storage.
     *
     * @param int $id
     * @param UpdateCapacitacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCapacitacionRequest $request)
    {
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            Flash::error('Capacitacion not found');

            return redirect(route('capacitacions.index'));
        }

        $capacitacion = $this->capacitacionRepository->update($request->all(), $id);

        Flash::success('Capacitacion updated successfully.');

        return redirect(route('capacitacions.index'));
    }

    /**
     * Remove the specified Capacitacion from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            Flash::error('Capacitacion not found');

            return redirect(route('capacitacions.index'));
        }

        $this->capacitacionRepository->delete($id);

        Flash::success('Capacitacion deleted successfully.');

        return redirect(route('capacitacions.index'));
    }
}
