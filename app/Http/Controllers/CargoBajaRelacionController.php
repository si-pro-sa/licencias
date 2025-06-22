<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoBajaRelacionRequest;
use App\Http\Requests\UpdateCargoBajaRelacionRequest;
use App\Repositories\CargoBajaRelacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoBajaRelacionController extends AppBaseController
{
    /** @var  CargoBajaRelacionRepository */
    private $cargoBajaRelacionRepository;

    public function __construct(CargoBajaRelacionRepository $cargoBajaRelacionRepo)
    {
        $this->cargoBajaRelacionRepository = $cargoBajaRelacionRepo;
    }

    /**
     * Display a listing of the CargoBajaRelacion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoBajaRelacionRepository->pushCriteria(new RequestCriteria($request));
        $cargoBajaRelacions = $this->cargoBajaRelacionRepository->all();

        return view('cargo_baja_relacions.index')
            ->with('cargoBajaRelacions', $cargoBajaRelacions);
    }

    /**
     * Show the form for creating a new CargoBajaRelacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_baja_relacions.create');
    }

    /**
     * Store a newly created CargoBajaRelacion in storage.
     *
     * @param CreateCargoBajaRelacionRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoBajaRelacionRequest $request)
    {
        $input = $request->all();

        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->create($input);

        Flash::success('Cargo Baja Relacion saved successfully.');

        return redirect(route('cargoBajaRelacions.index'));
    }

    /**
     * Display the specified CargoBajaRelacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->findWithoutFail($id);

        if (empty($cargoBajaRelacion)) {
            Flash::error('Cargo Baja Relacion not found');

            return redirect(route('cargoBajaRelacions.index'));
        }

        return view('cargo_baja_relacions.show')->with('cargoBajaRelacion', $cargoBajaRelacion);
    }

    /**
     * Show the form for editing the specified CargoBajaRelacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->findWithoutFail($id);

        if (empty($cargoBajaRelacion)) {
            Flash::error('Cargo Baja Relacion not found');

            return redirect(route('cargoBajaRelacions.index'));
        }

        return view('cargo_baja_relacions.edit')->with('cargoBajaRelacion', $cargoBajaRelacion);
    }

    /**
     * Update the specified CargoBajaRelacion in storage.
     *
     * @param  int              $id
     * @param UpdateCargoBajaRelacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoBajaRelacionRequest $request)
    {
        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->findWithoutFail($id);

        if (empty($cargoBajaRelacion)) {
            Flash::error('Cargo Baja Relacion not found');

            return redirect(route('cargoBajaRelacions.index'));
        }

        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->update($request->all(), $id);

        Flash::success('Cargo Baja Relacion updated successfully.');

        return redirect(route('cargoBajaRelacions.index'));
    }

    /**
     * Remove the specified CargoBajaRelacion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoBajaRelacion = $this->cargoBajaRelacionRepository->findWithoutFail($id);

        if (empty($cargoBajaRelacion)) {
            Flash::error('Cargo Baja Relacion not found');

            return redirect(route('cargoBajaRelacions.index'));
        }

        $this->cargoBajaRelacionRepository->delete($id);

        Flash::success('Cargo Baja Relacion deleted successfully.');

        return redirect(route('cargoBajaRelacions.index'));
    }
}
