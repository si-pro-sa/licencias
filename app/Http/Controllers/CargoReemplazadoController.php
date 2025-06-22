<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoReemplazadoRequest;
use App\Http\Requests\UpdateCargoReemplazadoRequest;
use App\Repositories\CargoReemplazadoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoReemplazadoController extends AppBaseController
{
    /** @var  CargoReemplazadoRepository */
    private $cargoReemplazadoRepository;

    public function __construct(CargoReemplazadoRepository $cargoReemplazadoRepo)
    {
        $this->cargoReemplazadoRepository = $cargoReemplazadoRepo;
    }

    /**
     * Display a listing of the CargoReemplazado.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoReemplazadoRepository->pushCriteria(new RequestCriteria($request));
        $cargoReemplazados = $this->cargoReemplazadoRepository->all();

        return view('cargo_reemplazados.index')
            ->with('cargoReemplazados', $cargoReemplazados);
    }

    /**
     * Show the form for creating a new CargoReemplazado.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_reemplazados.create');
    }

    /**
     * Store a newly created CargoReemplazado in storage.
     *
     * @param CreateCargoReemplazadoRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoReemplazadoRequest $request)
    {
        $input = $request->all();

        $cargoReemplazado = $this->cargoReemplazadoRepository->create($input);

        Flash::success('Cargo Reemplazado saved successfully.');

        return redirect(route('cargoReemplazados.index'));
    }

    /**
     * Display the specified CargoReemplazado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoReemplazado = $this->cargoReemplazadoRepository->findWithoutFail($id);

        if (empty($cargoReemplazado)) {
            Flash::error('Cargo Reemplazado not found');

            return redirect(route('cargoReemplazados.index'));
        }

        return view('cargo_reemplazados.show')->with('cargoReemplazado', $cargoReemplazado);
    }

    /**
     * Show the form for editing the specified CargoReemplazado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoReemplazado = $this->cargoReemplazadoRepository->findWithoutFail($id);

        if (empty($cargoReemplazado)) {
            Flash::error('Cargo Reemplazado not found');

            return redirect(route('cargoReemplazados.index'));
        }

        return view('cargo_reemplazados.edit')->with('cargoReemplazado', $cargoReemplazado);
    }

    /**
     * Update the specified CargoReemplazado in storage.
     *
     * @param  int              $id
     * @param UpdateCargoReemplazadoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoReemplazadoRequest $request)
    {
        $cargoReemplazado = $this->cargoReemplazadoRepository->findWithoutFail($id);

        if (empty($cargoReemplazado)) {
            Flash::error('Cargo Reemplazado not found');

            return redirect(route('cargoReemplazados.index'));
        }

        $cargoReemplazado = $this->cargoReemplazadoRepository->update($request->all(), $id);

        Flash::success('Cargo Reemplazado updated successfully.');

        return redirect(route('cargoReemplazados.index'));
    }

    /**
     * Remove the specified CargoReemplazado from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoReemplazado = $this->cargoReemplazadoRepository->findWithoutFail($id);

        if (empty($cargoReemplazado)) {
            Flash::error('Cargo Reemplazado not found');

            return redirect(route('cargoReemplazados.index'));
        }

        $this->cargoReemplazadoRepository->delete($id);

        Flash::success('Cargo Reemplazado deleted successfully.');

        return redirect(route('cargoReemplazados.index'));
    }
}
