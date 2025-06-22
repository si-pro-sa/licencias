<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTipoCampaniaRequest;
use App\Http\Requests\UpdateTipoCampaniaRequest;
use App\Repositories\TipoCampaniaRepository;
use Illuminate\Http\Request;
use Flash;
use Response;

class TipoCampaniaController extends AppBaseController
{
    /** @var  TipoCampaniaRepository */
    private $tipoCampaniaRepository;

    public function __construct(TipoCampaniaRepository $tipoCampaniaRepo)
    {
        $this->tipoCampaniaRepository = $tipoCampaniaRepo;
    }

    /**
     * Display a listing of the TipoCampania.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $tipoCampanias = $this->tipoCampaniaRepository->all();

        return view('tipo_campanias.index')
            ->with('tipoCampanias', $tipoCampanias);
    }

    /**
     * Show the form for creating a new TipoCampania.
     *
     * @return Response
     */
    public function create()
    {
        return view('tipo_campanias.create');
    }

    /**
     * Store a newly created TipoCampania in storage.
     *
     * @param CreateTipoCampaniaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoCampaniaRequest $request)
    {
        $input = $request->all();

        $tipoCampania = $this->tipoCampaniaRepository->create($input);

        Flash::success('Tipo Campania saved successfully.');

        return redirect(route('tipoCampanias.index'));
    }

    /**
     * Display the specified TipoCampania.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tipoCampania = $this->tipoCampaniaRepository->find($id);

        if (empty($tipoCampania)) {
            Flash::error('Tipo Campania not found');

            return redirect(route('tipoCampanias.index'));
        }

        return view('tipo_campanias.show')->with('tipoCampania', $tipoCampania);
    }

    /**
     * Show the form for editing the specified TipoCampania.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tipoCampania = $this->tipoCampaniaRepository->find($id);

        if (empty($tipoCampania)) {
            Flash::error('Tipo Campania not found');

            return redirect(route('tipoCampanias.index'));
        }

        return view('tipo_campanias.edit')->with('tipoCampania', $tipoCampania);
    }

    /**
     * Update the specified TipoCampania in storage.
     *
     * @param int $id
     * @param UpdateTipoCampaniaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoCampaniaRequest $request)
    {
        $tipoCampania = $this->tipoCampaniaRepository->find($id);

        if (empty($tipoCampania)) {
            Flash::error('Tipo Campania not found');

            return redirect(route('tipoCampanias.index'));
        }

        $tipoCampania = $this->tipoCampaniaRepository->update($request->all(), $id);

        Flash::success('Tipo Campania updated successfully.');

        return redirect(route('tipoCampanias.index'));
    }

    /**
     * Remove the specified TipoCampania from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tipoCampania = $this->tipoCampaniaRepository->find($id);

        if (empty($tipoCampania)) {
            Flash::error('Tipo Campania not found');

            return redirect(route('tipoCampanias.index'));
        }

        $this->tipoCampaniaRepository->delete($id);

        Flash::success('Tipo Campania deleted successfully.');

        return redirect(route('tipoCampanias.index'));
    }
}
