<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateEvaluacionPsicotecnicaGrupoAPIRequest;
use App\Http\Requests\API\UpdateEvaluacionPsicotecnicaGrupoAPIRequest;
use App\Models\EvaluacionPsicotecnicaGrupo;
use App\Repositories\EvaluacionPsicotecnicaGrupoRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class EvaluacionPsicotecnicaGrupoController
 * @package App\Http\Controllers\API
 */
class EvaluacionPsicotecnicaGrupoAPIController extends AppBaseController
{
    /** @var  EvaluacionPsicotecnicaGrupoRepository */
    private $evaluacionPsicotecnicaGrupoRepository;

    public function __construct(EvaluacionPsicotecnicaGrupoRepository $evaluacionPsicotecnicaGrupoRepo)
    {
        $this->evaluacionPsicotecnicaGrupoRepository = $evaluacionPsicotecnicaGrupoRepo;
    }

    /**
     * Display a listing of the EvaluacionPsicotecnicaGrupo.
     * GET|HEAD /evaluacionPsicotecnicaGrupos
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (User::tienePermiso('EvaluacionPsicotecnicaGrupoAPIController-index')) {
            $evaluacionPsicotecnicaGrupos = $this->evaluacionPsicotecnicaGrupoRepository->all(
                $request->except(['skip', 'limit']),
                $request->get('skip'),
                $request->get('limit')
            );

            return $this->sendResponse($evaluacionPsicotecnicaGrupos->toArray(), 'Evaluacion Psicotecnica Grupos retrieved successfully');
        }
        return $this->sendError('No tiene permisos', 403);
    }

    /**
     * Store a newly created EvaluacionPsicotecnicaGrupo in storage.
     * POST /evaluacionPsicotecnicaGrupos
     *
     * @param CreateEvaluacionPsicotecnicaGrupoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEvaluacionPsicotecnicaGrupoAPIRequest $request)
    {
        if (User::tienePermiso('EvaluacionPsicotecnicaGrupoAPIController-store')) {
            $input = $request->all();

            $evaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepository->create($input);

            return $this->sendResponse($evaluacionPsicotecnicaGrupo->toArray(), 'Evaluacion Psicotecnica Grupo saved successfully');
        }
        return $this->sendError('No tiene permisos', 403);
    }

    /**
     * Display the specified EvaluacionPsicotecnicaGrupo.
     * GET|HEAD /evaluacionPsicotecnicaGrupos/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EvaluacionPsicotecnicaGrupo $evaluacionPsicotecnicaGrupo */
        $evaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepository->find($id);

        if ($evaluacionPsicotecnicaGrupo === null) {
            return $this->sendError('Evaluacion Psicotecnica Grupo not found');
        }

        return $this->sendResponse($evaluacionPsicotecnicaGrupo->toArray(), 'Evaluacion Psicotecnica Grupo retrieved successfully');
    }

    /**
     * Update the specified EvaluacionPsicotecnicaGrupo in storage.
     * PUT/PATCH /evaluacionPsicotecnicaGrupos/{id}
     *
     * @param int $id
     * @param UpdateEvaluacionPsicotecnicaGrupoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEvaluacionPsicotecnicaGrupoAPIRequest $request)
    {
        $input = $request->all();

        /** @var EvaluacionPsicotecnicaGrupo $evaluacionPsicotecnicaGrupo */
        $evaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepository->find($id);

        if (empty($evaluacionPsicotecnicaGrupo)) {
            return $this->sendError('Evaluacion Psicotecnica Grupo not found');
        }

        $evaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepository->update($input, $id);

        return $this->sendResponse($evaluacionPsicotecnicaGrupo->toArray(), 'EvaluacionPsicotecnicaGrupo updated successfully');
    }

    /**
     * Remove the specified EvaluacionPsicotecnicaGrupo from storage.
     * DELETE /evaluacionPsicotecnicaGrupos/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var EvaluacionPsicotecnicaGrupo $evaluacionPsicotecnicaGrupo */
        $evaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepository->find($id);

        if ($evaluacionPsicotecnicaGrupo === null) {
            return $this->sendError('Evaluacion Psicotecnica Grupo not found');
        }

        $evaluacionPsicotecnicaGrupo->delete();

        return $this->sendSuccess('Evaluacion Psicotecnica Grupo deleted successfully');
    }
}
