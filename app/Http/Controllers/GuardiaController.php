<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGuardiaRequest;
use App\Http\Requests\UpdateGuardiaRequest;
use App\Repositories\GuardiaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class GuardiaController extends AppBaseController
{
    /** @var  GuardiaRepository */
    private $guardiaRepository;

    public function __construct(GuardiaRepository $guardiaRepo)
    {
        $this->guardiaRepository = $guardiaRepo;
    }

    /**
     * Display a listing of the Guardia.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $guardias = $this->guardiaRepository->all();

        return view('guardias.index')
            ->with('guardias', $guardias);
    }

    /**
     * Show the form for creating a new Guardia.
     *
     * @return Response
     */
    public function create()
    {
        return view('guardias.create');
    }

    /**
     * Store a newly created Guardia in storage.
     *
     * @param CreateGuardiaRequest $request
     *
     * @return Response
     */
    public function store(CreateGuardiaRequest $request)
    {
        $input = $request->all();

        $guardia = $this->guardiaRepository->create($input);

        Flash::success('Guardia saved successfully.');

        return redirect(route('guardias.index'));
    }

    /**
     * Display the specified Guardia.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $guardia = $this->guardiaRepository->find($id);

        if (empty($guardia)) {
            Flash::error('Guardia not found');

            return redirect(route('guardias.index'));
        }

        return view('guardias.show')->with('guardia', $guardia);
    }

    /**
     * Show the form for editing the specified Guardia.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $guardia = $this->guardiaRepository->find($id);

        if (empty($guardia)) {
            Flash::error('Guardia not found');

            return redirect(route('guardias.index'));
        }

        return view('guardias.edit')->with('guardia', $guardia);
    }

    /**
     * Update the specified Guardia in storage.
     *
     * @param int $id
     * @param UpdateGuardiaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGuardiaRequest $request)
    {
        $guardia = $this->guardiaRepository->find($id);

        if (empty($guardia)) {
            Flash::error('Guardia not found');

            return redirect(route('guardias.index'));
        }

        $guardia = $this->guardiaRepository->update($request->all(), $id);

        Flash::success('Guardia updated successfully.');

        return redirect(route('guardias.index'));
    }

    /**
     * Remove the specified Guardia from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $guardia = $this->guardiaRepository->find($id);

        if (empty($guardia)) {
            Flash::error('Guardia not found');

            return redirect(route('guardias.index'));
        }

        $this->guardiaRepository->delete($id);

        Flash::success('Guardia deleted successfully.');

        return redirect(route('guardias.index'));
    }
}
