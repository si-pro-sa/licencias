<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAgenteAPIRequest;
use App\Http\Requests\API\UpdateAgenteAPIRequest;
use App\Http\Requests\API\UpdateContactAPIRequest;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\Domicilio;
use App\Repositories\AgenteRepository;
use App\Repositories\DomicilioRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\CapacitacionAgente;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

/**
 * Class AgenteController
 * @package App\Http\Controllers\API
 */
class AgenteAPIController extends AppBaseController
{
    /** @var  AgenteRepository */
    private $agenteRepository;
    private $domicilioRepository;

    public function __construct(AgenteRepository $agenteRepo, DomicilioRepository $domicilioRepository)
    {
        $this->agenteRepository = $agenteRepo;
        $this->domicilioRepository = $domicilioRepository;
    }

    /**
     * Display a listing of the Agente.
     * GET|HEAD /agentes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $agentes = Agente::dni($request->get('dni'))->get(['documento', 'apellido', 'nombre']);

        return $this->sendResponse($agentes->toArray(), 'Agentes retrieved successfully');
    }

    public function agentePuestoDependiente($dni)
    {
        $agentes = Agente::dni($dni)->get(['documento', 'apellido', 'nombre']);

        return $this->sendResponse($agentes->toArray(), 'Agentes retrieved successfully');
    }

    /**
     * Store a newly created Agente in storage.
     * POST /agentes
     *
     * @param CreateAgenteAPIRequest $request
     *
     * @return Response
     * @throws ValidatorException
     */
    public function store(CreateAgenteAPIRequest $request)
    {
        $input = $request->all();

        $agentes = $this->agenteRepository->create($input);

        return $this->sendResponse($agentes->toArray(), 'Agente saved successfully');
    }

    /**
     * Display the specified Agente.
     * GET|HEAD /agentes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Agente $agente */
        $agente = $this->agenteRepository->findWithoutFail($id);

        if (empty($agente)) {
            return $this->sendError('Agente not found');
        }

        return $this->sendResponse($agente->toArray(), 'Agente retrieved successfully');
    }

    /**
     * Update the specified Agente in storage.
     * PUT/PATCH /agentes/{id}
     *
     * @param int $id
     * @param UpdateAgenteAPIRequest $request
     *
     * @return Response
     * @throws ValidatorException
     */
    public function update($id, UpdateAgenteAPIRequest $request)
    {
        $input = $request->all();

        /** @var Agente $agente */
        $agente = $this->agenteRepository->findWithoutFail($id);

        if (empty($agente)) {
            return $this->sendError('Agente not found');
        }

        $agente = $this->agenteRepository->update($input, $id);

        return $this->sendResponse($agente->toArray(), 'Agente updated successfully');
    }

    /**
     * Remove the specified Agente from storage.
     * DELETE /agentes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Agente $agente */
        $agente = $this->agenteRepository->findWithoutFail($id);

        if (empty($agente)) {
            return $this->sendError('Agente not found');
        }

        $agente->delete();

        return $this->sendResponse($id, 'Agente deleted successfully');
    }

    public function searchAgente(Request $request)
    {
        $dependencias = $this->agenteRepository->with('puestos.tipoFuncion')
            ->with('puestos.tipoAgrupamiento')
            ->with('puestos.tipoNivel')
            ->with('puestos.tipoPlanta')
            ->with('puestos.tipoEspecialidad')
            ->with('agenteTitulos.titulo')
            ->getAgente($request->get('agente'));

        if (empty($dependencias)) {
            return $this->sendError('No se encontro el agente');
        }

        return $this->sendResponse($dependencias->toArray(), 'Agente retrieved successfully', 200);
    }

    public function updateContactData(UpdateContactAPIRequest $request, $id)
    {
        if (User::tienePermiso('AgenteAPIController-updateContactData')) {
            $agente = $this->agenteRepository->findWithoutFail($id);

            if (empty($agente)) {
                return $this->sendError('No se encontró el agente');
            }

            DB::beginTransaction();
            $domicilio = $this->domicilioRepository->findWithoutFail($agente->iddomicilio);

            if (!isset($domicilio) || empty($domicilio)) {
                $domicilio = $this->domicilioRepository->create($request->all(Domicilio::$parameters)['domicilio']);
            } else {
                $domicilio = $this->domicilioRepository->update($request->all(Domicilio::$parameters)['domicilio'], $domicilio->iddomicilio);
            }

            if (!isset($domicilio) || empty($domicilio)) {
                DB::rollback();
                return $this->sendError('*Error al guardar el domicilio, verifique los campos obligatorios.', 422);
            }
            $data = [
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'email' => $request->email,
                'iddomicilio' => $domicilio->iddomicilio
            ];
            $agente = $this->agenteRepository->update($data, $id);
            if ($agente) {
                DB::commit();
                return $this->sendResponse($agente, 'Información de contacto actualizada correctamente.', 201);
            } else {
                DB::rollBack();
                return $this->sendError('*Error al actualizar datos de contacto.', 422);
            }
        }
        return $this->sendError('No tiene permisos', 403);
    }

    public function updateCandidateData(Request $request, $id)
    {
        if (User::tienePermiso('AgenteAPIController-updateCandidateData')) {
            $this->validate($request, [
                'telefono' => 'string|nullable',
                'celular' => 'required|string',
                'email' => 'required|email',
            ]);

            $agente = $this->agenteRepository->findWithoutFail($id);
            if (!isset($agente) || $agente === null) {
                $this->sendError('No se encontro el Agente', 422);
            }
            $agente = array();
            $agente['telefono'] = $request->telefono;
            $agente['celular'] = $request->celular;
            $agente['email'] = $request->email;

            DB::beginTransaction();
            $domicilio = Domicilio::create($request->domicilio);

            if (!isset($domicilio)) {
                DB::rollback();
                return $this->sendError('*Error al guardar el candidato, verifique los campos obligatorios.', 422);
            }

            $agente['iddomicilio'] = $domicilio->iddomicilio;
            $agente = $this->agenteRepository->update($agente);
            if (isset($agente) && !empty($agente)) {
                DB::commit();
                return $this->sendResponse($agente, 'Información de contacto actualizada correctamente.', 201);
            }

            DB::rollBack();
            return $this->sendError('*Error al actualizar datos de contacto, verifique los campos obligatorios.', 422);
        }
        return $this->sendError('No tiene permisos', 403);
    }

    public function getDatosAgenteConBaja(int $documento)
    {
        $puesto = Agente::buscarUltimoPuestoCerrado($documento);
        if (!isset($puesto)) {
            return $this->sendError('Agente no encontrado');
        }
        if (isset($puesto->cese) && !in_array($puesto->cese->idtipo_cese, [1, 2, 3, 4])) {
            return $this->sendError('Las razones de cierre de Puesto deben ser: Renuncia, Jubilación, Fallecimiento o Cesantía');
        }
        if (!in_array($puesto->idtipo_planta, [1, 2, 6])) {
            return $this->sendError('El último Puesto del Agente Reemplazado debe ser: Permanente Interino, Permanente Titular o Transitorio');
        }
        if (isset($puesto, $puesto->cese)) {
            $datosAgente = [
                'documento' => $puesto->agente->documento,
                'nombre' => $puesto->agente->apellido . ' ' . $puesto->agente->nombre,
                'fnacimiento' => $puesto->agente->fnacimiento->format('d/m/Y'),
                'edad' => $puesto->agente->edad,
                'titulo' => $puesto->formacion->titulo ?? '',
                'especialidad' => $puesto->tipoEspecialidad->tipoespecialidad ?? '',
                'funcion' => $puesto->tipoFuncion->tipofuncion ?? '',
                'nivel' => $puesto->tipoNivel->tiponivel ?? '',
                'agrupamiento' => $puesto->tipoAgrupamiento->tipoagrupamiento ?? '',
                'efector' => $puesto->dependencia->getPadre() ?? '',
                'servicio' => $puesto->dependencia->dependencia ?? '',
                'razon_baja' => $puesto->razonCese() ?? '',
            ];
            return $this->sendResponse($datosAgente, 'Datos de Agente');
        } else {
            return $this->sendError('Agente no existe o tiene un Puesto activo');
        }
    }

    public function getAgentePropuesto(int $documento)
    {
        $agente = Agente::buscarDocumento($documento);
        if (isset($agente)) {
            $puesto = $agente->puestoActual();
            if (!isset($puesto->tipoFuncion->tipofuncion) || empty($puesto->tipoFuncion->tipofuncion)) {
                return $this->sendError('El Agente Propuesto no posee Función');
            }

            if (!($agente->getPrimerTitulo() !== null)) {
                return $this->sendError('El Agente Propuesto no posee Título');
            }

            if (!isset($puesto->tipoNivel->tiponivel) || empty($puesto->tipoNivel->tiponivel)) {
                return $this->sendError('El Agente Propuesto no posee Nivel');
            }

            if (!isset($puesto->tipoAgrupamiento->tipoagrupamiento) || empty($puesto->tipoAgrupamiento->tipoagrupamiento)) {
                return $this->sendError('El Agente Propuesto no posee Agrupamiento');
            }

            if ($puesto->idtipo_planta === 1 || $puesto->idtipo_planta === 2 || $puesto->idtipo_planta === 6) {
                return $this->sendError('El Agente Propuesto es de Planta Perminante Interino, Permanente Titular o Planta Transitoria');
            }

            if ($puesto->idtipo_planta === 1 || $puesto->idtipo_planta === 2 || $puesto->idtipo_planta === 6) {
                return $this->sendError('El Agente Propuesto es de Planta Perminante Interino, Permanente Titular o Planta Transitoria');
            }

            if ($puesto->idtipo_planta === 1 || $puesto->idtipo_planta === 2 || $puesto->idtipo_planta === 6) {
                return $this->sendError('El Agente Propuesto es de Planta Perminante Interino, Permanente Titular o Planta Transitoria');
            }

            if (!($agente->tieneCargoActivo() !== null)) {
                return $this->sendError('El Agente Propuesto ya posee un Cargo Activo');
            }

            if (!$agente->psicotecnicoAprobado()) {
                return $this->sendError('El candidato no posee Psicotécnico Aprobado');
            }

            $datosAgente = [
                'documento' => $agente->documento,
                'nombre' => $agente->apellido . ' ' . $agente->nombre,
                'fnacimiento' => $agente->fnacimiento->format('d/m/Y'),
                'edad' => $agente->edad,
                'funcion' => $puesto->tipoFuncion->tipofuncion ?? '',
                'idtipo_funcion' => $puesto->idtipo_funcion ?? '',
                'titulo' => $agente->titulos[0]->titulo ?? '',
                'nivel' => $puesto->tipoNivel->tiponivel ?? '',
                'agrupamiento' => $puesto->tipoAgrupamiento->tipoagrupamiento ?? '',
            ];
            return $this->sendResponse($datosAgente, 'Datos de Agente');
        } else {
            $candidato = Candidato::buscarDocumento($documento);
            if (!isset($candidato)) {
                return $this->sendError('Agente Propuesto no encontrado');
            }

            if (!isset($candidato->fnacimiento) || empty($candidato->fnacimiento)) {
                return $this->sendError('El Agente Propuesto no posee Fecha de Nacimiento');
            }

            if (!isset($candidato->tipoFuncion->tipofuncion) || empty($candidato->tipoFuncion->tipofuncion)) {
                return $this->sendError('El Agente Propuesto no posee Función');
            }

            if (!($candidato->tieneCargoActivo() !== null)) {
                return $this->sendError('El Agente Propuesto ya posee un Cargo Activo');
            }

            if (isset($candidato->recomendacion) && count($candidato->recomendacion) > 0) {
                $recomendacion = $candidato->recomendacion[0];
                $datosCandidato = [
                    'documento' => $candidato->documento,
                    'nombre' => $candidato->apellido . ' ' . $candidato->nombre,
                    'fnacimiento' => $candidato->fnacimiento->format('d/m/Y'),
                    'edad' => $candidato->edad,
                    'funcion' => $candidato->tipoFuncion->tipofuncion,
                    'idtipo_funcion' => $candidato->idtipo_funcion,
                    'titulo' => $recomendacion->formacion->titulo,
                    'nivel' => $recomendacion->nivel->tiponivel,
                    'agrupamiento' => $recomendacion->agrupamiento->tipoagrupamiento ?? '',
                ];
                return $this->sendResponse($datosCandidato, 'Datos de Agente Propuesto');
            }
        }

        return $this->sendError('Agente Propuesto no encontrado');
    }
    public function getDatosAgente(int $idagente)
    {
        $agente = $this->agenteRepository->getDatosAgente($idagente);
        if (isset($agente)) {
            return $this->sendResponse($agente, 'Datos de Agente');
        }

        return $this->sendError('Agente no encontrado');
    }
    public function getAgenteCapacitaciones($idagente)
    {
        $agente = $this->agenteRepository->getDatosAgente($idagente);
        if (isset($agente)) {
            $capacitaciones = CapacitacionAgente::leftJoin('capacitacion', 'capacitacion_agente.idCapacitacion', '=', 'capacitacion.idCapacitacion')
                ->leftJoin('tipo_evento as tp', 'tp.idTipoEvento', '=', 'capacitacion.idTipoEvento')
                ->leftJoin('alcance_capacitacion as al', 'al.idAlcanceCapacitacion', '=', 'capacitacion.idAlcanceCapacitacion')
                ->leftJoin('caracter as ca', 'ca.idCaracter', '=', 'capacitacion.idCaracter')
                ->selectRaw('capacitacion.*, tp.descripcion as tipoEvento, al.descripcion as alcance, ca.descripcion as caracter')
                ->where('capacitacion_agente.idAgente', '=', $idagente)->get();
            return $this->sendResponse($capacitaciones->toArray(), 'Datos de Capacitacion');
        }

        return $this->sendError('Agente no encontrado');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDepartmentDetails($id)
    {
        $agente = Agente::find($id);

        if (!$agente) {
            return response()->json(['message' => 'Agente no encontrado'], 404);
        }

        $departmentDetails = $agente->getDepartmentDetails();

        return response()->json([
            'agente' => $agente->only(['idagente', 'nombre', 'apellido']),
            'departmentDetails' => $departmentDetails,
        ]);
    }
}
