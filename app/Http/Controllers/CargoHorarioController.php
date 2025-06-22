<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoHorarioRequest;
use App\Http\Requests\UpdateCargoHorarioRequest;
use App\Repositories\CargoHorarioRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoHorarioController extends AppBaseController
{
    /** @var  CargoHorarioRepository */
    private $cargoHorarioRepository;

    public function __construct(CargoHorarioRepository $cargoHorarioRepo)
    {
        $this->cargoHorarioRepository = $cargoHorarioRepo;
    }

    /**
     * Display a listing of the CargoHorario.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoHorarioRepository->pushCriteria(new RequestCriteria($request));
        $cargoHorarios = $this->cargoHorarioRepository->all();

        return view('cargo_horarios.index')
            ->with('cargoHorarios', $cargoHorarios);
    }

    /**
     * Show the form for creating a new CargoHorario.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_horarios.create');
    }

    /**
     * Store a newly created CargoHorario in storage.
     *
     * @param CreateCargoHorarioRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoHorarioRequest $request)
    {
        $input = $request->all();

        $cargoHorario = $this->cargoHorarioRepository->create($input);

        Flash::success('Cargo Horario saved successfully.');

        return redirect(route('cargoHorarios.index'));
    }

    /**
     * Display the specified CargoHorario.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoHorario = $this->cargoHorarioRepository->findWithoutFail($id);

        if (empty($cargoHorario)) {
            Flash::error('Cargo Horario not found');

            return redirect(route('cargoHorarios.index'));
        }

        return view('cargo_horarios.show')->with('cargoHorario', $cargoHorario);
    }

    /**
     * Show the form for editing the specified CargoHorario.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoHorario = $this->cargoHorarioRepository->findWithoutFail($id);

        if (empty($cargoHorario)) {
            Flash::error('Cargo Horario not found');

            return redirect(route('cargoHorarios.index'));
        }

        return view('cargo_horarios.edit')->with('cargoHorario', $cargoHorario);
    }

    /**
     * Update the specified CargoHorario in storage.
     *
     * @param  int              $id
     * @param UpdateCargoHorarioRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoHorarioRequest $request)
    {
        $cargoHorario = $this->cargoHorarioRepository->findWithoutFail($id);

        if (empty($cargoHorario)) {
            Flash::error('Cargo Horario not found');

            return redirect(route('cargoHorarios.index'));
        }

        $cargoHorario = $this->cargoHorarioRepository->update($request->all(), $id);

        Flash::success('Cargo Horario updated successfully.');

        return redirect(route('cargoHorarios.index'));
    }

    /**
     * Remove the specified CargoHorario from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoHorario = $this->cargoHorarioRepository->findWithoutFail($id);

        if (empty($cargoHorario)) {
            Flash::error('Cargo Horario not found');

            return redirect(route('cargoHorarios.index'));
        }

        $this->cargoHorarioRepository->delete($id);

        Flash::success('Cargo Horario deleted successfully.');

        return redirect(route('cargoHorarios.index'));
    }
}
