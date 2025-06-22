<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReemplazoRequest;
use App\Http\Requests\UpdateReemplazoRequest;
use App\Repositories\ReemplazoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class ReemplazoController extends AppBaseController
{
    /** @var  ReemplazoRepository */
    private $reemplazoRepository;

    public function __construct(ReemplazoRepository $reemplazoRepo)
    {
        $this->reemplazoRepository = $reemplazoRepo;
    }

    /**
     * Display a listing of the Reemplazo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $reemplazos = $this->reemplazoRepository->all();

        return view('reemplazos.index')
            ->with('reemplazos', $reemplazos);
    }

    /**
     * Show the form for creating a new Reemplazo.
     *
     * @return Response
     */
    public function create()
    {
        return view('reemplazos.create');
    }

    /**
     * Store a newly created Reemplazo in storage.
     *
     * @param CreateReemplazoRequest $request
     *
     * @return Response
     */
    public function store(CreateReemplazoRequest $request)
    {
        $input = $request->all();

        $reemplazo = $this->reemplazoRepository->create($input);

        Flash::success('Reemplazo saved successfully.');

        return redirect(route('reemplazos.index'));
    }

    /**
     * Display the specified Reemplazo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $reemplazo = $this->reemplazoRepository->find($id);

        if (empty($reemplazo)) {
            Flash::error('Reemplazo not found');

            return redirect(route('reemplazos.index'));
        }

        return view('reemplazos.show')->with('reemplazo', $reemplazo);
    }

    /**
     * Show the form for editing the specified Reemplazo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $reemplazo = $this->reemplazoRepository->find($id);

        if (empty($reemplazo)) {
            Flash::error('Reemplazo not found');

            return redirect(route('reemplazos.index'));
        }

        return view('reemplazos.edit')->with('reemplazo', $reemplazo);
    }

    /**
     * Update the specified Reemplazo in storage.
     *
     * @param int $id
     * @param UpdateReemplazoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReemplazoRequest $request)
    {
        $reemplazo = $this->reemplazoRepository->find($id);

        if (empty($reemplazo)) {
            Flash::error('Reemplazo not found');

            return redirect(route('reemplazos.index'));
        }

        $reemplazo = $this->reemplazoRepository->update($request->all(), $id);

        Flash::success('Reemplazo updated successfully.');

        return redirect(route('reemplazos.index'));
    }

    /**
     * Remove the specified Reemplazo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $reemplazo = $this->reemplazoRepository->find($id);

        if (empty($reemplazo)) {
            Flash::error('Reemplazo not found');

            return redirect(route('reemplazos.index'));
        }

        $this->reemplazoRepository->delete($id);

        Flash::success('Reemplazo deleted successfully.');

        return redirect(route('reemplazos.index'));
    }
}
