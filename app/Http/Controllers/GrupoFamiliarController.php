<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGrupoFamiliarRequest;
use App\Http\Requests\UpdateGrupoFamiliarRequest;
use App\Repositories\GrupoFamiliarRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GrupoFamiliarController extends AppBaseController
{
    /** @var  GrupoFamiliarRepository */
    private $grupoFamiliarRepository;

    public function __construct(GrupoFamiliarRepository $grupoFamiliarRepo)
    {
        $this->grupoFamiliarRepository = $grupoFamiliarRepo;
    }

    /**
     * Display a listing of the GrupoFamiliar.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->grupoFamiliarRepository->pushCriteria(new RequestCriteria($request));
        $grupoFamiliars = $this->grupoFamiliarRepository->all();

        return view('grupo_familiars.index')
            ->with('grupoFamiliars', $grupoFamiliars);
    }

    /**
     * Show the form for creating a new GrupoFamiliar.
     *
     * @return Response
     */
    public function create()
    {
        return view('grupo_familiars.create');
    }

    /**
     * Store a newly created GrupoFamiliar in storage.
     *
     * @param CreateGrupoFamiliarRequest $request
     *
     * @return Response
     */
    public function store(CreateGrupoFamiliarRequest $request)
    {
        $input = $request->all();

        $grupoFamiliar = $this->grupoFamiliarRepository->create($input);

        Flash::success('Grupo Familiar saved successfully.');

        return redirect(route('grupoFamiliars.index'));
    }

    /**
     * Display the specified GrupoFamiliar.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            Flash::error('Grupo Familiar not found');

            return redirect(route('grupoFamiliars.index'));
        }

        return view('grupo_familiars.show')->with('grupoFamiliar', $grupoFamiliar);
    }

    /**
     * Show the form for editing the specified GrupoFamiliar.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            Flash::error('Grupo Familiar not found');

            return redirect(route('grupoFamiliars.index'));
        }

        return view('grupo_familiars.edit')->with('grupoFamiliar', $grupoFamiliar);
    }

    /**
     * Update the specified GrupoFamiliar in storage.
     *
     * @param  int              $id
     * @param UpdateGrupoFamiliarRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGrupoFamiliarRequest $request)
    {
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            Flash::error('Grupo Familiar not found');

            return redirect(route('grupoFamiliars.index'));
        }

        $grupoFamiliar = $this->grupoFamiliarRepository->update($request->all(), $id);

        Flash::success('Grupo Familiar updated successfully.');

        return redirect(route('grupoFamiliars.index'));
    }

    /**
     * Remove the specified GrupoFamiliar from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $grupoFamiliar = $this->grupoFamiliarRepository->findWithoutFail($id);

        if (empty($grupoFamiliar)) {
            Flash::error('Grupo Familiar not found');

            return redirect(route('grupoFamiliars.index'));
        }

        $this->grupoFamiliarRepository->delete($id);

        Flash::success('Grupo Familiar deleted successfully.');

        return redirect(route('grupoFamiliars.index'));
    }
}
