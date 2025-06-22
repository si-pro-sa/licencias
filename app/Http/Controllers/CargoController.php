<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Repositories\CargoVacanteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoController extends AppBaseController
{
    /** @var  CargoVacanteRepository */
    private $cargoVacanteRepository;

    public function __construct(CargoVacanteRepository $cargoVacanteRepo)
    {
        $this->cargoVacanteRepository = $cargoVacanteRepo;
    }

    /**
     * Display a listing of the Cargo.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoVacanteRepository->pushCriteria(new RequestCriteria($request));
        $cargoVacantes = $this->cargoVacanteRepository->paginate(15);

        return view('cargo_vacantes.index')
            ->with('cargoVacantes', $cargoVacantes);
    }

    /**
     * Show the form for creating a new Cargo.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_vacantes.create');
    }

    /**
     * Store a newly created Cargo in storage.
     *
     * @param CreateCargoRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoRequest $request)
    {
        $input = $request->all();

        $cargoVacante = $this->cargoVacanteRepository->create($input);

        Flash::success('Cargo Vacante saved successfully.');

        return redirect(route('cargoVacantes.index'));
    }

    /**
     * Display the specified Cargo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoVacante = $this->cargoVacanteRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            Flash::error('Cargo Vacante not found');

            return redirect(route('cargoVacantes.index'));
        }

        return view('cargo_vacantes.show')->with('cargoVacante', $cargoVacante);
    }

    /**
     * Show the form for editing the specified Cargo.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoVacante = $this->cargoVacanteRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            Flash::error('Cargo Vacante not found');

            return redirect(route('cargoVacantes.index'));
        }

        return view('cargo_vacantes.edit')->with('cargoVacante', $cargoVacante);
    }

    /**
     * Update the specified Cargo in storage.
     *
     * @param  int              $id
     * @param UpdateCargoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoRequest $request)
    {
        $cargoVacante = $this->cargoVacanteRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            Flash::error('Cargo Vacante not found');

            return redirect(route('cargoVacantes.index'));
        }

        $cargoVacante = $this->cargoVacanteRepository->update($request->all(), $id);

        Flash::success('Cargo Vacante updated successfully.');

        return redirect(route('cargoVacantes.index'));
    }

    /**
     * Remove the specified Cargo from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoVacante = $this->cargoVacanteRepository->findWithoutFail($id);

        if (empty($cargoVacante)) {
            Flash::error('Cargo Vacante not found');

            return redirect(route('cargoVacantes.index'));
        }

        $this->cargoVacanteRepository->delete($id);

        Flash::success('Cargo Vacante deleted successfully.');

        return redirect(route('cargoVacantes.index'));
    }
}
