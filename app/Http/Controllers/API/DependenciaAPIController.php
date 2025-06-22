<?php

namespace App\Http\Controllers\API;

use Response;
use App\Models\Dependencia;
use Illuminate\Http\Request;
use App\Models\HorarioDependencia;
use Illuminate\Support\Facades\DB;
use App\Models\DependenciaRelacion;
use App\Http\Controllers\AppBaseController;
use App\Repositories\DependenciaRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\DependenciaRelacionRepository;
use App\Http\Requests\API\CreateDependenciaAPIRequest;
use App\Http\Requests\API\UpdateDependenciaAPIRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class DependenciaController.
 */
class DependenciaAPIController extends AppBaseController
{
    /** @var  DependenciaRepository
     * @var DependenciaRelacionRepository
     * */
    private $dependenciaRepository;
    private $dependenciaRelacionRepository;

    public function __construct(DependenciaRepository $dependenciaRepo, DependenciaRelacionRepository $dependenciaRelacionRepository)
    {
        $this->dependenciaRepository = $dependenciaRepo;
        $this->dependenciaRelacionRepository = $dependenciaRelacionRepository;
    }

    /**
     * Display a listing of the Dependencia.
     * GET|HEAD /dependencias.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->dependenciaRepository->pushCriteria(new RequestCriteria($request));
        $dependencias = $this->dependenciaRepository->paginate(15);

        return $this->sendResponse($dependencias->toArray(), 'Dependencias retrieved successfully');
    }

    /**
     * Store a newly created Dependencia in storage.
     * POST /dependencias.
     *
     * @return Response
     */
    public function store(CreateDependenciaAPIRequest $request)
    {
        $input = $request->all();

        $dependencias = $this->dependenciaRepository->create($input);

        return $this->sendResponse($dependencias->toArray(), 'Dependencia saved successfully');
    }

    /**
     * Display the specified Dependencia.
     * GET|HEAD /dependencias/{id}.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Dependencia $dependencia */
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            return $this->sendError('Dependencia not found');
        }

        return $this->sendResponse($dependencia->toArray(), 'Dependencia retrieved successfully');
    }

    /**
     * Update the specified Dependencia in storage.
     * PUT/PATCH /dependencias/{id}.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id, UpdateDependenciaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Dependencia $dependencia */
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            return $this->sendError('Dependencia not found');
        }

        $dependencia = $this->dependenciaRepository->update($input, $id);

        return $this->sendResponse($dependencia->toArray(), 'Dependencia updated successfully');
    }

    /**
     * Remove the specified Dependencia from storage.
     * DELETE /dependencias/{id}.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Dependencia $dependencia */
        $dependencia = $this->dependenciaRepository->findWithoutFail($id);

        if (empty($dependencia)) {
            return $this->sendError('Dependencia not found');
        }

        $dependencia->delete();

        return $this->sendResponse($id, 'Dependencia deleted successfully');
    }

    public function searchDependencia(Request $request)
    {
        $dependencias = $this->dependenciaRepository->getDependencia($request->get('dependencia'));

        if (empty($dependencias)) {
            return $this->sendError('Dependencia not found');
        } else {
            foreach ($dependencias as $dependencia) {
                $raw = DB::raw('CONCAT(codigorrhh,dependencia) AS label');
                $dependencia['hijas'] = Dependencia::codigo($dependencia['codigorrhh'])->orderBy('codigorrhh')->get(['iddependencia', $raw, 'codigorrhh', 'dependencia'])->toArray();
            }
        }

        return $this->sendResponse($dependencias->toArray(), 'Dependencias retrieved successfully' . env('APP_URL'), 200);
    }

    /**
     * Muestro listado de dependencias utilizando json.
     *
     * @return JSON
     */
    public function vueSelectDependencias()
    {
        return $this->sendResponse(Dependencia::whereNotNull('codigorrhh')->get()->map(function ($model) {
            return ['value' => $model->iddependencia, 'label' => $model->codigorrhh . ' - ' . $model->dependencia];
        })->all(), 'Organismos ADELIA');
    }

    /**
     * Muestro listado de dependencias utilizando json.
     *
     * @return JSON
     */
    public function vueSelectEfectores(bool $soloRed = false)
    {
        $dependencias = [];
        if ($soloRed) {
            $dependencias = Dependencia::soloEfectoresRed()->map->format()->filter()->all();
        } else {
            $dependencias = Dependencia::soloEfectores()->map->format()->filter()->all();
        }

        return $this->sendResponse($dependencias, 'Listado de Efectores');
    }

    /**
     * Muestro listado de dependencias utilizando json.
     *
     * @return JSON
     */
    public function vueSelectServicios(int $iddependencia)
    {
        $dependencias = Dependencia::hijas($iddependencia)->map->format();

        return $this->sendResponse($dependencias, 'Listado de Servicios');
    }

    /**
     * Muestro información de Dependencia
     * Horario, cantidad de agentes por función en horario o no
     * para alta de cargos.
     *
     * @return JSON
     */
    public function getDotacion(int $idefector, int $idservicio, int $idtipo_funcion, string $hora_desde, string $hora_hasta, int $idtipo_dia = 999)
    {
        return $this->sendResponse(Dependencia::getDotacionDependencias($idefector, $idservicio, $idtipo_funcion, $hora_desde, $hora_hasta, $idtipo_dia), 'Totales');
    }

    public function tree(Request $request)
    {
        if ($request->get('dependencia') != null) {
            $this->dependenciaRelacionRepository->dependencia($request->get('dependencia'));
        } else {
            if ($request->get('codigorrhh') == null) {
                $this->dependenciaRelacionRepository->raiz(2576);
            }
        }

        if ($request->get('codigorrhh') != null) {
            $this->dependenciaRelacionRepository->codigo($request->get('codigorrhh'));
        }

        $dependenciaRelacion = $this->dependenciaRelacionRepository->all();

        if (empty($dependenciaRelacion)) {
            return $this->sendError('Dependencia not found');
        } else {
            $dependenciasArray = [];
            $idPadres = array_unique(array_column($dependenciaRelacion->toArray(), 'iddependenciapadre'), SORT_NUMERIC);

            $raw = DB::raw("iddependencia,codigorrhh,dependencia,CONCAT(COALESCE(codigorrhh,''),' - ',dependencia) AS label");
            $dependenciasPadres = Dependencia::select($raw)->whereIn('iddependencia', $idPadres)->paginate();
            $structure = Dependencia::whereIn('iddependencia', $idPadres)->paginate();

            $dependenciasArray = $this->hijas($dependenciasPadres);

            $max_depth = 3;
            if ($request->get('dependencia') != null) {
                foreach ($dependenciasArray as $dependencia) {
                    $dependencia['padre'] = Dependencia::where(
                        'iddependencia',
                        DependenciaRelacion::where('iddependenciahija', $dependencia->iddependencia)->first()->dependenciaPadre->iddependencia
                    )->first(['iddependencia', 'codigorrhh', 'dependencia']);
                }
                $max_depth = 2;
            }
        }

        return $this->sendResponse(['data' => $dependenciasArray, 'structure' => $structure, 'max_depth' => $max_depth], 'Dependencias retrieved successfully');
    }

    // Función recursiva para obtener las dependencias hijas
    public function getHijasConsulta($iddependencia)
    {
        $dependencias = Dependencia::hijas($iddependencia)->map->format();

        return $this->sendResponse($dependencias, 'Listado de Servicios');
    }

    private function hijas($dependenciasPadres)
    {
        $array = [];
        foreach ($dependenciasPadres as $padre) {
            $raw = DB::raw("iddependencia,codigorrhh,dependencia,CONCAT(COALESCE(codigorrhh,''),' - ',dependencia) AS label");
            $hijas = DependenciaRelacion::where('iddependenciapadre', $padre->iddependencia)->get(['iddependenciarelacion', 'iddependenciapadre', 'iddependenciahija']);

            $idPadres = array_unique(array_column($hijas->toArray(), 'iddependenciahija'), SORT_NUMERIC);
            $dependenciasPadres = Dependencia::select($raw)->whereIn('iddependencia', $idPadres)->orderBy('codigorrhh')->get();

            if (count($dependenciasPadres) > 0) {
                $tmpHijas = $this->hijas($dependenciasPadres);
                $padre['hijas'] = $tmpHijas;
            }
            $array[$padre->label] = $padre;
        }

        return $array;
    }

    public function getDependenciasSinHorario()
    {
        $dependencias = Dependencia::all();

        $dependenciasSinHorario = collect();
        $dependenciasRed = Dependencia::find(191)->getIdsDescendencia();

        foreach ($dependencias as $d) {
            if ($d->horarios() !== null && $d->horarios()->count() < 1) {
                $dependenciasSinHorario->push([
                    'iddependencia' => $d->iddependencia,
                    'codigorrhh' => $d->codigorrhh,
                    'dependencia' => $d->dependencia,
                    'tipoDependencia' => $d->tipoDependencia->tipoDependencia ?? '',
                    'perteneceRed' => in_array($d->iddependencia, $dependenciasRed) ? 'SI' : 'NO',
                ]);
            }
        }

        return $dependenciasSinHorario;
    }

    public function getDependenciasConHorarioRepetido()
    {
        // Horarios Lunes a Viernes o Domingos
        $horarios = HorarioDependencia::with(['tipoDia', 'dependencia'])
            ->whereIn('idtipo_dia', [8, 9, 10])
            ->orderByDesc('idhorario_dependencia')
            ->get();

        $dependenciasSinHorario = collect();

        foreach ($horarios as $h) {
            $horariosAdicionales = HorarioDependencia::with(['tipoDia', 'dependencia'])
                ->where('idhorario_dependencia', '<>', $h->idhorario_dependencia)
                ->where('iddependencia', $h->iddependencia)
                ->get();

            if ($horariosAdicionales->count() > 0) {
                $dependenciasSinHorario->push([
                    'iddependencia' => $h->idhorario_dependencia,
                    'codigo' => $h->dependencia->codigorrhh,
                    'dependencia_padre' => $h->dependencia->getPadre(),
                    'dependencia' => $h->dependencia->dependencia,
                    'tipoDia' => $h->tipoDia->tipodia_corto,
                    'hora_desde' => $h->hora_desde,
                    'hora_hasta' => $h->hora_hasta,
                    'borrar' => 'NO'
                ]);
                foreach ($horariosAdicionales as $ha) {
                    $dependenciasSinHorario->push([
                        'iddependencia' => $ha->idhorario_dependencia,
                        'codigo' => $ha->dependencia->codigorrhh,
                        'dependencia_padre' => $ha->dependencia->getPadre(),
                        'dependencia' => $ha->dependencia->dependencia,
                        'tipoDia' => $ha->tipoDia->tipodia_corto,
                        'hora_desde' => $ha->hora_desde,
                        'hora_hasta' => $ha->hora_hasta,
                        'borrar' => 'SI'
                    ]);
                }
                $ha->delete();
            }
        }

        //Horarios Personalizados
        $horarios = HorarioDependencia::with(['tipoDia', 'dependencia'])
            ->whereIn('idtipo_dia', [1, 2, 3, 4, 5, 6, 7])
            ->orderByDesc('idhorario_dependencia')
            ->get();

        foreach ($horarios as $h) {
            $horariosAdicionales = HorarioDependencia::with(['tipoDia', 'dependencia'])
                ->where('idhorario_dependencia', '<>', $h->idhorario_dependencia)
                ->whereIn('idtipo_dia', [1, 2, 3, 4, 5, 6, 7])
                ->where('iddependencia', $h->iddependencia)
                ->get();

            if ($horariosAdicionales->count() > 0) {
                $hasRepetidos = false;
                foreach ($horariosAdicionales as $ha) {
                    if ($ha->idtipo_dia === $h->idtipo_dia) {
                        $dependenciasSinHorario->push([
                            'iddependencia' => $ha->idhorario_dependencia,
                            'codigo' => $ha->dependencia->codigorrhh,
                            'dependencia_padre' => $ha->dependencia->getPadre(),
                            'dependencia' => $ha->dependencia->dependencia,
                            'tipoDia' => $ha->tipoDia->tipodia_corto,
                            'hora_desde' => $ha->hora_desde,
                            'hora_hasta' => $ha->hora_hasta,
                            'borrar' => 'SI'
                        ]);
                        $hasRepetidos = true;
                    }
                    $ha->delete();
                }
                if ($hasRepetidos) {
                    $dependenciasSinHorario->push([
                        'iddependencia' => $h->idhorario_dependencia,
                        'codigo' => $h->dependencia->codigorrhh,
                        'dependencia_padre' => $h->dependencia->getPadre(),
                        'dependencia' => $h->dependencia->dependencia,
                        'tipoDia' => $h->tipoDia->tipodia_corto,
                        'hora_desde' => $h->hora_desde,
                        'hora_hasta' => $h->hora_hasta,
                        'borrar' => 'NO'
                    ]);
                }
            }
        }

        return $dependenciasSinHorario;
    }

    public function getCantidadAgentesTipoPuesto(?int $iddependencia, int $idpuesto)
    {
        return Dependencia::getCantidadAgentesTipoPuesto(iddependencia: $iddependencia, puesto: $idpuesto);
    }

    /**
     * Muestro listado de efectores sin (policlinicas y hospitales de la red).
     * @return JSON
     */
    public function selectEfectores(): JsonResponse
    {
        $dependenciasList = [];
        $dependencias = Dependencia::selectEfectores();
        foreach ($dependencias as $dependencia) {
            array_push(
                $dependenciasList,
                [
                    'label' => $dependencia->codigorrhh . ' - ' . $dependencia->dependencia,
                    'value' => $dependencia->iddependencia
                ]
            );
        }

        return $this->sendResponse($dependenciasList, 'Listado de Efectores');
    }
}
