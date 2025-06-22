<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLdAltaRequest;
use App\Http\Requests\UpdateLdAltaRequest;
use App\Repositories\LdAltaRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\LdAlta;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Flash;
use Response;

class LdAltaController extends AppBaseController
{
    /** @var  LdAltaRepository */
    private $ldAltaRepository;

    public function __construct(LdAltaRepository $ldAltaRepo)
    {
        $this->ldAltaRepository = $ldAltaRepo;
    }

    /**
     * Display a listing of the LdAlta.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ldAltas = $this->ldAltaRepository->all();

        return view('ld_altas.index')
            ->with('ldAltas', $ldAltas);
    }

    /**
     * Show the form for creating a new LdAlta.
     *
     * @return Response
     */
    public function create()
    {
        return view('ld_altas.create');
    }

    /**
     * Store a newly created LdAlta in storage.
     *
     * @param CreateLdAltaRequest $request
     *
     * @return Response
     */
    public function store(CreateLdAltaRequest $request)
    {
        $input = $request->all();

        $ldAlta = $this->ldAltaRepository->create($input);

        Flash::success('Ld Alta saved successfully.');

        return redirect(route('ldAltas.index'));
    }

    /**
     * Display the specified LdAlta.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ldAlta = $this->ldAltaRepository->find($id);

        if (empty($ldAlta)) {
            Flash::error('Ld Alta not found');

            return redirect(route('ldAltas.index'));
        }

        return view('ld_altas.show')->with('ldAlta', $ldAlta);
    }

    /**
     * Show the form for editing the specified LdAlta.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ldAlta = $this->ldAltaRepository->find($id);

        if (empty($ldAlta)) {
            Flash::error('Ld Alta not found');

            return redirect(route('ldAltas.index'));
        }

        return view('ld_altas.edit')->with('ldAlta', $ldAlta);
    }

    /**
     * Update the specified LdAlta in storage.
     *
     * @param int $id
     * @param UpdateLdAltaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLdAltaRequest $request)
    {
        $ldAlta = $this->ldAltaRepository->find($id);

        if (empty($ldAlta)) {
            Flash::error('Ld Alta not found');

            return redirect(route('ldAltas.index'));
        }

        $ldAlta = $this->ldAltaRepository->update($request->all(), $id);

        Flash::success('Ld Alta updated successfully.');

        return redirect(route('ldAltas.index'));
    }

    /**
     * Remove the specified LdAlta from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ldAlta = $this->ldAltaRepository->find($id);

        if (empty($ldAlta)) {
            Flash::error('Ld Alta not found');

            return redirect(route('ldAltas.index'));
        }

        $this->ldAltaRepository->delete($id);

        Flash::success('Ld Alta deleted successfully.');

        return redirect(route('ldAltas.index'));
    }
}
