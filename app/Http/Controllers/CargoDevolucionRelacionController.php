<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoDevolucionRelacionRequest;
use App\Http\Requests\UpdateCargoDevolucionRelacionRequest;
use App\Repositories\CargoDevolucionRelacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoDevolucionRelacionController extends AppBaseController
{
    /** @var  CargoDevolucionRelacionRepository */
    private $cargoDevolucionRelacionRepository;

    public function __construct(CargoDevolucionRelacionRepository $cargoDevolucionRelacionRepo)
    {
        $this->cargoDevolucionRelacionRepository = $cargoDevolucionRelacionRepo;
    }

    /**
     * Display a listing of the CargoDevolucionRelacion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoDevolucionRelacionRepository->pushCriteria(new RequestCriteria($request));
        $cargoDevolucionRelacions = $this->cargoDevolucionRelacionRepository->all();

        return view('cargo_devolucion_relacions.index')
            ->with('cargoDevolucionRelacions', $cargoDevolucionRelacions);
    }

    /**
     * Show the form for creating a new CargoDevolucionRelacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_devolucion_relacions.create');
    }

    /**
     * Store a newly created CargoDevolucionRelacion in storage.
     *
     * @param CreateCargoDevolucionRelacionRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoDevolucionRelacionRequest $request)
    {
        $input = $request->all();

        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->create($input);

        Flash::success('Cargo Devolucion Relacion saved successfully.');

        return redirect(route('cargoDevolucionRelacions.index'));
    }

    /**
     * Display the specified CargoDevolucionRelacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->findWithoutFail($id);

        if (empty($cargoDevolucionRelacion)) {
            Flash::error('Cargo Devolucion Relacion not found');

            return redirect(route('cargoDevolucionRelacions.index'));
        }

        return view('cargo_devolucion_relacions.show')->with('cargoDevolucionRelacion', $cargoDevolucionRelacion);
    }

    /**
     * Show the form for editing the specified CargoDevolucionRelacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->findWithoutFail($id);

        if (empty($cargoDevolucionRelacion)) {
            Flash::error('Cargo Devolucion Relacion not found');

            return redirect(route('cargoDevolucionRelacions.index'));
        }

        return view('cargo_devolucion_relacions.edit')->with('cargoDevolucionRelacion', $cargoDevolucionRelacion);
    }

    /**
     * Update the specified CargoDevolucionRelacion in storage.
     *
     * @param  int              $id
     * @param UpdateCargoDevolucionRelacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoDevolucionRelacionRequest $request)
    {
        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->findWithoutFail($id);

        if (empty($cargoDevolucionRelacion)) {
            Flash::error('Cargo Devolucion Relacion not found');

            return redirect(route('cargoDevolucionRelacions.index'));
        }

        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->update($request->all(), $id);

        Flash::success('Cargo Devolucion Relacion updated successfully.');

        return redirect(route('cargoDevolucionRelacions.index'));
    }

    /**
     * Remove the specified CargoDevolucionRelacion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoDevolucionRelacion = $this->cargoDevolucionRelacionRepository->findWithoutFail($id);

        if (empty($cargoDevolucionRelacion)) {
            Flash::error('Cargo Devolucion Relacion not found');

            return redirect(route('cargoDevolucionRelacions.index'));
        }

        $this->cargoDevolucionRelacionRepository->delete($id);

        Flash::success('Cargo Devolucion Relacion deleted successfully.');

        return redirect(route('cargoDevolucionRelacions.index'));
    }
}
