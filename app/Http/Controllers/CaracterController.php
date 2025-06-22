<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCaracterRequest;
use App\Http\Requests\UpdateCaracterRequest;
use App\Repositories\CaracterRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class CaracterController extends AppBaseController
{
    /** @var  CaracterRepository */
    private $caracterRepository;

    public function __construct(CaracterRepository $caracterRepo)
    {
        $this->caracterRepository = $caracterRepo;
    }

    /**
     * Display a listing of the Caracter.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $caracters = $this->caracterRepository->all();

        return view('caracters.index')
            ->with('caracters', $caracters);
    }

    /**
     * Show the form for creating a new Caracter.
     *
     * @return Response
     */
    public function create()
    {
        return view('caracters.create');
    }

    /**
     * Store a newly created Caracter in storage.
     *
     * @param CreateCaracterRequest $request
     *
     * @return Response
     */
    public function store(CreateCaracterRequest $request)
    {
        $input = $request->all();

        $caracter = $this->caracterRepository->create($input);

        Flash::success('Caracter saved successfully.');

        return redirect(route('caracters.index'));
    }

    /**
     * Display the specified Caracter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            Flash::error('Caracter not found');

            return redirect(route('caracters.index'));
        }

        return view('caracters.show')->with('caracter', $caracter);
    }

    /**
     * Show the form for editing the specified Caracter.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            Flash::error('Caracter not found');

            return redirect(route('caracters.index'));
        }

        return view('caracters.edit')->with('caracter', $caracter);
    }

    /**
     * Update the specified Caracter in storage.
     *
     * @param int $id
     * @param UpdateCaracterRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCaracterRequest $request)
    {
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            Flash::error('Caracter not found');

            return redirect(route('caracters.index'));
        }

        $caracter = $this->caracterRepository->update($request->all(), $id);

        Flash::success('Caracter updated successfully.');

        return redirect(route('caracters.index'));
    }

    /**
     * Remove the specified Caracter from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $caracter = $this->caracterRepository->find($id);

        if (empty($caracter)) {
            Flash::error('Caracter not found');

            return redirect(route('caracters.index'));
        }

        $this->caracterRepository->delete($id);

        Flash::success('Caracter deleted successfully.');

        return redirect(route('caracters.index'));
    }
}
