<?php
namespace App\Repositories;

use App\Models\Cargo;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\CargoHorario;
use App\Models\GrupoFuncion;
use App\Models\CargoTipoCese;
use App\Models\CargoReemplazado;
use App\Models\CargoCambioEstado;
use App\Models\Dependencia;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use App\Models\ValidacionHorasMaximas;

/**
 * Class CargoRepository
 * @package App\Repositories
 * @version October 31, 2019, 3:55 pm -03
 *
 * @method Cargo findWithoutFail($id, $columns = ['*'])
 * @method Cargo find($id, $columns = ['*'])
 * @method Cargo first($columns = ['*'])
*/
class CargoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idrecomendacion_candidato',
        'idefector',
        'idservicio',
        'idtipo_funcion',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idtitulo',
        'idtipo_especialidad',
        'produccion_esperada',
        'razones_brecha',
        'idtipo_referido',
        'idtipo_cargo',
        'foto_carnet',
        'diagrama_servicio',
        'resolucion_ministerial',
        'formulario_baja_cobertura',
        'formulario_resumen_servicio',
        'formulario_devolucion',
        'titulo_academico',
        'copia_dni',
        'copia_cuil',
        'resumen_evaluacion'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cargo::class;
    }

    public function getCargosAgente(int $idagente) : array
    {
        return $this->model
        ->with(['tipoCargo', 'tipoCampania', 'efector', 'servicio'])
        ->where('agente_propuesto_id', $idagente)
        ->where('agente_propuesto_type', 'App\Models\Agente')
        ->whereHas('cargoCambioEstados', function ($query) {
            $query->whereIn('idcargo_tipo_visado', [8, 9]);
        })
        ->orderByDesc('idcargo')
        ->limit(25)
        ->get()
        ->map->format()
        ->all();
    }

    public function getCargosReemplazadoAgente(int $idagente) : array
    {
        return $this->model
                    ->with(['tipoCargo', 'tipoCampania', 'efector', 'servicio'])
                    ->whereHas('cargoReemplazado', function ($que) use ($idagente) {
                        $que->whereHas('puesto', function ($q) use ($idagente) {
                            $q->where('idagente', $idagente);
                        });
                    })
                    ->whereHas('cargoCambioEstados', function ($query) {
                        $query->whereIn('idcargo_tipo_visado', [8, 9]);
                    })
                    ->orderByDesc('idcargo')
                    ->limit(25)
                    ->get()
                    ->map->format()
                    ->all();
    }

    public function create($inputs)
    {
        $candidato = Agente::documento($inputs['dniPropuesto'])->first();
        if (!$candidato) {
            $candidato = Candidato::documento($inputs['dniPropuesto'])->first();
            $idtipo_referido = $candidato->recomendacion[0]->idtipo_referido_interno;
        } else {
            $puesto = $candidato->puestoActivo();
            if (!$puesto) {
                return 'No posee un Puesto Activo';
            }

            $idtipo_referido = null;

            if ($candidato->getPrimerTitulo() === null) {
                return 'No posee Título';
            }
        }

        //Cantidad de cargos RECIBIDO, VISADO I APROBADO, VISADO II APROBADO, VISADO III APROBADO
        $usuario = auth()->user();
        $cargoActivo = $candidato->tieneCargoVisadoComoReemplazado();
        if ((!isset($usuario) || !$usuario->isRRHH()) && $cargoActivo) {
            return 'El Agente Reemplazado ya posee un Cargo Visado';
        }

        if (isset($usuario) && !$usuario->isRRHH() && $candidato->tieneCargoActivo()) {
            return 'El Agente Propuesto ya posee un Cargo Activo';
        }

        DB::beginTransaction();
        $cargo = new Cargo();
        $cargo->idtipo_cese = $inputs['idtipo_cese'];
        $cargo->agente_propuesto_id = $candidato->idcandidato ?? $candidato->idagente;
        $cargo->agente_propuesto_type = get_class($candidato);
        $cargo->idefector = $inputs['efectores'][0]['idefector'];
        $cargo->idservicio = $inputs['efectores'][0]['idservicio'];
        $cargo->idtipo_funcion = $inputs['idtipo_funcion'];
        $cargo->idtipo_nivel = $inputs['idtipo_nivel'];
        $cargo->idtitulo = $inputs['idtitulo'];
        $cargo->idtipo_referido = $idtipo_referido;
        $cargo->idtipo_campania = $inputs['idtipo_campania'];
        $cargo->idtipo_agrupamiento = $inputs['idtipo_agrupamiento'];
        $cargo->idtipo_especialidad = $inputs['idtipo_especialidad'];
        $cargo->idtipo_cargo = CargoTipoCese::firstWhere('idtipo_cese', $inputs['idtipo_cese'])->idtipo_cargo;
        $cargo->foto_carnet = $inputs['documentacion']['fotoCarnet'];
        $cargo->diagrama_servicio = $inputs['documentacion']['diagramaServicio'];
        $cargo->formulario_baja_cobertura = $inputs['documentacion']['formularioBajaCobertura'];
        $cargo->resolucion_ministerial = $inputs['documentacion']['resolucionMinisterial'];
        $cargo->titulo_academico = $inputs['documentacion']['tituloAcademico'];
        $cargo->copia_dni = $inputs['documentacion']['copiaDni'];
        $cargo->copia_cuil = $inputs['documentacion']['copiaCuil'];
        $cargo->resumen_evaluacion = $inputs['documentacion']['resumenEvaluacion'];
        $cargo->curso_induccion = $inputs['documentacion']['cursoInduccion'];
        $cargo->titulo_especialidad = $inputs['documentacion']['tituloEspecialidad'];
        $cargo->declaracion_jurada = $inputs['documentacion']['declaracionJurada'];
        $cargo->matricula_profesional = $inputs['documentacion']['matriculaProfesional'];
        $cargo->certificado_reincidencia = $inputs['documentacion']['certificadoReincidencia'];

        $cargo->produccion_esperada = $inputs['produccion_esperada'];
        $cargo->razones_brecha = $inputs['razones_brecha'];

        $cargo->prioritario = GrupoFuncion::isPrioritario($inputs['idtipo_funcion']);

        if (!$cargo->save()) {
            DB::rollBack();
            return 'Ocurrió un error al crear el Cargo. Consulte a un administrador';
        }

        $cargoCambioEstado = new CargoCambioEstado();
        $cargoCambioEstado->idcargo = $cargo->idcargo;
        $cargoCambioEstado->idcargo_tipo_visado = 1;
        $cargoCambioEstado->idcargo_tipo_formulario = 1;
        $cargoCambioEstado->fecha_desde = null;
        $cargoCambioEstado->fecha_hasta = null;
        $cargoCambioEstado->idperiodo_desde = null;
        $cargoCambioEstado->idperiodo_hasta = null;
        $cargoCambioEstado->fecha_retorno = null;
        $cargoCambioEstado->fecha_entrega_organismo = null;
        $cargoCambioEstado->observaciones_internas = null;
        $cargoCambioEstado->motivo = null;

        if (!$cargoCambioEstado->save()) {
            DB::rollBack();
            return 'Ocurrió un error al crear el Alta del Cargo. Consulte a un administrador';
        }

        if ($inputs['agente_reemplazado']) {
            $reemplazado = Agente::buscarUltimoPuestoCerrado($inputs['dniReemplazado']);
            $reemplazadoPuestoActivo = Agente::buscarPuestoAbierto($inputs['dniReemplazado']);

            if (!GrupoFuncion::isMismoGrupo($inputs['idtipo_funcion'], $reemplazado->idtipo_funcion)) {
                DB::rollBack();
                return 'La Función del Agente Propuesto no corresponde a la Función del Agente Reemplazado.';
            }

            //Cambio de Función solo permitida en el mismo efector o su padre
            if ($inputs['idtipo_cese'] === 19) {
                $efector = Dependencia::find($inputs['efectores'][0]['idefector']);
                $servicio = $reemplazado->dependencia;
                $padresServicio = $reemplazado->dependencia->getIdsPadresCargo();
                $padresServicioString = '';

                foreach ($padresServicio as $padre) {
                    $padre = Dependencia::find($padre);
                    $padresServicioString .= "{$padre->dependencia} ";
                }

                if (!(in_array($efector->iddependencia, $padresServicio))) {
                    DB::rollBack();
                    return "El Cambio de Función solo está permitido en el mismo efector. 
                    Efector Cargo: {$efector->dependencia}
                    Servicio Puesto: {$servicio->dependencia}
                    Efectores Puesto: {$padresServicioString}";
                }
            }

            // Reubicacion / reasignacion / comision
            if (in_array($inputs['idtipo_cese'], [18, 20, 27])) {
                //Otros cargos similar a cargo vacante y que no sean en el mismo efector
                if ($reemplazadoPuestoActivo?->dependencia?->getIdPadre() === $reemplazado?->dependencia?->getIdPadre()) {
                    DB::rollBack();
                    return 'La razón de cierre de su último Puesto Cerrado no debe pertenecer al mismo Efector';
                }
            }

            $cargoReemplazado = new CargoReemplazado();
            $cargoReemplazado->idcargo = $cargo->idcargo;
            $cargoReemplazado->idpuesto = $reemplazado->idpuesto;
            $cargoReemplazado->idtipo_funcion = $reemplazado->idtipo_funcion;
            $cargoReemplazado->idtipo_nivel = $reemplazado->idtipo_nivel;
            $cargoReemplazado->idtipo_agrupamiento = $reemplazado->idtipo_agrupamiento;

            if (!$cargoReemplazado->save()) {
                DB::rollBack();
                return 'Ocurrió un error al crear al asocial el Reemplazado. Consulte a un administrador';
            }
        }

        foreach ($inputs['efectores'] as $efector) {
            if ($efector['tipoHorario'] === 'p' || $efector['tipoHorario'] === 'pg') {
                foreach ($efector['dias'] as $key => $dia) {
                    if ($dia['isChecked'] === true && isset($dia['hora_desde'], $dia['hora_hasta'])) {
                        $cargoHorario = new CargoHorario();
                        $cargoHorario->idtipo_dia = $efector['tipoHorario'] === 'p' ? ($key + 1) : (10 + $key + 1);
                        $cargoHorario->idcargo = $cargo->idcargo;
                        $cargoHorario->idefector = $efector['idefector'];
                        $cargoHorario->idservicio = $efector['idservicio'];
                        $cargoHorario->hora_desde = $dia['hora_desde'];
                        $cargoHorario->hora_hasta = $dia['hora_hasta'];

                        if (!$cargoHorario->save()) {
                            DB::rollBack();
                            return 'Ocurrió un error al crear los horarios. Consulte a un administrador';
                        }
                    }
                }
            } else {
                if ($efector['tipoHorario'] === 'rot' && (!isset($efector['cantidad_mensual']) || empty($efector['cantidad_mensual']) || $efector['cantidad_mensual'] === 0)) {
                    DB::rollBack();
                    return 'El horario rotativo debe tener una Cantidad Mensual';
                }

                $cargoHorario = new CargoHorario();
                $cargoHorario->idcargo = $cargo->idcargo;
                $cargoHorario->idtipo_dia = $efector['tipoHorario'] === 'lv' ? 8 : 9;
                $cargoHorario->idefector = $efector['idefector'];
                $cargoHorario->idservicio = $efector['idservicio'];
                $cargoHorario->hora_desde = $efector['hora_desde'];
                $cargoHorario->hora_hasta = $efector['hora_hasta'];
                $cargoHorario->cantidad_mensual = $efector['tipoHorario'] === 'lv' ? 0 : $efector['cantidad_mensual'];

                if (!$cargoHorario->save()) {
                    DB::rollBack();
                    return 'Ocurrió un error al crear el horario. Consulte a un administrador';
                }
            }
        }

        DB::commit();
        return true;
    }

    public function estaVisado(int $idcargo_cambio_estado)
    {
        $cargo = CargoCambioEstado::firstWhere('idcambio_estado', $idcargo_cambio_estado);
        if (isset($cargo)) {
            return $cargo->idcargo_tipo_visado > 1;
        }
        return true;
    }

    public function modificar(int $id, array $inputs)
    {
        $cargo = $this->find($id);
        $candidato = Agente::documento($inputs['dniPropuesto'])->first();
        if (!$candidato) {
            $candidato = Candidato::documento($inputs['dniPropuesto'])->first();
            $idtipo_funcion = $candidato->recomendacion[0]->idtipo_funcion;
            $idtipo_nivel = $candidato->recomendacion[0]->idtipo_nivel;
            $idtitulo = $candidato->recomendacion[0]->idtitulo;
            $idtipo_referido = $candidato->recomendacion[0]->idtipo_referido_interno;
        } else {
            $puesto = $candidato->puestoActivo();
            if (!$puesto) {
                return 'No posee un Puesto Activo';
            }

            $idtipo_funcion = $puesto->idtipo_funcion;
            $idtipo_nivel = $puesto->idtipo_nivel;

            $idtitulo = $candidato->getPrimerTitulo();

            $idtipo_referido = null;

            if (!$idtitulo) {
                return 'No posee Título';
            }
        }

        DB::beginTransaction();
        $cargo = new Cargo();
        $cargo->idtipo_cese = $inputs['idtipo_cese'];
        $cargo->agente_propuesto_id = $candidato->idcandidato ?? $candidato->idagente;
        $cargo->agente_propuesto_type = get_class($candidato);
        $cargo->idefector = $inputs['efectores'][0]['idefector'];
        $cargo->idservicio = $inputs['efectores'][0]['idservicio'];
        $cargo->idtipo_funcion = $idtipo_funcion;
        $cargo->idtipo_nivel = $idtipo_nivel;
        $cargo->idtitulo = $idtitulo;
        $cargo->idtipo_referido = $idtipo_referido;
        $cargo->idtipo_campania = $inputs['idtipo_campania'];
        $cargo->idtipo_agrupamiento = $inputs['idtipo_agrupamiento'];
        $cargo->idtipo_especialidad = $inputs['idtipo_especialidad'];
        $cargo->idtipo_cargo = CargoTipoCese::firstWhere('idtipo_cese', $inputs['idtipo_cese'])->idtipo_cargo;
        $cargo->foto_carnet = $inputs['documentacion']['fotoCarnet'];
        $cargo->diagrama_servicio = $inputs['documentacion']['diagramaServicio'];
        $cargo->formulario_baja_cobertura = $inputs['documentacion']['formularioBajaCobertura'];
        $cargo->resolucion_ministerial = $inputs['documentacion']['resolucionMinisterial'];
        $cargo->titulo_academico = $inputs['documentacion']['tituloAcademico'];
        $cargo->copia_dni = $inputs['documentacion']['copiaDni'];
        $cargo->copia_cuil = $inputs['documentacion']['copiaCuil'];
        $cargo->resumen_evaluacion = $inputs['documentacion']['resumenEvaluacion'];
        $cargo->curso_induccion = $inputs['documentacion']['cursoInduccion'];
        $cargo->titulo_especialidad = $inputs['documentacion']['tituloEspecialidad'];
        $cargo->declaracion_jurada = $inputs['documentacion']['declaracionJurada'];
        $cargo->matricula_profesional = $inputs['documentacion']['matriculaProfesional'];
        $cargo->certificado_reincidencia = $inputs['documentacion']['certificadoReincidencia'];

        $cargo->produccion_esperada = $inputs['produccion_esperada'];
        $cargo->razones_brecha = $inputs['razones_brecha'];

        $cargo->prioritario = GrupoFuncion::isPrioritario($idtipo_funcion);

        if (!$cargo->save()) {
            DB::rollBack();
            return 'Ocurrió un error al crear el Cargo. Consulte a un administrador';
        }

        $cargoCambioEstado = new CargoCambioEstado();
        $cargoCambioEstado->idcargo = $cargo->idcargo;
        $cargoCambioEstado->idcargo_tipo_visado = 1;
        $cargoCambioEstado->idcargo_tipo_formulario = 1;
        $cargoCambioEstado->fecha_desde = null;
        $cargoCambioEstado->fecha_hasta = null;
        $cargoCambioEstado->idperiodo_desde = null;
        $cargoCambioEstado->idperiodo_hasta = null;
        $cargoCambioEstado->fecha_retorno = null;
        $cargoCambioEstado->fecha_entrega_organismo = null;
        $cargoCambioEstado->observaciones_internas = null;
        $cargoCambioEstado->motivo = null;

        if (!$cargoCambioEstado->save()) {
            DB::rollBack();
            return 'Ocurrió un error al crear el Alta del Cargo. Consulte a un administrador';
        }

        if ($inputs['agente_reemplazado']) {
            $reemplazado = Agente::buscarUltimoPuestoCerrado($inputs['dniReemplazado']);

            if (!GrupoFuncion::isMismoGrupo($idtipo_funcion, $reemplazado->idtipo_funcion)) {
                DB::rollBack();
                return 'La Función del Agente Propuesto no corresponde a la Función del Agente Reemplazado.';
            }

            $cargoReemplazado = new CargoReemplazado();
            $cargoReemplazado->idcargo = $cargo->idcargo;
            $cargoReemplazado->idpuesto = $reemplazado->idpuesto;
            $cargoReemplazado->idtipo_funcion = $reemplazado->idtipo_funcion;
            $cargoReemplazado->idtipo_nivel = $reemplazado->idtipo_nivel;
            $cargoReemplazado->idtipo_agrupamiento = $reemplazado->idtipo_agrupamiento;

            if (!$cargoReemplazado->save()) {
                DB::rollBack();
                return 'Ocurrió un error al crear al asocial el Reemplazado. Consulte a un administrador';
            }
        }

        foreach ($inputs['efectores'] as $efector) {
            if ($efector['tipoHorario'] === 'p' || $efector['tipoHorario'] === 'pg') {
                foreach ($efector['dias'] as $key => $dia) {
                    if ($dia['isChecked'] === true && isset($dia['hora_desde'], $dia['hora_hasta'])) {
                        $cargoHorario = new CargoHorario();
                        $cargoHorario->idtipo_dia = $efector['tipoHorario'] === 'p' ? ($key + 1) : (10 + $key + 1);
                        $cargoHorario->idcargo = $cargo->idcargo;
                        $cargoHorario->idefector = $efector['idefector'];
                        $cargoHorario->idservicio = $efector['idservicio'];
                        $cargoHorario->hora_desde = $dia['hora_desde'];
                        $cargoHorario->hora_hasta = $dia['hora_hasta'];

                        if (!$cargoHorario->save()) {
                            DB::rollBack();
                            return 'Ocurrió un error al crear los horarios. Consulte a un administrador';
                        }
                    }
                }
            } else {
                $cargoHorario = new CargoHorario();
                $cargoHorario->idcargo = $cargo->idcargo;
                $cargoHorario->idtipo_dia = $efector['tipoHorario'] === 'lv' ? 8 : 9;
                $cargoHorario->idefector = $efector['idefector'];
                $cargoHorario->idservicio = $efector['idservicio'];
                $cargoHorario->hora_desde = $efector['hora_desde'];
                $cargoHorario->hora_hasta = $efector['hora_hasta'];
                if (!$cargoHorario->save()) {
                    DB::rollBack();
                    return 'Ocurrió un error al crear el horario. Consulte a un administrador';
                }
            }
        }

        DB::commit();
        return true;
    }

    public function tieneCargosVisadosComoReemplazado(int $documento): bool
    {
        $cantidadCargosVisado4 = CargoCambioEstado::whereHas('cargo', function ($query) use ($documento) {
            $query->whereHas('cargoReemplazado', function ($quer) use ($documento) {
                $quer->whereHas('puesto', function ($que) use ($documento) {
                    $que->whereHas('agente', function ($q) use ($documento) {
                        $q->where('documento', $documento);
                    });
                });
            });
        })
        ->where('idcargo_tipo_formulario', '<', 3)
        ->where('idcargo_tipo_visado', 9)
        ->orderByDesc('fecha_desde')
        ->count();

        if ($cantidadCargosVisado4 > 0) {
            return true;
        }

        $cargosVisado3 = Cargo::whereHas('cargoReemplazado', function ($quer) use ($documento) {
            $quer->whereHas('puesto', function ($que) use ($documento) {
                $que->whereHas('agente', function ($q) use ($documento) {
                    $q->where('documento', $documento);
                });
            });
        })
        ->whereHas('cargoCambioEstados', function ($query) {
            $query->where('idcargo_tipo_visado', 8)
                    ->where(function ($que) {
                        $que->where('fecha_hasta', '>', date('Y-m-d'))
                      ->orWhere('fecha_hasta', '=', null);
                    })
            ->orderByDesc('fecha_desde');
        })
        ->get();

        foreach ($cargosVisado3 as $cargo) {
            if (strtotime($cargo->fecha_vencimiento_real) > strtotime('today')) {
                return true;
            }
        }

        return false;
    }
}
