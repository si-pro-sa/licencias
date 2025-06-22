<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Cargo;
use App\Models\Fecha;
use App\Models\Periodo;
use App\Models\CargoCambioEstado;
use App\Repositories\BaseRepository;
use App\Models\ValidacionHorasMaximas;

/**
 * Class CargoCambioEstadoRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:04 pm -03
 *
 * @method CargoCambioEstado findWithoutFail($id, $columns = ['*'])
 * @method CargoCambioEstado find($id, $columns = ['*'])
 * @method CargoCambioEstado first($columns = ['*'])
*/
class CargoCambioEstadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo',
        'idperiodo_desde',
        'fecha_desde',
        'idperiodo_hasta',
        'fecha_hasta',
        'idcargo_tipo_visado',
        'idtipo_formulario',
        'fecha_ingreso',
        'fecha_devolucion',
        'fecha_entrega_organismo',
        'observaciones_internas',
        'motivo'
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
        return CargoCambioEstado::class;
    }

    public function create($inputs)
    {
        $cargo = Cargo::find((int) $inputs['idcargo']);

        if ($inputs['tipoFormulario'] === 'continuidad') {
            $idcargo_tipo_formulario = 2;
        } else {
            $idcargo_tipo_formulario = $inputs['idcargo_tipo_formulario'];
        }

        if ($idcargo_tipo_formulario > 1 && $cargo->countFormulariosConVisado4() > 0) {
            return 'Ya posee un Formulario con Visado IV.';
        }

        $baja = $cargo->cargoCambioEstados()
            ->where('idcargo_tipo_formulario', '>', '2')
            ->orderBy('fecha_hasta', 'desc')
            ->first();

        if (isset($baja)) {
            return 'Ya posee una baja presentada.';
        }

        $cambioEstado = $cargo->cargoCambioEstados()
            ->where('idcargo_tipo_visado', '8')
            ->orderBy('fecha_hasta', 'desc')
            ->first();

        $fechaDesde = null;

        if ($idcargo_tipo_formulario > 2 && isset($cambioEstado)) {
            $fechaDesde = $cambioEstado->fecha_desde;
        }

        if ($idcargo_tipo_formulario === 2 && isset($cambioEstado, $cambioEstado->fecha_hasta)) {
            $fechaDesde = date('Y-m-d', strtotime("{$cambioEstado->fecha_hasta} +1 days"));
        }

        if ($idcargo_tipo_formulario === 2) {
            $continuidad = $cargo->cargoCambioEstados()
            ->where('idcargo_tipo_formulario', $idcargo_tipo_formulario)
            ->where('fecha_desde', $fechaDesde)
            ->orderBy('fecha_desde', 'desc')
            ->first();

            if (isset($continuidad)) {
                return 'Ya posee una contunuidad presentada.';
            }
        }

        if ($idcargo_tipo_formulario > 2 && isset($inputs['fecha_hasta'], $cambioEstado, $cambioEstado->fecha_hasta, $cambioEstado->fecha_desde)
        && !empty($fechaDesde) && !empty($cambioEstado->fecha_hasta)) {
            $rangoFechas = new Fecha($fechaDesde, $cambioEstado->fecha_hasta);
            if (!$rangoFechas->fechaPerteneceARango($inputs['fecha_hasta'])) {
                return 'La fecha de Vencimiento elegida no pertenece al período que posee el Cargo en su último formulario presentado.';
            }
        }

        if (!$fechaDesde) {
            return 'No se pudo encontrar la Fecha de Vencimiento actual del Formulario';
        }

        $cargoCambioEstado = new CargoCambioEstado();
        $cargoCambioEstado->idcargo = $inputs['idcargo'];
        $cargoCambioEstado->idcargo_tipo_visado = 1;
        $cargoCambioEstado->idcargo_tipo_formulario = $idcargo_tipo_formulario;
        $cargoCambioEstado->fecha_desde = $fechaDesde;
        $cargoCambioEstado->fecha_hasta = isset($inputs['fecha_hasta']) && !empty($inputs['fecha_hasta']) ? $inputs['fecha_hasta'] : null;
        $cargoCambioEstado->idperiodo_desde = Periodo::getPeriodoConFecha($fechaDesde)->idperiodo;
        $cargoCambioEstado->idperiodo_hasta = isset($inputs['fecha_hasta']) && !empty($inputs['fecha_hasta']) ? Periodo::getPeriodoConFecha($inputs['fecha_hasta'])->idperiodo : null;
        $cargoCambioEstado->fecha_retorno = null;
        $cargoCambioEstado->fecha_entrega_organismo = null;
        $cargoCambioEstado->observaciones_internas = null;
        $cargoCambioEstado->motivo = $inputs['motivo'];

        if (!$cargoCambioEstado->save()) {
            return false;
        }

        return true;
    }

    public function modificarFecha($inputs)
    {
        $fechasEditables = ['fecha_desde', 'fecha_hasta', 'fecha_designacion_transitorio', 'fecha_recibido', 'fecha_envio', 'fecha_retorno', 'fecha_entrega_organismo'];

        if (!in_array($inputs['nombrePropiedad'], $fechasEditables)) {
            return 'La fecha ingresada no puede editarse.';
        }

        $cargoCambioEstado = CargoCambioEstado::find((int) $inputs['idcargo_cambio_estado']);

        if (isset($cargoCambioEstado)) {
            if (isset($inputs['nombrePropiedad'], $inputs['fecha'])) {
                $fechaActual = isset($cargoCambioEstado[$inputs['nombrePropiedad']]) && !empty($cargoCambioEstado[$inputs['nombrePropiedad']]) ? Carbon::parse($cargoCambioEstado[$inputs['nombrePropiedad']])->format('Y-m-d') : null;
                if (!isset($cargoCambioEstado->idcargo_tipo_firma) && $inputs['nombrePropiedad'] === 'fecha_envio') {
                    return 'Debe ingresar una Firma antes de cargar la Fecha de Envío';
                }

                if ($inputs['fecha'] !== $fechaActual) {
                    $cargoCambioEstado[$inputs['nombrePropiedad']] = $inputs['fecha'];
                    if ($inputs['nombrePropiedad'] === 'fecha_desde') {
                        $cargoCambioEstado->idperiodo_desde = Periodo::getPeriodoConFecha($inputs['fecha'])->idperiodo;
                    }
                    if ($inputs['nombrePropiedad'] === 'fecha_hasta') {
                        $cargoCambioEstado->idperiodo_hasta = Periodo::getPeriodoConFecha($inputs['fecha'])->idperiodo;
                    }
                } else {
                    return true;
                }

                $error = $cargoCambioEstado->hasError();

                if (!$error && $cargoCambioEstado->save()) {
                    return true;
                } elseif ($error) {
                    return $error;
                }
            }
        }
        return false;
    }

    public function modificarObservaciones($inputs)
    {
        $cargoCambioEstado = CargoCambioEstado::find((int) $inputs['idcargo_cambio_estado']);

        if (isset($cargoCambioEstado)) {
            $cargoCambioEstado->observaciones_internas = $inputs['observaciones_internas'];
            if ($cargoCambioEstado->save()) {
                return true;
            }
        }
        return false;
    }

    public function visar($inputs)
    {
        $cargoCambioEstado = CargoCambioEstado::find((int) $inputs['idcargo_cambio_estado']);

        if (isset($cargoCambioEstado)) {
            $estadosVisado = CargoCambioEstado::getEstadosVisado();
            $desvisar = (bool) $inputs['desvisar'];
            $estado = $estadosVisado
                                    ->where('estado', $cargoCambioEstado->idcargo_tipo_visado)
                                    ->where('aprobado', (bool) $inputs['aprobado'])
                                    ->where('desvisar', $desvisar)
                                    ->first();
            $nuevoEstado = $estado['nuevo_estado'] ?? 0;

            if ($nuevoEstado !== 0 && $nuevoEstado > 0 && $nuevoEstado < 10) {
                $agentePropuesto = $cargoCambioEstado->cargo->agentePropuesto();

                if (isset($agentePropuesto)) {
                    $puestoActivo = $agentePropuesto->puestoActivo();
                }

                //Chequeo plantas permitidas en formularios nuevos y continuidades
                if (isset($puestoActivo) &&
                        $desvisar === false &&
                        in_array($cargoCambioEstado->idtipo_formulario, [1, 2]) &&
                        in_array($puestoActivo->idtipo_planta, CargoCambioEstado::$plantasNoPermitidas)) {
                    return $this->sendError('El Agente Propuesto es de Planta Perminante Interino, Permanente Titular, Residentes, Residentes Nacionales, Planta Transitoria o Reemplazante no permanente – LD');
                }

                //Chequeo niveles permitidos en formularios nuevos y continuidades
                if (isset($puestoActivo) &&
                        $desvisar === false &&
                        in_array($cargoCambioEstado->idtipo_formulario, [1, 2]) &&
                        $puestoActivo->tipoNivel->idtipo_estado != 1) {
                    return $this->sendError('El Agente Propuesto debe tener un Nivel habilitado.');
                }

                if ($cargoCambioEstado->countBajaVigente() > 0) {
                    return 'El formulario ya posee una Baja asociada Visada';
                }

                if ($cargoCambioEstado->countContinuidadVigentePosterior() > 0 && $cargoCambioEstado->idcargo_tipo_formulario <= 2) {
                    return 'El formulario ya posee una Continuidad posterior Visada';
                }

                if (!($nuevoEstado === 8 && $desvisar === true)
                    && $cargoCambioEstado->cargo->countFormulariosConVisado4() > 0) {
                    return 'No se pudo visar porque ya posee un Formulario relacionado con Visado IV';
                }

                //PENDIENTE
                if ($nuevoEstado === 1 && $desvisar === false) {
                    $cargoCambioEstado->fecha_recibido = null;
                }

                //RECIBIDO
                if ($nuevoEstado === 2 && !isset($cargoCambioEstado->fecha_recibido) && $desvisar === false) {
                    $cargoCambioEstado->fecha_recibido = date('Y-m-d');
                }

                // VISADO III Para formulario de Alta
                if ($nuevoEstado === 8 && $cargoCambioEstado->idcargo_tipo_formulario === 1 && $desvisar === false) {
                    if (isset($cargoCambioEstado->fecha_desde, $cargoCambioEstado->fecha_envio, $cargoCambioEstado->fecha_recibido, $cargoCambioEstado->idcargo_tipo_firma)) {
                        $puestoCreado = $cargoCambioEstado->cargo->createAgenteYPuesto();
                        if (is_string($puestoCreado)) {
                            return $puestoCreado;
                        }
                        if (!$puestoCreado) {
                            return 'No se pudo crear el Agente y el Puesto';
                        }
                    } else {
                        return 'Verifique que haya ingresado: Fecha Desde, Fecha Envío, Fecha Recibido y Firma';
                    }
                }

                // VISADO IV Para formulario de Alta
                if ($nuevoEstado === 9
                && ($cargoCambioEstado->idcargo_tipo_formulario === 1 || $cargoCambioEstado->idcargo_tipo_formulario === 2)
                && $desvisar === false) {
                    if (!isset($cargoCambioEstado->fecha_designacion_transitorio)) {
                        return 'Debe ingresar una Fecha de Designación Transitorio';
                    }
                    $puestoTransitorioCreado = $cargoCambioEstado->createPuestoTransitorio();
                    if (is_string($puestoTransitorioCreado)) {
                        return $puestoTransitorioCreado;
                    }
                    if (!$puestoTransitorioCreado) {
                        return 'No se pudo crear el Puesto Transitorio';
                    }
                }

                $cargoCambioEstado->idcargo_tipo_visado = $nuevoEstado;
                $error = $cargoCambioEstado->hasError();

                if (!$error && $cargoCambioEstado->update()) {
                    return true;
                } elseif ($error) {
                    return $error;
                }
            }
        }
        return false;
    }

    public function modificarFirma($inputs)
    {
        $cargoCambioEstado = CargoCambioEstado::find((int) $inputs['idcargo_cambio_estado']);

        if (isset($cargoCambioEstado)) {
            $cargoCambioEstado->idcargo_tipo_firma = $inputs['idcargo_tipo_firma'];
            $error = $cargoCambioEstado->hasError();

            if (!$error && $cargoCambioEstado->save()) {
                return true;
            } elseif ($error) {
                return $error;
            }
        }
        return false;
    }

    public function modificarReferido($inputs)
    {
        $cargo = Cargo::whereHas('cargoCambioEstados', function ($query) use ($inputs) {
            $query->where('idcargo_cambio_estado', $inputs['idcargo_cambio_estado']);
        })->first();

        if (isset($cargo)) {
            $cargo->idtipo_referido = $inputs['idtipo_referido'];
            $error = false; //$cargo->hasError();

            if (!$error && $cargo->save()) {
                return true;
            } elseif ($error) {
                return $error;
            }
        }
        return false;
    }

    public function modificarDocumentacion($inputs)
    {
        $cargoCambioEstado = CargoCambioEstado::find((int) $inputs['idcargo_cambio_estado']);
        if (isset($cargoCambioEstado, $inputs['documentacion'])) {
            $cargo = Cargo::find($cargoCambioEstado->idcargo);
            if (isset($cargo)) {
                $cargo->foto_carnet = $inputs['documentacion']['fotoCarnet'];
                $cargo->diagrama_servicio = $inputs['documentacion']['diagramaServicio'];
                $cargo->resolucion_ministerial = $inputs['documentacion']['resolucionMinisterial'];
                $cargo->formulario_baja_cobertura = $inputs['documentacion']['formularioBajaCobertura'];
                $cargo->titulo_academico = $inputs['documentacion']['tituloAcademico'];
                $cargo->copia_dni = $inputs['documentacion']['copiaDni'];
                $cargo->copia_cuil = $inputs['documentacion']['copiaCuil'];
                $cargo->resumen_evaluacion = $inputs['documentacion']['resumenEvaluacion'];
                $cargo->curso_induccion = $inputs['documentacion']['cursoInduccion'];
                $cargo->titulo_especialidad = $inputs['documentacion']['tituloEspecialidad'];
                $cargo->declaracion_jurada = $inputs['documentacion']['declaracionJurada'];
                $cargo->matricula_profesional = $inputs['documentacion']['matriculaProfesional'];
                $cargo->certificado_reincidencia = $inputs['documentacion']['certificadoReincidencia'];

                if ($cargo->save()) {
                    return true;
                }
            }
        }
        return false;
    }
}
