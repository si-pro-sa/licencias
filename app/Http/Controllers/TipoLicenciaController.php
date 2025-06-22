<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoLicenciaRequest;
use App\Http\Requests\UpdateTipoLicenciaRequest;
use App\Repositories\TipoLicenciaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TipoLicenciaController extends AppBaseController
{
    /** @var  TipoLicenciaRepository */
    private $tipoLicenciaRepository;

    public function __construct(TipoLicenciaRepository $tipoLicenciaRepo)
    {
        $this->tipoLicenciaRepository = $tipoLicenciaRepo;
    }

    /**
     * Display a listing of the TipoLicencia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoLicenciaRepository->pushCriteria(new RequestCriteria($request));
        $tipoLicencias = $this->tipoLicenciaRepository->all();

        return view('tipo_licencias.index')
            ->with('tipoLicencias', $tipoLicencias);
    }

    /**
     * Show the form for creating a new TipoLicencia.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_licencias.create');
    }

    /**
     * Store a newly created TipoLicencia in storage.
     *
     * @param CreateTipoLicenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoLicenciaRequest $request)
    {
        $input = $request->all();

        $tipoLicencia = $this->tipoLicenciaRepository->create($input);

        Flash::success('Tipo Licencia saved successfully.');

        return redirect(route('tipoLicencias.index'));
    }

    /**
     * Display the specified TipoLicencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            Flash::error('Tipo Licencia not found');

            return redirect(route('tipoLicencias.index'));
        }

        return view('tipo_licencias.show')->with('tipoLicencia', $tipoLicencia);
    }

    /**
     * Show the form for editing the specified TipoLicencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            Flash::error('Tipo Licencia not found');

            return redirect(route('tipoLicencias.index'));
        }

        return view('tipo_licencias.edit')->with('tipoLicencia', $tipoLicencia);
    }

    /**
     * Update the specified TipoLicencia in storage.
     *
     * @param  int              $id
     * @param UpdateTipoLicenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoLicenciaRequest $request)
    {
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            Flash::error('Tipo Licencia not found');

            return redirect(route('tipoLicencias.index'));
        }

        $tipoLicencia = $this->tipoLicenciaRepository->update($request->all(), $id);

        Flash::success('Tipo Licencia updated successfully.');

        return redirect(route('tipoLicencias.index'));
    }

    /**
     * Remove the specified TipoLicencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoLicencia = $this->tipoLicenciaRepository->findWithoutFail($id);

        if (empty($tipoLicencia)) {
            Flash::error('Tipo Licencia not found');

            return redirect(route('tipoLicencias.index'));
        }

        $this->tipoLicenciaRepository->delete($id);

        Flash::success('Tipo Licencia deleted successfully.');

        return redirect(route('tipoLicencias.index'));
    }
}
