<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePuestoAdicionalRequest;
use App\Http\Requests\UpdatePuestoAdicionalRequest;
use App\Repositories\PuestoAdicionalRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class PuestoAdicionalController extends AppBaseController
{
    /** @var  PuestoAdicionalRepository */
    private $puestoAdicionalRepository;

    public function __construct(PuestoAdicionalRepository $puestoAdicionalRepo)
    {
        $this->puestoAdicionalRepository = $puestoAdicionalRepo;
    }

    /**
     * Display a listing of the PuestoAdicional.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->puestoAdicionalRepository->pushCriteria(new RequestCriteria($request));
        $puestoAdicionals = $this->puestoAdicionalRepository->all();

        return view('puesto_adicionals.index')
            ->with('puestoAdicionals', $puestoAdicionals);
    }

    /**
     * Show the form for creating a new PuestoAdicional.
     *
     * @return Response
     */
    public function create()
    {
        return view('puesto_adicionals.create');
    }

    /**
     * Store a newly created PuestoAdicional in storage.
     *
     * @param CreatePuestoAdicionalRequest $request
     *
     * @return Response
     */
    public function store(CreatePuestoAdicionalRequest $request)
    {
        $input = $request->all();

        $puestoAdicional = $this->puestoAdicionalRepository->create($input);

        Flash::success('Puesto Adicional saved successfully.');

        return redirect(route('puestoAdicionals.index'));
    }

    /**
     * Display the specified PuestoAdicional.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $puestoAdicional = $this->puestoAdicionalRepository->findWithoutFail($id);

        if (empty($puestoAdicional)) {
            Flash::error('Puesto Adicional not found');

            return redirect(route('puestoAdicionals.index'));
        }

        return view('puesto_adicionals.show')->with('puestoAdicional', $puestoAdicional);
    }

    /**
     * Show the form for editing the specified PuestoAdicional.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $puestoAdicional = $this->puestoAdicionalRepository->findWithoutFail($id);

        if (empty($puestoAdicional)) {
            Flash::error('Puesto Adicional not found');

            return redirect(route('puestoAdicionals.index'));
        }

        return view('puesto_adicionals.edit')->with('puestoAdicional', $puestoAdicional);
    }

    /**
     * Update the specified PuestoAdicional in storage.
     *
     * @param  int              $id
     * @param UpdatePuestoAdicionalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePuestoAdicionalRequest $request)
    {
        $puestoAdicional = $this->puestoAdicionalRepository->findWithoutFail($id);

        if (empty($puestoAdicional)) {
            Flash::error('Puesto Adicional not found');

            return redirect(route('puestoAdicionals.index'));
        }

        $puestoAdicional = $this->puestoAdicionalRepository->update($request->all(), $id);

        Flash::success('Puesto Adicional updated successfully.');

        return redirect(route('puestoAdicionals.index'));
    }

    /**
     * Remove the specified PuestoAdicional from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $puestoAdicional = $this->puestoAdicionalRepository->findWithoutFail($id);

        if (empty($puestoAdicional)) {
            Flash::error('Puesto Adicional not found');

            return redirect(route('puestoAdicionals.index'));
        }

        $this->puestoAdicionalRepository->delete($id);

        Flash::success('Puesto Adicional deleted successfully.');

        return redirect(route('puestoAdicionals.index'));
    }
}
