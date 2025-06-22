<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGrupoFamiliarPersonaRequest;
use App\Http\Requests\UpdateGrupoFamiliarPersonaRequest;
use App\Repositories\GrupoFamiliarPersonaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class GrupoFamiliarPersonaController extends AppBaseController
{
    /** @var  GrupoFamiliarPersonaRepository */
    private $grupoFamiliarPersonaRepository;

    public function __construct(GrupoFamiliarPersonaRepository $grupoFamiliarPersonaRepo)
    {
        $this->grupoFamiliarPersonaRepository = $grupoFamiliarPersonaRepo;
    }

    /**
     * Display a listing of the GrupoFamiliarPersona.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->grupoFamiliarPersonaRepository->pushCriteria(new RequestCriteria($request));
        $grupoFamiliarPersonas = $this->grupoFamiliarPersonaRepository->all();

        return view('grupo_familiar_personas.index')
            ->with('grupoFamiliarPersonas', $grupoFamiliarPersonas);
    }

    /**
     * Show the form for creating a new GrupoFamiliarPersona.
     *
     * @return Response
     */
    public function create()
    {
        return view('grupo_familiar_personas.create');
    }

    /**
     * Store a newly created GrupoFamiliarPersona in storage.
     *
     * @param CreateGrupoFamiliarPersonaRequest $request
     *
     * @return Response
     */
    public function store(CreateGrupoFamiliarPersonaRequest $request)
    {
        $input = $request->all();

        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->create($input);

        Flash::success('Grupo Familiar Persona saved successfully.');

        return redirect(route('grupoFamiliarPersonas.index'));
    }

    /**
     * Display the specified GrupoFamiliarPersona.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            Flash::error('Grupo Familiar Persona not found');

            return redirect(route('grupoFamiliarPersonas.index'));
        }

        return view('grupo_familiar_personas.show')->with('grupoFamiliarPersona', $grupoFamiliarPersona);
    }

    /**
     * Show the form for editing the specified GrupoFamiliarPersona.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            Flash::error('Grupo Familiar Persona not found');

            return redirect(route('grupoFamiliarPersonas.index'));
        }

        return view('grupo_familiar_personas.edit')->with('grupoFamiliarPersona', $grupoFamiliarPersona);
    }

    /**
     * Update the specified GrupoFamiliarPersona in storage.
     *
     * @param  int              $id
     * @param UpdateGrupoFamiliarPersonaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGrupoFamiliarPersonaRequest $request)
    {
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            Flash::error('Grupo Familiar Persona not found');

            return redirect(route('grupoFamiliarPersonas.index'));
        }

        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->update($request->all(), $id);

        Flash::success('Grupo Familiar Persona updated successfully.');

        return redirect(route('grupoFamiliarPersonas.index'));
    }

    /**
     * Remove the specified GrupoFamiliarPersona from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $grupoFamiliarPersona = $this->grupoFamiliarPersonaRepository->findWithoutFail($id);

        if (empty($grupoFamiliarPersona)) {
            Flash::error('Grupo Familiar Persona not found');

            return redirect(route('grupoFamiliarPersonas.index'));
        }

        $this->grupoFamiliarPersonaRepository->delete($id);

        Flash::success('Grupo Familiar Persona deleted successfully.');

        return redirect(route('grupoFamiliarPersonas.index'));
    }
}
