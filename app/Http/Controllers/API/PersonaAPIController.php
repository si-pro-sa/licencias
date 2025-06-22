<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePersonaAPIRequest;
use App\Http\Requests\API\UpdatePersonaAPIRequest;
use App\Models\Licencia;
use App\Models\Persona;
use App\Models\Agente;
use App\Repositories\PersonaRepository;
use Faker\Provider\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;

/**
 * Class PersonaController
 * @package App\Http\Controllers\API
 */

class PersonaAPIController extends AppBaseController
{
    /** @var  PersonaRepository */
    private $personaRepository;

    public function __construct(PersonaRepository $personaRepo)
    {
        $this->personaRepository = $personaRepo;
    }

    /**
     * Display a listing of the Persona.
     * GET|HEAD /personas
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->personaRepository->pushCriteria(new RequestCriteria($request));
        $this->personaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $personas = $this->personaRepository->all();

        return $this->sendResponse($personas->toArray(), 'Personas retrieved successfully');
    }

    /**
     * Store a newly created Persona in storage.
     * POST /personas
     *
     * @param CreatePersonaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreatePersonaAPIRequest $request)
    {
        $input = $request->all();

        $persona = $this->personaRepository->create($input);

        return $this->sendResponse($persona->toArray(), 'Persona saved successfully');
    }

    /**
     * Display the specified Persona.
     * GET|HEAD /personas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Persona $persona */
        $persona = $this->personaRepository->findWithoutFail($id);

        if (empty($persona)) {
            return $this->sendError('Persona not found');
        }

        return $this->sendResponse($persona->toArray(), 'Persona retrieved successfully');
    }

    /**
     * Update the specified Persona in storage.
     * PUT/PATCH /personas/{id}
     *
     * @param  int $id
     * @param UpdatePersonaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePersonaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Persona $persona */
        $persona = $this->personaRepository->findWithoutFail($id);

        if (empty($persona)) {
            return $this->sendError('Persona not found');
        }

        $persona = $this->personaRepository->update($input, $id);

        return $this->sendResponse($persona->toArray(), 'Persona updated successfully');
    }

    /**
     * Remove the specified Persona from storage.
     * DELETE /personas/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Persona $persona */
        $persona = $this->personaRepository->findWithoutFail($id);

        if (empty($persona)) {
            return $this->sendError('Persona not found');
        }

        $persona->delete();

        return $this->sendResponse($id, 'Persona deleted successfully');
    }
    public function getPersonas($idagente)
    {
        $Personas = DB::table('personas')->join('grupo_familiar_personas', function ($join) {
            $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })->join('grupo_familiares', function ($join) {
            $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                ->where('grupo_familiares.deleted_at', '=', null);
        })->where('grupo_familiares.idagente', '=', $idagente)->where('grupo_familiares.activo', '1')->where('personas.deleted_at', '=', null)->get();

        return $this->sendResponse($Personas->toArray(), 'Agente retrieved successfully');
    }
    public function getPersonasGrupo($idgrupoFamiliar)
    {
        $Personas = Persona::join('grupo_familiares', function ($join) {
            $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })
            ->join('personas', function ($join) {
                $join->on('grupo_familiar_personas.idpersona', '=', 'personas.idpersona')
                    ->where('grupo_familiares.deleted_at', '=', null);
            })->where('grupo_familiar_personas.idgrupoFamiliar', '=', $idgrupoFamiliar)->get();
        return $this->sendResponse($Personas->toArray(), 'Agente retrieved successfully');
    }
    public function getPersona($documento)
    {

        $Persona = Persona::where('documento', '=', $documento)->get();

        return $this->sendResponse($Persona->toArray(), 'Persona retrieved successfully');
    }
    public function getPersonaPorExpediente($expediente)
    {

        $Persona = Persona::join('grupo_familiar_personas', function ($join) {
            $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })->where('grupo_familiar_personas.idgrupoFamiliar', '=', $expediente)
            ->get();

        return $this->sendResponse($Persona->toArray(), 'Persona retrieved successfully');
    }
    public function getPersonasActivas($idagente)
    {

        $Persona = Persona::join('grupo_familiar_personas', function ($join) {
            $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })->join('grupo_familiares', function ($join) {
            $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                ->where('grupo_familiares.deleted_at', '=', null);
        })->where('grupo_familiares.idagente', '=', $idagente)
            ->where('grupo_familiares.activo', '1')
            ->where('personas.deleted_at', '=', null)
            ->get();

        return $this->sendResponse($Persona->toArray(), 'Persona retrieved successfully');
    }
    public function getPersonasDiscapacitadaActivas($idagente)
    {
        $Persona = Persona::join('grupo_familiar_personas', function ($join) {
            $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })->join('grupo_familiares', function ($join) {
            $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                ->where('grupo_familiares.deleted_at', '=', null);
        })->where('grupo_familiares.idagente', '=', $idagente)
            ->where('grupo_familiares.activo', '1')
            ->where('personas.discapacidad', '1')
            ->get();

        return $this->sendResponse($Persona->toArray(), 'Persona Discapacitadas retrieved successfully');
    }
    public function getDiscapacitado($idagente, $documento)
    {
        $Persona = Persona::join('grupo_familiar_personas', function ($join) {
            $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                ->where('grupo_familiar_personas.deleted_at', '=', null);
        })->join('grupo_familiares', function ($join) {
            $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                ->where('grupo_familiares.deleted_at', '=', null);
        })->where('grupo_familiares.idagente', '!=', $idagente)
            ->where('personas.documento', '=', $documento)
            ->where('grupo_familiares.activo', '1')
            ->where('personas.discapacidad', '1')
            ->get();

        return $this->sendResponse($Persona->toArray(), 'Persona Discapacitadas retrieved successfully');
    }

    public function getDiasRestanteDiscapacitado($idpersona, $idtipoLicencia, $idlicencia)
    {

        $Licencias = Licencia::join('licencia_familiares', 'licencia_familiares.idlicencia', 'licencias.idlicencia')
            ->join('personas', 'personas.idpersona', 'licencia_familiares.idpersona')
            ->leftJoin('grupo_familiar_personas', function ($join) {
                $join->on('personas.idpersona', '=', 'grupo_familiar_personas.idpersona')
                    ->where('grupo_familiar_personas.deleted_at', '=', null)
                    ->whereIn('grupo_familiar_personas.idtipoParentesco', [5, 6]);
            })->leftJoin('grupo_familiares', function ($join) {
                $join->on('grupo_familiar_personas.idgrupoFamiliar', '=', 'grupo_familiares.idgrupoFamiliar')
                    ->where('grupo_familiares.deleted_at', '=', null);
            })->where('personas.idpersona', '=', $idpersona)
            ->where('grupo_familiares.activo', true)
            ->where('personas.discapacidad', true)
            ->whereNotNull('licencias.primer_visado')
            ->whereNotNull('licencias.segundo_visado')
            ->whereNull('licencias.cuarta_visado')
            ->where('licencias.idtipoLicencia', '=', $idtipoLicencia)
            ->where('licencias.idlicencia', '!=', $idlicencia)
            ->select('licencias.idlicencia as idlicencia')
            ->distinct()
            ->get()->toArray();


        $dias_consumidos = Licencia::whereIn('licencias.idlicencia', $Licencias)
            ->join('licencia_saldos', 'licencia_saldos.idlicencia', 'licencias.idlicencia')
            ->groupBy('licencia_saldos.año')
            ->selectRaw('sum(licencia_saldos.dias) as dias, licencia_saldos.año as año')
            ->get()->toArray();

        return $this->sendResponse($dias_consumidos, 'Dias Consumidos Discapacitados retrieved successfully');
    }
}
