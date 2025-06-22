<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGuardiaLineaRequest;
use App\Http\Requests\UpdateGuardiaLineaRequest;
use App\Repositories\GuardiaLineaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class GuardiaLineaController extends AppBaseController
{
    /** @var  GuardiaLineaRepository */
    private $guardiaLineaRepository;

    public function __construct(GuardiaLineaRepository $guardiaLineaRepo)
    {
        $this->guardiaLineaRepository = $guardiaLineaRepo;
    }

    /**
     * Display a listing of the GuardiaLinea.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $guardiaLineas = $this->guardiaLineaRepository->all();

        return view('guardia_lineas.index')
            ->with('guardiaLineas', $guardiaLineas);
    }

    /**
     * Show the form for creating a new GuardiaLinea.
     *
     * @return Response
     */
    public function create()
    {
        return view('guardia_lineas.create');
    }

    /**
     * Store a newly created GuardiaLinea in storage.
     *
     * @param CreateGuardiaLineaRequest $request
     *
     * @return Response
     */
    public function store(CreateGuardiaLineaRequest $request)
    {
        $input = $request->all();

        $guardiaLinea = $this->guardiaLineaRepository->create($input);

        Flash::success('Guardia Linea saved successfully.');

        return redirect(route('guardiaLineas.index'));
    }

    /**
     * Display the specified GuardiaLinea.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $guardiaLinea = $this->guardiaLineaRepository->find($id);

        if (empty($guardiaLinea)) {
            Flash::error('Guardia Linea not found');

            return redirect(route('guardiaLineas.index'));
        }

        return view('guardia_lineas.show')->with('guardiaLinea', $guardiaLinea);
    }

    /**
     * Show the form for editing the specified GuardiaLinea.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $guardiaLinea = $this->guardiaLineaRepository->find($id);

        if (empty($guardiaLinea)) {
            Flash::error('Guardia Linea not found');

            return redirect(route('guardiaLineas.index'));
        }

        return view('guardia_lineas.edit')->with('guardiaLinea', $guardiaLinea);
    }

    /**
     * Update the specified GuardiaLinea in storage.
     *
     * @param int $id
     * @param UpdateGuardiaLineaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGuardiaLineaRequest $request)
    {
        $guardiaLinea = $this->guardiaLineaRepository->find($id);

        if (empty($guardiaLinea)) {
            Flash::error('Guardia Linea not found');

            return redirect(route('guardiaLineas.index'));
        }

        $guardiaLinea = $this->guardiaLineaRepository->update($request->all(), $id);

        Flash::success('Guardia Linea updated successfully.');

        return redirect(route('guardiaLineas.index'));
    }

    /**
     * Remove the specified GuardiaLinea from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $guardiaLinea = $this->guardiaLineaRepository->find($id);

        if (empty($guardiaLinea)) {
            Flash::error('Guardia Linea not found');

            return redirect(route('guardiaLineas.index'));
        }

        $this->guardiaLineaRepository->delete($id);

        Flash::success('Guardia Linea deleted successfully.');

        return redirect(route('guardiaLineas.index'));
    }
}
