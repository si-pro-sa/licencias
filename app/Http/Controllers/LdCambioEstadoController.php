<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLdCambioEstadoRequest;
use App\Http\Requests\UpdateLdCambioEstadoRequest;
use App\Repositories\LdCambioEstadoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LdCambioEstadoController extends AppBaseController
{
    /** @var  LdCambioEstadoRepository */
    private $ldCambioEstadoRepository;

    public function __construct(LdCambioEstadoRepository $ldCambioEstadoRepo)
    {
        $this->ldCambioEstadoRepository = $ldCambioEstadoRepo;
    }

    /**
     * Display a listing of the LdCambioEstado.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ldCambioEstados = $this->ldCambioEstadoRepository->all();

        return view('ld_cambio_estados.index')
            ->with('ldCambioEstados', $ldCambioEstados);
    }

    /**
     * Show the form for creating a new LdCambioEstado.
     *
     * @return Response
     */
    public function create()
    {
        return view('ld_cambio_estados.create');
    }

    /**
     * Store a newly created LdCambioEstado in storage.
     *
     * @param CreateLdCambioEstadoRequest $request
     *
     * @return Response
     */
    public function store(CreateLdCambioEstadoRequest $request)
    {
        $input = $request->all();

        $ldCambioEstado = $this->ldCambioEstadoRepository->create($input);

        Flash::success('Ld Cambio Estado saved successfully.');

        return redirect(route('ldCambioEstados.index'));
    }

    /**
     * Display the specified LdCambioEstado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ldCambioEstado = $this->ldCambioEstadoRepository->find($id);

        if (empty($ldCambioEstado)) {
            Flash::error('Ld Cambio Estado not found');

            return redirect(route('ldCambioEstados.index'));
        }

        return view('ld_cambio_estados.show')->with('ldCambioEstado', $ldCambioEstado);
    }

    /**
     * Show the form for editing the specified LdCambioEstado.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ldCambioEstado = $this->ldCambioEstadoRepository->find($id);

        if (empty($ldCambioEstado)) {
            Flash::error('Ld Cambio Estado not found');

            return redirect(route('ldCambioEstados.index'));
        }

        return view('ld_cambio_estados.edit')->with('ldCambioEstado', $ldCambioEstado);
    }

    /**
     * Update the specified LdCambioEstado in storage.
     *
     * @param int $id
     * @param UpdateLdCambioEstadoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLdCambioEstadoRequest $request)
    {
        $ldCambioEstado = $this->ldCambioEstadoRepository->find($id);

        if (empty($ldCambioEstado)) {
            Flash::error('Ld Cambio Estado not found');

            return redirect(route('ldCambioEstados.index'));
        }

        $ldCambioEstado = $this->ldCambioEstadoRepository->update($request->all(), $id);

        Flash::success('Ld Cambio Estado updated successfully.');

        return redirect(route('ldCambioEstados.index'));
    }

    /**
     * Remove the specified LdCambioEstado from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ldCambioEstado = $this->ldCambioEstadoRepository->find($id);

        if (empty($ldCambioEstado)) {
            Flash::error('Ld Cambio Estado not found');

            return redirect(route('ldCambioEstados.index'));
        }

        $this->ldCambioEstadoRepository->delete($id);

        Flash::success('Ld Cambio Estado deleted successfully.');

        return redirect(route('ldCambioEstados.index'));
    }
}
