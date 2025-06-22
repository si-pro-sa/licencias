<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLdCodigoRequest;
use App\Http\Requests\UpdateLdCodigoRequest;
use App\Repositories\LdCodigoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class LdCodigoController extends AppBaseController
{
    /** @var  LdCodigoRepository */
    private $ldCodigoRepository;

    public function __construct(LdCodigoRepository $ldCodigoRepo)
    {
        $this->ldCodigoRepository = $ldCodigoRepo;
    }

    /**
     * Display a listing of the LdCodigo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ldCodigos = $this->ldCodigoRepository->all();

        return view('ld_codigos.index')
            ->with('ldCodigos', $ldCodigos);
    }

    /**
     * Show the form for creating a new LdCodigo.
     *
     * @return Response
     */
    public function create()
    {
        return view('ld_codigos.create');
    }

    /**
     * Store a newly created LdCodigo in storage.
     *
     * @param CreateLdCodigoRequest $request
     *
     * @return Response
     */
    public function store(CreateLdCodigoRequest $request)
    {
        $input = $request->all();

        $ldCodigo = $this->ldCodigoRepository->create($input);

        Flash::success('Ld Codigo saved successfully.');

        return redirect(route('ldCodigos.index'));
    }

    /**
     * Display the specified LdCodigo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ldCodigo = $this->ldCodigoRepository->find($id);

        if (empty($ldCodigo)) {
            Flash::error('Ld Codigo not found');

            return redirect(route('ldCodigos.index'));
        }

        return view('ld_codigos.show')->with('ldCodigo', $ldCodigo);
    }

    /**
     * Show the form for editing the specified LdCodigo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ldCodigo = $this->ldCodigoRepository->find($id);

        if (empty($ldCodigo)) {
            Flash::error('Ld Codigo not found');

            return redirect(route('ldCodigos.index'));
        }

        return view('ld_codigos.edit')->with('ldCodigo', $ldCodigo);
    }

    /**
     * Update the specified LdCodigo in storage.
     *
     * @param int $id
     * @param UpdateLdCodigoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLdCodigoRequest $request)
    {
        $ldCodigo = $this->ldCodigoRepository->find($id);

        if (empty($ldCodigo)) {
            Flash::error('Ld Codigo not found');

            return redirect(route('ldCodigos.index'));
        }

        $ldCodigo = $this->ldCodigoRepository->update($request->all(), $id);

        Flash::success('Ld Codigo updated successfully.');

        return redirect(route('ldCodigos.index'));
    }

    /**
     * Remove the specified LdCodigo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ldCodigo = $this->ldCodigoRepository->find($id);

        if (empty($ldCodigo)) {
            Flash::error('Ld Codigo not found');

            return redirect(route('ldCodigos.index'));
        }

        $this->ldCodigoRepository->delete($id);

        Flash::success('Ld Codigo deleted successfully.');

        return redirect(route('ldCodigos.index'));
    }
}
