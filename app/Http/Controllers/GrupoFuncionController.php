<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGrupoFuncionRequest;
use App\Http\Requests\UpdateGrupoFuncionRequest;
use App\Repositories\GrupoFuncionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GrupoFuncionController extends AppBaseController
{
    /** @var  GrupoFuncionRepository */
    private $grupoFuncionRepository;

    public function __construct(GrupoFuncionRepository $grupoFuncionRepo)
    {
        $this->grupoFuncionRepository = $grupoFuncionRepo;
    }

    /**
     * Display a listing of the GrupoFuncion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->grupoFuncionRepository->pushCriteria(new RequestCriteria($request));
        $grupoFuncions = $this->grupoFuncionRepository->all();

        return view('grupo_funcions.index')
            ->with('grupoFuncions', $grupoFuncions);
    }

    /**
     * Show the form for creating a new GrupoFuncion.
     *
     * @return Response
     */
    public function create()
    {
        return view('grupo_funcions.create');
    }

    /**
     * Store a newly created GrupoFuncion in storage.
     *
     * @param CreateGrupoFuncionRequest $request
     *
     * @return Response
     */
    public function store(CreateGrupoFuncionRequest $request)
    {
        $input = $request->all();

        $grupoFuncion = $this->grupoFuncionRepository->create($input);

        Flash::success('Grupo Funcion saved successfully.');

        return redirect(route('grupoFuncions.index'));
    }

    /**
     * Display the specified GrupoFuncion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $grupoFuncion = $this->grupoFuncionRepository->findWithoutFail($id);

        if (empty($grupoFuncion)) {
            Flash::error('Grupo Funcion not found');

            return redirect(route('grupoFuncions.index'));
        }

        return view('grupo_funcions.show')->with('grupoFuncion', $grupoFuncion);
    }

    /**
     * Show the form for editing the specified GrupoFuncion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $grupoFuncion = $this->grupoFuncionRepository->findWithoutFail($id);

        if (empty($grupoFuncion)) {
            Flash::error('Grupo Funcion not found');

            return redirect(route('grupoFuncions.index'));
        }

        return view('grupo_funcions.edit')->with('grupoFuncion', $grupoFuncion);
    }

    /**
     * Update the specified GrupoFuncion in storage.
     *
     * @param  int              $id
     * @param UpdateGrupoFuncionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGrupoFuncionRequest $request)
    {
        $grupoFuncion = $this->grupoFuncionRepository->findWithoutFail($id);

        if (empty($grupoFuncion)) {
            Flash::error('Grupo Funcion not found');

            return redirect(route('grupoFuncions.index'));
        }

        $grupoFuncion = $this->grupoFuncionRepository->update($request->all(), $id);

        Flash::success('Grupo Funcion updated successfully.');

        return redirect(route('grupoFuncions.index'));
    }

    /**
     * Remove the specified GrupoFuncion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $grupoFuncion = $this->grupoFuncionRepository->findWithoutFail($id);

        if (empty($grupoFuncion)) {
            Flash::error('Grupo Funcion not found');

            return redirect(route('grupoFuncions.index'));
        }

        $this->grupoFuncionRepository->delete($id);

        Flash::success('Grupo Funcion deleted successfully.');

        return redirect(route('grupoFuncions.index'));
    }
}
