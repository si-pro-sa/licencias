<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoTipoVisadoRequest;
use App\Http\Requests\UpdateCargoTipoVisadoRequest;
use App\Repositories\CargoTipoVisadoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoTipoVisadoController extends AppBaseController
{
    /** @var  CargoTipoVisadoRepository */
    private $cargoTipoVisadoRepository;

    public function __construct(CargoTipoVisadoRepository $cargoTipoVisadoRepo)
    {
        $this->cargoTipoVisadoRepository = $cargoTipoVisadoRepo;
    }

    /**
     * Display a listing of the CargoTipoVisado.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoVisadoRepository->pushCriteria(new RequestCriteria($request));
        $cargoTipoVisados = $this->cargoTipoVisadoRepository->all();

        return view('cargo_tipo_visados.index')
            ->with('cargoTipoVisados', $cargoTipoVisados);
    }

    /**
     * Show the form for creating a new CargoTipoVisado.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_tipo_visados.create');
    }

    /**
     * Store a newly created CargoTipoVisado in storage.
     *
     * @param CreateCargoTipoVisadoRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoVisadoRequest $request)
    {
        $input = $request->all();

        $cargoTipoVisado = $this->cargoTipoVisadoRepository->create($input);

        Flash::success('Cargo Tipo Visado saved successfully.');

        return redirect(route('cargoTipoVisados.index'));
    }

    /**
     * Display the specified CargoTipoVisado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoTipoVisado = $this->cargoTipoVisadoRepository->findWithoutFail($id);

        if (empty($cargoTipoVisado)) {
            Flash::error('Cargo Tipo Visado not found');

            return redirect(route('cargoTipoVisados.index'));
        }

        return view('cargo_tipo_visados.show')->with('cargoTipoVisado', $cargoTipoVisado);
    }

    /**
     * Show the form for editing the specified CargoTipoVisado.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoTipoVisado = $this->cargoTipoVisadoRepository->findWithoutFail($id);

        if (empty($cargoTipoVisado)) {
            Flash::error('Cargo Tipo Visado not found');

            return redirect(route('cargoTipoVisados.index'));
        }

        return view('cargo_tipo_visados.edit')->with('cargoTipoVisado', $cargoTipoVisado);
    }

    /**
     * Update the specified CargoTipoVisado in storage.
     *
     * @param  int              $id
     * @param UpdateCargoTipoVisadoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoVisadoRequest $request)
    {
        $cargoTipoVisado = $this->cargoTipoVisadoRepository->findWithoutFail($id);

        if (empty($cargoTipoVisado)) {
            Flash::error('Cargo Tipo Visado not found');

            return redirect(route('cargoTipoVisados.index'));
        }

        $cargoTipoVisado = $this->cargoTipoVisadoRepository->update($request->all(), $id);

        Flash::success('Cargo Tipo Visado updated successfully.');

        return redirect(route('cargoTipoVisados.index'));
    }

    /**
     * Remove the specified CargoTipoVisado from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoTipoVisado = $this->cargoTipoVisadoRepository->findWithoutFail($id);

        if (empty($cargoTipoVisado)) {
            Flash::error('Cargo Tipo Visado not found');

            return redirect(route('cargoTipoVisados.index'));
        }

        $this->cargoTipoVisadoRepository->delete($id);

        Flash::success('Cargo Tipo Visado deleted successfully.');

        return redirect(route('cargoTipoVisados.index'));
    }
}
