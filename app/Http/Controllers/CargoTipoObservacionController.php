<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoTipoObservacionRequest;
use App\Http\Requests\UpdateCargoTipoObservacionRequest;
use App\Repositories\CargoTipoObservacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoTipoObservacionController extends AppBaseController
{
    /** @var  CargoTipoObservacionRepository */
    private $cargoTipoObservacionRepository;

    public function __construct(CargoTipoObservacionRepository $cargoTipoObservacionRepo)
    {
        $this->cargoTipoObservacionRepository = $cargoTipoObservacionRepo;
    }

    /**
     * Display a listing of the CargoTipoObservacion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoObservacionRepository->pushCriteria(new RequestCriteria($request));
        $cargoTipoObservacions = $this->cargoTipoObservacionRepository->all();

        return view('cargo_tipo_observacions.index')
            ->with('cargoTipoObservacions', $cargoTipoObservacions);
    }

    /**
     * Show the form for creating a new CargoTipoObservacion.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_tipo_observacions.create');
    }

    /**
     * Store a newly created CargoTipoObservacion in storage.
     *
     * @param CreateCargoTipoObservacionRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoObservacionRequest $request)
    {
        $input = $request->all();

        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->create($input);

        Flash::success('Cargo Tipo Observacion saved successfully.');

        return redirect(route('cargoTipoObservacions.index'));
    }

    /**
     * Display the specified CargoTipoObservacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->findWithoutFail($id);

        if (empty($cargoTipoObservacion)) {
            Flash::error('Cargo Tipo Observacion not found');

            return redirect(route('cargoTipoObservacions.index'));
        }

        return view('cargo_tipo_observacions.show')->with('cargoTipoObservacion', $cargoTipoObservacion);
    }

    /**
     * Show the form for editing the specified CargoTipoObservacion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->findWithoutFail($id);

        if (empty($cargoTipoObservacion)) {
            Flash::error('Cargo Tipo Observacion not found');

            return redirect(route('cargoTipoObservacions.index'));
        }

        return view('cargo_tipo_observacions.edit')->with('cargoTipoObservacion', $cargoTipoObservacion);
    }

    /**
     * Update the specified CargoTipoObservacion in storage.
     *
     * @param  int              $id
     * @param UpdateCargoTipoObservacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoObservacionRequest $request)
    {
        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->findWithoutFail($id);

        if (empty($cargoTipoObservacion)) {
            Flash::error('Cargo Tipo Observacion not found');

            return redirect(route('cargoTipoObservacions.index'));
        }

        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->update($request->all(), $id);

        Flash::success('Cargo Tipo Observacion updated successfully.');

        return redirect(route('cargoTipoObservacions.index'));
    }

    /**
     * Remove the specified CargoTipoObservacion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoTipoObservacion = $this->cargoTipoObservacionRepository->findWithoutFail($id);

        if (empty($cargoTipoObservacion)) {
            Flash::error('Cargo Tipo Observacion not found');

            return redirect(route('cargoTipoObservacions.index'));
        }

        $this->cargoTipoObservacionRepository->delete($id);

        Flash::success('Cargo Tipo Observacion deleted successfully.');

        return redirect(route('cargoTipoObservacions.index'));
    }
}
