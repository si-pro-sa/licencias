<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLicenciaRequest;
use App\Http\Requests\UpdateLicenciaRequest;
use App\Repositories\LicenciaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LicenciaController extends AppBaseController
{
    /** @var  LicenciaRepository */
    private $licenciaRepository;

    public function __construct(LicenciaRepository $licenciaRepo)
    {
        $this->licenciaRepository = $licenciaRepo;
    }

    /**
     * Display a listing of the Licencia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->licenciaRepository->pushCriteria(new RequestCriteria($request));
        $licencias = $this->licenciaRepository->all();

        return view('licencias.index')
            ->with('licencias', $licencias);
    }

    /**
     * Show the form for creating a new Licencia.
     *
     * @return Response
     */
    public function create()
    {
        return view('licencias.create');
    }

    /**
     * Store a newly created Licencia in storage.
     *
     * @param CreateLicenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenciaRequest $request)
    {
        $input = $request->all();

        $licencia = $this->licenciaRepository->create($input);

        Flash::success('Licencia saved successfully.');

        return redirect(route('licencias.index'));
    }

    /**
     * Display the specified Licencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        if (empty($licencia)) {
            Flash::error('Licencia not found');

            return redirect(route('licencias.index'));
        }

        return view('licencias.show')->with('licencia', $licencia);
    }

    /**
     * Show the form for editing the specified Licencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        if (empty($licencia)) {
            Flash::error('Licencia not found');

            return redirect(route('licencias.index'));
        }

        return view('licencias.edit')->with('licencia', $licencia);
    }

    /**
     * Update the specified Licencia in storage.
     *
     * @param  int              $id
     * @param UpdateLicenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenciaRequest $request)
    {
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        if (empty($licencia)) {
            Flash::error('Licencia not found');

            return redirect(route('licencias.index'));
        }

        $licencia = $this->licenciaRepository->update($request->all(), $id);

        Flash::success('Licencia updated successfully.');

        return redirect(route('licencias.index'));
    }

    /**
     * Remove the specified Licencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $licencia = $this->licenciaRepository->findWithoutFail($id);

        if (empty($licencia)) {
            Flash::error('Licencia not found');

            return redirect(route('licencias.index'));
        }

        $this->licenciaRepository->delete($id);

        Flash::success('Licencia deleted successfully.');

        return redirect(route('licencias.index'));
    }
}
