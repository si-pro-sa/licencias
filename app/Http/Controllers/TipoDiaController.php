<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoDiaRequest;
use App\Http\Requests\UpdateTipoDiaRequest;
use App\Repositories\TipoDiaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class TipoDiaController extends AppBaseController
{
    /** @var  TipoDiaRepository */
    private $tipoDiaRepository;

    public function __construct(TipoDiaRepository $tipoDiaRepo)
    {
        $this->tipoDiaRepository = $tipoDiaRepo;
    }

    /**
     * Display a listing of the TipoDia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoDiaRepository->pushCriteria(new RequestCriteria($request));
        $tipoDias = $this->tipoDiaRepository->all();

        return view('tipo_dias.index')
            ->with('tipoDias', $tipoDias);
    }

    /**
     * Show the form for creating a new TipoDia.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_dias.create');
    }

    /**
     * Store a newly created TipoDia in storage.
     *
     * @param CreateTipoDiaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoDiaRequest $request)
    {
        $input = $request->all();

        $tipoDia = $this->tipoDiaRepository->create($input);

        Flash::success('Tipo Dia saved successfully.');

        return redirect(route('tipoDias.index'));
    }

    /**
     * Display the specified TipoDia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoDia = $this->tipoDiaRepository->findWithoutFail($id);

        if (empty($tipoDia)) {
            Flash::error('Tipo Dia not found');

            return redirect(route('tipoDias.index'));
        }

        return view('tipo_dias.show')->with('tipoDia', $tipoDia);
    }

    /**
     * Show the form for editing the specified TipoDia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoDia = $this->tipoDiaRepository->findWithoutFail($id);

        if (empty($tipoDia)) {
            Flash::error('Tipo Dia not found');

            return redirect(route('tipoDias.index'));
        }

        return view('tipo_dias.edit')->with('tipoDia', $tipoDia);
    }

    /**
     * Update the specified TipoDia in storage.
     *
     * @param  int              $id
     * @param UpdateTipoDiaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoDiaRequest $request)
    {
        $tipoDia = $this->tipoDiaRepository->findWithoutFail($id);

        if (empty($tipoDia)) {
            Flash::error('Tipo Dia not found');

            return redirect(route('tipoDias.index'));
        }

        $tipoDia = $this->tipoDiaRepository->update($request->all(), $id);

        Flash::success('Tipo Dia updated successfully.');

        return redirect(route('tipoDias.index'));
    }

    /**
     * Remove the specified TipoDia from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoDia = $this->tipoDiaRepository->findWithoutFail($id);

        if (empty($tipoDia)) {
            Flash::error('Tipo Dia not found');

            return redirect(route('tipoDias.index'));
        }

        $this->tipoDiaRepository->delete($id);

        Flash::success('Tipo Dia deleted successfully.');

        return redirect(route('tipoDias.index'));
    }
}
