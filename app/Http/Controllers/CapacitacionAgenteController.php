<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCapacitacionAgenteRequest;
use App\Http\Requests\UpdateCapacitacionAgenteRequest;
use App\Repositories\CapacitacionAgenteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CapacitacionAgenteController extends AppBaseController
{
    /** @var  CapacitacionAgenteRepository */
    private $capacitacionAgenteRepository;

    public function __construct(CapacitacionAgenteRepository $capacitacionAgenteRepo)
    {
        $this->capacitacionAgenteRepository = $capacitacionAgenteRepo;
    }

    /**
     * Display a listing of the CapacitacionAgente.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $capacitacionAgentes = $this->capacitacionAgenteRepository->all();

        return view('capacitacion_agentes.index')
            ->with('capacitacionAgentes', $capacitacionAgentes);
    }

    /**
     * Show the form for creating a new CapacitacionAgente.
     *
     * @return Response
     */
    public function create()
    {
        return view('capacitacion_agentes.create');
    }

    /**
     * Store a newly created CapacitacionAgente in storage.
     *
     * @param CreateCapacitacionAgenteRequest $request
     *
     * @return Response
     */
    public function store(CreateCapacitacionAgenteRequest $request)
    {
        $input = $request->all();

        $capacitacionAgente = $this->capacitacionAgenteRepository->create($input);

        Flash::success('Capacitacion Agente saved successfully.');

        return redirect(route('capacitacionAgentes.index'));
    }

    /**
     * Display the specified CapacitacionAgente.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            Flash::error('Capacitacion Agente not found');

            return redirect(route('capacitacionAgentes.index'));
        }

        return view('capacitacion_agentes.show')->with('capacitacionAgente', $capacitacionAgente);
    }

    /**
     * Show the form for editing the specified CapacitacionAgente.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            Flash::error('Capacitacion Agente not found');

            return redirect(route('capacitacionAgentes.index'));
        }

        return view('capacitacion_agentes.edit')->with('capacitacionAgente', $capacitacionAgente);
    }

    /**
     * Update the specified CapacitacionAgente in storage.
     *
     * @param int $id
     * @param UpdateCapacitacionAgenteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCapacitacionAgenteRequest $request)
    {
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            Flash::error('Capacitacion Agente not found');

            return redirect(route('capacitacionAgentes.index'));
        }

        $capacitacionAgente = $this->capacitacionAgenteRepository->update($request->all(), $id);

        Flash::success('Capacitacion Agente updated successfully.');

        return redirect(route('capacitacionAgentes.index'));
    }

    /**
     * Remove the specified CapacitacionAgente from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $capacitacionAgente = $this->capacitacionAgenteRepository->find($id);

        if (empty($capacitacionAgente)) {
            Flash::error('Capacitacion Agente not found');

            return redirect(route('capacitacionAgentes.index'));
        }

        $this->capacitacionAgenteRepository->delete($id);

        Flash::success('Capacitacion Agente deleted successfully.');

        return redirect(route('capacitacionAgentes.index'));
    }
}
