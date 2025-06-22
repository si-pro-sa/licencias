<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoGrupoFuncionRequest;
use App\Http\Requests\UpdateTipoGrupoFuncionRequest;
use App\Repositories\TipoGrupoFuncionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TipoGrupoFuncionController extends AppBaseController
{
    /** @var  TipoGrupoFuncionRepository */
    private $tipoGrupoFuncionRepository;

    public function __construct(TipoGrupoFuncionRepository $tipoGrupoFuncionRepo)
    {
        $this->tipoGrupoFuncionRepository = $tipoGrupoFuncionRepo;
    }

    /**
     * Display a listing of the TipoGrupoFuncion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoGrupoFuncionRepository->pushCriteria(new RequestCriteria($request));
        $tipoGrupoFuncions = $this->tipoGrupoFuncionRepository->all();

        return view('tipo_grupo_funcions.index')
            ->with('tipoGrupoFuncions', $tipoGrupoFuncions);
    }

    /**
     * Show the form for creating a new TipoGrupoFuncion.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_grupo_funcions.create');
    }

    /**
     * Store a newly created TipoGrupoFuncion in storage.
     *
     * @param CreateTipoGrupoFuncionRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoGrupoFuncionRequest $request)
    {
        $input = $request->all();

        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->create($input);

        Flash::success('Tipo Grupo Funcion saved successfully.');

        return redirect(route('tipoGrupoFuncions.index'));
    }

    /**
     * Display the specified TipoGrupoFuncion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->findWithoutFail($id);

        if (empty($tipoGrupoFuncion)) {
            Flash::error('Tipo Grupo Funcion not found');

            return redirect(route('tipoGrupoFuncions.index'));
        }

        return view('tipo_grupo_funcions.show')->with('tipoGrupoFuncion', $tipoGrupoFuncion);
    }

    /**
     * Show the form for editing the specified TipoGrupoFuncion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->findWithoutFail($id);

        if (empty($tipoGrupoFuncion)) {
            Flash::error('Tipo Grupo Funcion not found');

            return redirect(route('tipoGrupoFuncions.index'));
        }

        return view('tipo_grupo_funcions.edit')->with('tipoGrupoFuncion', $tipoGrupoFuncion);
    }

    /**
     * Update the specified TipoGrupoFuncion in storage.
     *
     * @param  int              $id
     * @param UpdateTipoGrupoFuncionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoGrupoFuncionRequest $request)
    {
        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->findWithoutFail($id);

        if (empty($tipoGrupoFuncion)) {
            Flash::error('Tipo Grupo Funcion not found');

            return redirect(route('tipoGrupoFuncions.index'));
        }

        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->update($request->all(), $id);

        Flash::success('Tipo Grupo Funcion updated successfully.');

        return redirect(route('tipoGrupoFuncions.index'));
    }

    /**
     * Remove the specified TipoGrupoFuncion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoGrupoFuncion = $this->tipoGrupoFuncionRepository->findWithoutFail($id);

        if (empty($tipoGrupoFuncion)) {
            Flash::error('Tipo Grupo Funcion not found');

            return redirect(route('tipoGrupoFuncions.index'));
        }

        $this->tipoGrupoFuncionRepository->delete($id);

        Flash::success('Tipo Grupo Funcion deleted successfully.');

        return redirect(route('tipoGrupoFuncions.index'));
    }
}
