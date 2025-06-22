<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCargoTipoVisadoEPRequest;
use App\Http\Requests\UpdateCargoTipoVisadoEPRequest;
use App\Repositories\CargoTipoVisadoEPRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class CargoTipoVisadoEPController extends AppBaseController
{
    /** @var  CargoTipoVisadoEPRepository */
    private $cargoTipoVisadoEPRepository;

    public function __construct(CargoTipoVisadoEPRepository $cargoTipoVisadoEPRepo)
    {
        $this->cargoTipoVisadoEPRepository = $cargoTipoVisadoEPRepo;
    }

    /**
     * Display a listing of the CargoTipoVisadoEP.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoVisadoEPRepository->pushCriteria(new RequestCriteria($request));
        $cargoTipoVisadoEPs = $this->cargoTipoVisadoEPRepository->all();

        return view('cargo_tipo_visado_e_ps.index')
            ->with('cargoTipoVisadoEPs', $cargoTipoVisadoEPs);
    }

    /**
     * Show the form for creating a new CargoTipoVisadoEP.
     *
     * @return Response
     */
    public function create()
    {
        return view('cargo_tipo_visado_e_ps.create');
    }

    /**
     * Store a newly created CargoTipoVisadoEP in storage.
     *
     * @param CreateCargoTipoVisadoEPRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoVisadoEPRequest $request)
    {
        $input = $request->all();

        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->create($input);

        Flash::success('Cargo Tipo Visado E P saved successfully.');

        return redirect(route('cargoTipoVisadoEPs.index'));
    }

    /**
     * Display the specified CargoTipoVisadoEP.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->findWithoutFail($id);

        if (empty($cargoTipoVisadoEP)) {
            Flash::error('Cargo Tipo Visado E P not found');

            return redirect(route('cargoTipoVisadoEPs.index'));
        }

        return view('cargo_tipo_visado_e_ps.show')->with('cargoTipoVisadoEP', $cargoTipoVisadoEP);
    }

    /**
     * Show the form for editing the specified CargoTipoVisadoEP.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->findWithoutFail($id);

        if (empty($cargoTipoVisadoEP)) {
            Flash::error('Cargo Tipo Visado E P not found');

            return redirect(route('cargoTipoVisadoEPs.index'));
        }

        return view('cargo_tipo_visado_e_ps.edit')->with('cargoTipoVisadoEP', $cargoTipoVisadoEP);
    }

    /**
     * Update the specified CargoTipoVisadoEP in storage.
     *
     * @param  int              $id
     * @param UpdateCargoTipoVisadoEPRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoVisadoEPRequest $request)
    {
        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->findWithoutFail($id);

        if (empty($cargoTipoVisadoEP)) {
            Flash::error('Cargo Tipo Visado E P not found');

            return redirect(route('cargoTipoVisadoEPs.index'));
        }

        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->update($request->all(), $id);

        Flash::success('Cargo Tipo Visado E P updated successfully.');

        return redirect(route('cargoTipoVisadoEPs.index'));
    }

    /**
     * Remove the specified CargoTipoVisadoEP from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $cargoTipoVisadoEP = $this->cargoTipoVisadoEPRepository->findWithoutFail($id);

        if (empty($cargoTipoVisadoEP)) {
            Flash::error('Cargo Tipo Visado E P not found');

            return redirect(route('cargoTipoVisadoEPs.index'));
        }

        $this->cargoTipoVisadoEPRepository->delete($id);

        Flash::success('Cargo Tipo Visado E P deleted successfully.');

        return redirect(route('cargoTipoVisadoEPs.index'));
    }
}
