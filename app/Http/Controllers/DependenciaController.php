<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDependenciaRequest;
use App\Http\Requests\UpdateDependenciaRequest;
use App\Repositories\DependenciaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class DependenciaController extends AppBaseController
{
    /** @var  DependenciaRepository */
    private $dependenciaRepository;

    public function __construct(DependenciaRepository $dependenciaRepo)
    {
        $this->dependenciaRepository = $dependenciaRepo;
    }

    /**
     * Display a listing of the Dependencia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dependenciaRepository->pushCriteria(new RequestCriteria($request));
        $dependencias = $this->dependenciaRepository->all();

        return view('dependencias.index')
            ->with('dependencias', $dependencias);
    }

    /**
     * Show the form for creating a new Dependencia.
     *
     * @return Response
     */
    public function create()
    {
        return view('dependencias.create');
    }

    /**
     * Store a newly created Dependencia in storage.
     *
     * @param CreateDependenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateDependenciaRequest $request)
    {
        $input = $request->all();

        $dependencia = $this->dependenciaRepository->create($input);

        Flash::success('Dependencia saved successfully.');

        return redirect(route('dependencias.index'));
    }

    /**
     * Display the specified Dependencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            Flash::error('Dependencia not found');

            return redirect(route('dependencias.index'));
        }

        return view('dependencias.show')->with('dependencia', $dependencia);
    }

    /**
     * Show the form for editing the specified Dependencia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            Flash::error('Dependencia not found');

            return redirect(route('dependencias.index'));
        }

        return view('dependencias.edit')->with('dependencia', $dependencia);
    }

    /**
     * Update the specified Dependencia in storage.
     *
     * @param  int              $id
     * @param UpdateDependenciaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDependenciaRequest $request)
    {
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            Flash::error('Dependencia not found');

            return redirect(route('dependencias.index'));
        }

        $dependencia = $this->dependenciaRepository->update($request->all(), $id);

        Flash::success('Dependencia updated successfully.');

        return redirect(route('dependencias.index'));
    }

    /**
     * Remove the specified Dependencia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            Flash::error('Dependencia not found');

            return redirect(route('dependencias.index'));
        }

        $this->dependenciaRepository->delete($id);

        Flash::success('Dependencia deleted successfully.');

        return redirect(route('dependencias.index'));
    }
}
