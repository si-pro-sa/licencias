<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLicenciaFamiliarRequest;
use App\Http\Requests\UpdateLicenciaFamiliarRequest;
use App\Repositories\LicenciaFamiliarRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LicenciaFamiliarController extends AppBaseController
{
    /** @var  LicenciaFamiliarRepository */
    private $licenciaFamiliarRepository;

    public function __construct(LicenciaFamiliarRepository $licenciaFamiliarRepo)
    {
        $this->licenciaFamiliarRepository = $licenciaFamiliarRepo;
    }

    /**
     * Display a listing of the LicenciaFamiliar.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->licenciaFamiliarRepository->pushCriteria(new RequestCriteria($request));
        $licenciaFamiliars = $this->licenciaFamiliarRepository->all();

        return view('licencia_familiars.index')
            ->with('licenciaFamiliars', $licenciaFamiliars);
    }

    /**
     * Show the form for creating a new LicenciaFamiliar.
     *
     * @return Response
     */
    public function create()
    {
        return view('licencia_familiars.create');
    }

    /**
     * Store a newly created LicenciaFamiliar in storage.
     *
     * @param CreateLicenciaFamiliarRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenciaFamiliarRequest $request)
    {
        $input = $request->all();

        $licenciaFamiliar = $this->licenciaFamiliarRepository->create($input);

        Flash::success('Licencia Familiar saved successfully.');

        return redirect(route('licenciaFamiliars.index'));
    }

    /**
     * Display the specified LicenciaFamiliar.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            Flash::error('Licencia Familiar not found');

            return redirect(route('licenciaFamiliars.index'));
        }

        return view('licencia_familiars.show')->with('licenciaFamiliar', $licenciaFamiliar);
    }

    /**
     * Show the form for editing the specified LicenciaFamiliar.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            Flash::error('Licencia Familiar not found');

            return redirect(route('licenciaFamiliars.index'));
        }

        return view('licencia_familiars.edit')->with('licenciaFamiliar', $licenciaFamiliar);
    }

    /**
     * Update the specified LicenciaFamiliar in storage.
     *
     * @param  int              $id
     * @param UpdateLicenciaFamiliarRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenciaFamiliarRequest $request)
    {
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            Flash::error('Licencia Familiar not found');

            return redirect(route('licenciaFamiliars.index'));
        }

        $licenciaFamiliar = $this->licenciaFamiliarRepository->update($request->all(), $id);

        Flash::success('Licencia Familiar updated successfully.');

        return redirect(route('licenciaFamiliars.index'));
    }

    /**
     * Remove the specified LicenciaFamiliar from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $licenciaFamiliar = $this->licenciaFamiliarRepository->findWithoutFail($id);

        if (empty($licenciaFamiliar)) {
            Flash::error('Licencia Familiar not found');

            return redirect(route('licenciaFamiliars.index'));
        }

        $this->licenciaFamiliarRepository->delete($id);

        Flash::success('Licencia Familiar deleted successfully.');

        return redirect(route('licenciaFamiliars.index'));
    }
}
