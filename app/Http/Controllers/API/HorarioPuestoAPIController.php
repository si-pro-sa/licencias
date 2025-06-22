<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateHorarioPuestoRequest;
use App\Repositories\HorarioPuestoRepository;
use App\Http\Controllers\AppBaseController;
use Response;

class HorarioPuestoAPIController extends AppBaseController
{
    /** @var  HorarioPuestoRepository */
    private $horarioPuestoRepository;

    public function __construct(HorarioPuestoRepository $horarioPuestoRepo)
    {
        $this->horarioPuestoRepository = $horarioPuestoRepo;
    }

    /**
     * Store a newly created HorarioPuesto in storage.
     *
     * @param CreateHorarioPuestoRequest $request
     *
     * @return Response
     */
    public function store(CreateHorarioPuestoRequest $request)
    {
        $input = $request->all();

        $horarioPuesto = $this->horarioPuestoRepository->create($input);

        return $this->sendResponse($horarioPuesto, 'El Horario de la Dependencia fue creado correctamente.');
    }
}
