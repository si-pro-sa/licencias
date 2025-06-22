<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSancionRequest;
use App\Http\Requests\UpdateSancionRequest;
use App\Repositories\SancionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SancionController extends AppBaseController
{
    /** @var  SancionRepository */
    private $sancionRepository;

    public function __construct(SancionRepository $sancionRepo)
    {
        $this->sancionRepository = $sancionRepo;
    }

    /**
     * Display a listing of the Sancion.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->sancionRepository->pushCriteria(new RequestCriteria($request));
        $sancions = $this->sancionRepository->all();

        return view('sancions.index')
            ->with('sancions', $sancions);
    }

    /**
     * Show the form for creating a new Sancion.
     *
     * @return Response
     */
    public function create()
    {
        return view('sancions.create');
    }

    /**
     * Store a newly created Sancion in storage.
     *
     * @param CreateSancionRequest $request
     *
     * @return Response
     */
    public function store(CreateSancionRequest $request)
    {
        $input = $request->all();

        $sancion = $this->sancionRepository->create($input);

        Flash::success('Sancion saved successfully.');

        return redirect(route('sancions.index'));
    }

    /**
     * Display the specified Sancion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            Flash::error('Sancion not found');

            return redirect(route('sancions.index'));
        }

        return view('sancions.show')->with('sancion', $sancion);
    }

    /**
     * Show the form for editing the specified Sancion.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            Flash::error('Sancion not found');

            return redirect(route('sancions.index'));
        }

        return view('sancions.edit')->with('sancion', $sancion);
    }

    /**
     * Update the specified Sancion in storage.
     *
     * @param  int              $id
     * @param UpdateSancionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSancionRequest $request)
    {
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            Flash::error('Sancion not found');

            return redirect(route('sancions.index'));
        }

        $sancion = $this->sancionRepository->update($request->all(), $id);

        Flash::success('Sancion updated successfully.');

        return redirect(route('sancions.index'));
    }

    /**
     * Remove the specified Sancion from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $sancion = $this->sancionRepository->findWithoutFail($id);

        if (empty($sancion)) {
            Flash::error('Sancion not found');

            return redirect(route('sancions.index'));
        }

        $this->sancionRepository->delete($id);

        Flash::success('Sancion deleted successfully.');

        return redirect(route('sancions.index'));
    }
}
