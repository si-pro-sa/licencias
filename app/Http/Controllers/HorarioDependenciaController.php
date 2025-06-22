<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHorarioDependenciaRequest;
use App\Http\Requests\UpdateHorarioDependenciaRequest;
use App\Repositories\HorarioDependenciaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class HorarioDependenciaController extends AppBaseController
{
    /** @var  HorarioDependenciaRepository */
    private $horarioDependenciaRepository;

    public function __construct(HorarioDependenciaRepository $horarioDependenciaRepo)
    {
        $this->horarioDependenciaRepository = $horarioDependenciaRepo;
    }

    /**
     * Display a listing of the HorarioDependencia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->horarioDependenciaRepository->pushCriteria(new RequestCriteria($request));
        $horarioDependencias = $this->horarioDependenciaRepository->all();

        return view('horario_dependencias.index')
            ->with('horarioDependencias', $horarioDependencias);
    }

    /**
     * Show the form for creating a new HorarioDependencia.
     *
     * @return Response
     */
    public function create()
    {
        return view('horario_dependencias.create');
    }

    /**
     * Store a newly created HorarioDependencia in storage.
     *
     * @param CreateHorarioDependenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateHorarioDependenciaRequest $request)
    {
        $input = $request->all();

        $horarioDependencia = $this->horarioDependenciaRepository->create($input);

        Flash::success('Horario Dependencia saved successfully.');

        return redirect(route('horarioDependencias.index'));
    }

    /**
     * Display the specified HorarioDependencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $horarioDependencia = $this->horarioDependenciaRepository->findWithoutFail($id);

        if (empty($horarioDependencia)) {
            Flash::error('Horario Dependencia not found');

            return redirect(route('horarioDependencias.index'));
        }

        return view('horario_dependencias.show')->with('horarioDependencia', $horarioDependencia);
    }

    /**
     * Show the form for editing the specified HorarioDependencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $horarioDependencia = $this->horarioDependenciaRepository->findWithoutFail($id);

        if (empty($horarioDependencia)) {
            Flash::error('Horario Dependencia not found');

            return redirect(route('horarioDependencias.index'));
        }

        return view('horario_dependencias.edit')->with('horarioDependencia', $horarioDependencia);
    }

    /**
     * Update the specified HorarioDependencia in storage.
     *
     * @param  int              $id
     * @param UpdateHorarioDependenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHorarioDependenciaRequest $request)
    {
        $horarioDependencia = $this->horarioDependenciaRepository->findWithoutFail($id);

        if (empty($horarioDependencia)) {
            Flash::error('Horario Dependencia not found');

            return redirect(route('horarioDependencias.index'));
        }

        $horarioDependencia = $this->horarioDependenciaRepository->update($request->all(), $id);

        Flash::success('Horario Dependencia updated successfully.');

        return redirect(route('horarioDependencias.index'));
    }

    /**
     * Remove the specified HorarioDependencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $horarioDependencia = $this->horarioDependenciaRepository->findWithoutFail($id);

        if (empty($horarioDependencia)) {
            Flash::error('Horario Dependencia not found');

            return redirect(route('horarioDependencias.index'));
        }

        $this->horarioDependenciaRepository->delete($id);

        Flash::success('Horario Dependencia deleted successfully.');

        return redirect(route('horarioDependencias.index'));
    }
}
