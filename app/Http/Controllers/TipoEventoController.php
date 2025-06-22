<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Repositories\TipoEventoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TipoEventoController extends AppBaseController
{
    /** @var  TipoEventoRepository */
    private $tipoEventoRepository;

    public function __construct(TipoEventoRepository $tipoEventoRepo)
    {
        $this->tipoEventoRepository = $tipoEventoRepo;
    }

    /**
     * Display a listing of the TipoEvento.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tipoEventos = $this->tipoEventoRepository->all();

        return view('tipo_eventos.index')
            ->with('tipoEventos', $tipoEventos);
    }

    /**
     * Show the form for creating a new TipoEvento.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_eventos.create');
    }

    /**
     * Store a newly created TipoEvento in storage.
     *
     * @param CreateTipoEventoRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoEventoRequest $request)
    {
        $input = $request->all();

        $tipoEvento = $this->tipoEventoRepository->create($input);

        Flash::success('Tipo Evento saved successfully.');

        return redirect(route('tipoEventos.index'));
    }

    /**
     * Display the specified TipoEvento.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            Flash::error('Tipo Evento not found');

            return redirect(route('tipoEventos.index'));
        }

        return view('tipo_eventos.show')->with('tipoEvento', $tipoEvento);
    }

    /**
     * Show the form for editing the specified TipoEvento.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            Flash::error('Tipo Evento not found');

            return redirect(route('tipoEventos.index'));
        }

        return view('tipo_eventos.edit')->with('tipoEvento', $tipoEvento);
    }

    /**
     * Update the specified TipoEvento in storage.
     *
     * @param int $id
     * @param UpdateTipoEventoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoEventoRequest $request)
    {
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            Flash::error('Tipo Evento not found');

            return redirect(route('tipoEventos.index'));
        }

        $tipoEvento = $this->tipoEventoRepository->update($request->all(), $id);

        Flash::success('Tipo Evento updated successfully.');

        return redirect(route('tipoEventos.index'));
    }

    /**
     * Remove the specified TipoEvento from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoEvento = $this->tipoEventoRepository->find($id);

        if (empty($tipoEvento)) {
            Flash::error('Tipo Evento not found');

            return redirect(route('tipoEventos.index'));
        }

        $this->tipoEventoRepository->delete($id);

        Flash::success('Tipo Evento deleted successfully.');

        return redirect(route('tipoEventos.index'));
    }
}
