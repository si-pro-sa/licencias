<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHorarioPuestoRequest;
use App\Http\Requests\UpdateHorarioPuestoRequest;
use App\Repositories\HorarioPuestoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class HorarioPuestoController extends AppBaseController
{
    /** @var  HorarioPuestoRepository */
    private $horarioPuestoRepository;

    public function __construct(HorarioPuestoRepository $horarioPuestoRepo)
    {
        $this->horarioPuestoRepository = $horarioPuestoRepo;
    }

    /**
     * Display a listing of the HorarioPuesto.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->horarioPuestoRepository->pushCriteria(new RequestCriteria($request));
        $horarioPuestos = $this->horarioPuestoRepository->all();

        return view('horario_puestos.index')
            ->with('horarioPuestos', $horarioPuestos);
    }

    /**
     * Show the form for creating a new HorarioPuesto.
     *
     * @return Response
     */
    public function create()
    {
        return view('horario_puestos.create');
    }

    /**
     * Store a newly created HorarioPuesto in storage.
     *
     * @param CreateHorarioPuestoRequest $request
     *
     * @return Response
     */
    public function store(CreateHorarioPuestoRequest $request)
    {
        $input = $request->all();

        $horarioPuesto = $this->horarioPuestoRepository->create($input);

        Flash::success('Horario Puesto saved successfully.');

        return redirect(route('horarioPuestos.index'));
    }

    /**
     * Display the specified HorarioPuesto.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $horarioPuesto = $this->horarioPuestoRepository->findWithoutFail($id);

        if (empty($horarioPuesto)) {
            Flash::error('Horario Puesto not found');

            return redirect(route('horarioPuestos.index'));
        }

        return view('horario_puestos.show')->with('horarioPuesto', $horarioPuesto);
    }

    /**
     * Show the form for editing the specified HorarioPuesto.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $horarioPuesto = $this->horarioPuestoRepository->findWithoutFail($id);

        if (empty($horarioPuesto)) {
            Flash::error('Horario Puesto not found');

            return redirect(route('horarioPuestos.index'));
        }

        return view('horario_puestos.edit')->with('horarioPuesto', $horarioPuesto);
    }

    /**
     * Update the specified HorarioPuesto in storage.
     *
     * @param  int              $id
     * @param UpdateHorarioPuestoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHorarioPuestoRequest $request)
    {
        $horarioPuesto = $this->horarioPuestoRepository->findWithoutFail($id);

        if (empty($horarioPuesto)) {
            Flash::error('Horario Puesto not found');

            return redirect(route('horarioPuestos.index'));
        }

        $horarioPuesto = $this->horarioPuestoRepository->update($request->all(), $id);

        Flash::success('Horario Puesto updated successfully.');

        return redirect(route('horarioPuestos.index'));
    }

    /**
     * Remove the specified HorarioPuesto from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $horarioPuesto = $this->horarioPuestoRepository->findWithoutFail($id);

        if (empty($horarioPuesto)) {
            Flash::error('Horario Puesto not found');

            return redirect(route('horarioPuestos.index'));
        }

        $this->horarioPuestoRepository->delete($id);

        Flash::success('Horario Puesto deleted successfully.');

        return redirect(route('horarioPuestos.index'));
    }
}
