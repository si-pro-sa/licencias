<?php
namespace App\Repositories;

use App\Models\LdAlta;
use App\Models\LdCambioEstado;
use App\Models\LdVisadoCambioEstado;
use App\Models\Periodo;
use App\Repositories\BaseRepository;

/**
 * Class LdAltaRepository
 * @package App\Repositories
 * @version March 8, 2020, 7:56 pm -03
*/

class LdAltaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha_creado',
        'fdesde',
        'fhasta',
        'fuera_termino',
        'valor',
        'info_adicional',
        'bloqueado',
        'idtipo_formulario',
        'idld_estado',
        'idld_tipo_alta',
        'idpuesto',
        'iddependencia_origen',
        'iddependencia_destino',
        'idefector',
        'idld_codigo',
        'idperiodo',
        'idtipo_agrupamiento',
        'usuario',
        'operacion',
        'foperacion',
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
        return LdAlta::class;
    }

    public function getLibresAgente(int $idagente) : array
    {
        return $this->model
        ->with([
            'ldCambioEstados' => function ($q) {
                $q->orderByDesc('fhasta');
            },
            'ldTipoAlta',
            'efector',
            'dependenciaOrigen',
            'dependenciaDestino',
            'ldCodigo'
        ])
        ->whereHas('puesto', function ($query) use ($idagente) {
            $query->where('idagente', $idagente);
        })
        ->where('idld_estado', 3)
        ->orderByDesc('fdesde')
        ->limit(25)
        ->get()
        ->map->format()
        ->all();
    }

    public function getContBajasLibresAgente(int $idagente) : array
    {
        return LdCambioEstado::with('ldTipoCambioEstado')
            ->whereHas('ldAlta', function ($q) use ($idagente) {
                $q->with([
                    'ldCambioEstados' => function ($q) {
                        $q->orderByDesc('fhasta');
                    },
                    'ldTipoAlta',
                    'efector',
                    'dependenciaOrigen',
                    'dependenciaDestino',
                    'ldCodigo'
                ])
                ->whereHas('puesto', function ($query) use ($idagente) {
                    $query->where('idagente', $idagente);
                })
                ->where('idld_estado', 3);
            })
            ->where('idld_estado', 3)
            ->orderByDesc('fhasta')
            ->limit(25)
            ->get()
            ->map->format()
            ->all();
    }

    public function getVigentes(): array
    {
        $orden_actual = date('Ym', time());
        $periodoActual = Periodo::firstWhere(['orden' => $orden_actual]);
        return $this->model
        ->with(['ldCambioEstados', 'efector', 'ldCodigo', 'puesto', 'dependenciaDestino', 'dependenciaOrigen', 'puesto.agente', 'puesto.tipoFuncion', 'puesto.tipoAgrupamiento', 'puesto.tipoNivel'])
        ->where('idld_estado', 3)
        ->where('fdesde', '<=', $periodoActual->fhasta)
        ->get()
        ->map->formatVigentes($periodoActual)
        ->all();
    }

    public function getContinuidadesConBajaAnterior()
    {
        $bajas = LdCambioEstado::where('idld_estado', 3)
            ->where('idld_tipo_cambio_estado', '>', 1)
            ->get();

        $continuidadesConBajaAnterior = [];
        foreach ($bajas as $baja) {
            $continuidades = LdCambioEstado::with(['ldAlta', 'ldAlta.puesto.agente', 'ldAlta.ldCodigo'])
                ->where('idld_estado', 3)
                ->where('idld_tipo_cambio_estado', 1)
                ->where('idld_alta', $baja->idld_alta)
                ->get();

            foreach ($continuidades as $continuidad) {
                $fdesdeCont = $continuidad->getFechaInicioContinuidad();
                $fhastaCont = strtotime($continuidad->fhasta);
                $fhastaBaja = strtotime($baja->fhasta);
                $agente = $continuidad->ldAlta->puesto->agente;
                if ($fhastaBaja < $fdesdeCont) {
                    $visadoCreadoCont = LdVisadoCambioEstado::where('idld_cambio_estado', $continuidad->idld_cambio_estado)->where('operacion', 'C')->first();
                    $visado1Cont = LdVisadoCambioEstado::where('idld_cambio_estado', $continuidad->idld_cambio_estado)->where('operacion', 'A')->first();
                    $visado2Cont = LdVisadoCambioEstado::where('idld_cambio_estado', $continuidad->idld_cambio_estado)->where('operacion', 'B')->first();
                    $desvisadoCont = LdVisadoCambioEstado::where('idld_cambio_estado', $continuidad->idld_cambio_estado)->where('operacion', 'D')->first();

                    $visadoCreadoBaja = LdVisadoCambioEstado::where('idld_cambio_estado', $baja->idld_cambio_estado)->where('operacion', 'C')->first();
                    $visado1Baja = LdVisadoCambioEstado::where('idld_cambio_estado', $baja->idld_cambio_estado)->where('operacion', 'A')->first();
                    $visado2Baja = LdVisadoCambioEstado::where('idld_cambio_estado', $baja->idld_cambio_estado)->where('operacion', 'B')->first();
                    $desvisadoBaja = LdVisadoCambioEstado::where('idld_cambio_estado', $baja->idld_cambio_estado)->where('operacion', 'D')->first();

                    $datosArray = [
                        'idalta' => $continuidad->idld_alta,
                        'idcontinuidad' => $continuidad->idld_cambio_estado,
                        'idbaja' => $baja->idld_cambio_estado,
                        'dni' => $agente->documento,
                        'apellido' => $agente->apellido,
                        'nombre' => $agente->nombre,
                        'código' => $continuidad->ldAlta->ldCodigo->ldcodigo,
                        'fecha_desde_alta' => date('Y-m-d', strtotime($continuidad->ldAlta->fdesde)),
                        'fecha_hasta_alta' => date('Y-m-d', strtotime($continuidad->ldAlta->fhasta)),
                        'fecha_desde_continuidad' => date('Y-m-d', $fdesdeCont),
                        'fecha_hasta_continuidad' => date('Y-m-d', $fhastaCont),
                        'fecha_baja' => date('Y-m-d', $fhastaBaja),
                        '----' => '',
                        'fecha_creado_continuidad' => isset($visadoCreadoCont->foperacion) ? date('Y-m-d (H:i)', strtotime($visadoCreadoCont->foperacion)) : '',
                        'usuario_creado_continuidad' => $visadoCreadoCont->usuario,
                        'fecha_visado1_continuidad' => isset($visado1Cont->foperacion) ? date('Y-m-d (H:i)', strtotime($visado1Cont->foperacion)) : '',
                        'usuario_visado1_continuidad' => $visado1Cont->usuario,
                        'fecha_visado2_continuidad' => isset($visado2Cont->foperacion) ? date('Y-m-d (H:i)', strtotime($visado2Cont->foperacion)) : '',
                        'usuario_visado2_continuidad' => $visado2Cont->usuario,
                        'fecha_desvisado_continuidad' => isset($desvisadoCont->foperacion) ? date('Y-m-d (H:i)', strtotime($desvisadoCont->foperacion)) : '',
                        'usuario_desvisado_continuidad' => $desvisadoCont->usuario ?? '',
                        '-----' => '',
                        'fecha_creado_baja' => isset($visadoCreadoBaja->foperacion) ? date('Y-m-d (H:i)', strtotime($visadoCreadoBaja->foperacion)) : '',
                        'usuario_creado_baja' => $visadoCreadoBaja->usuario,
                        'fecha_visado1_baja' => isset($visado1Baja->foperacion) ? date('Y-m-d (H:i)', strtotime($visado1Baja->foperacion)) : '',
                        'usuario_visado1_baja' => $visado1Baja->usuario ?? '',
                        'fecha_visado2_baja' => isset($visado2Baja->foperacion) ? date('Y-m-d (H:i)', strtotime($visado2Baja->foperacion)) : '',
                        'usuario_visado2_baja' => $visado2Baja->usuario ?? '',
                        'fecha_desvisado_baja' => isset($desvisadoBaja->foperacion) ? date('Y-m-d (H:i)', strtotime($desvisadoBaja->foperacion)) : '',
                        'usuario_desvisado_baja' => $desvisadoBaja->usuario ?? '',
                    ];
                    $continuidadesConBajaAnterior[] = $datosArray;
                }
            }
        }

        return $continuidadesConBajaAnterior;
    }

    /**
     * Busca formularios de altas de libre disponibilidad con modelos relacionados según opciones de parámetros
     * Función importada de Siarhuv1
     * @param int    $idPuesto
     * @param int    $idEfector
     * @param int    $idEstado
     * @param int    $idTipoAlta
     * @param int    $idPeriodo
     * @param int    $idTipoAgrupamiento
     * @param bool   $fueraTermino
     * @param bool   $altasPorBajas
     * @param string $exportFormat
     * @return array
     * @throws CException
     */
    public function getAltasLibreDisponibilidad($idEfector, $idEstado, $idTipoAlta, $idPeriodo, $idTipoAgrupamiento, $fueraTermino, $altasPorBajas, $vigentes, $documento = null)
    {
        $vigentes = ($vigentes === 'true' ? true : false);
        $altasPorBajas = ($altasPorBajas === 'true' ? true : false);
        $fueraTermino = ($fueraTermino === 'true' ? true : false);

        if ((isset($idPeriodo) && $idPeriodo !== 0) || $vigentes) {
            if (!is_numeric($idPeriodo) || $idPeriodo === 0) {
                $periodo = Periodo::getPeriodoConFecha(null);
            } else {
                $periodo = Periodo::find($idPeriodo);
            }
        }

        return $this->model->with([
            'efector',
            'dependenciaDestino',
            'dependenciaOrigen',
            'ldTipoAlta',
            'ldCodigo',
            'puesto',
            'puesto.agente',
            'puesto.tipoFuncion',
            'puesto.tipoPlanta',
            'puesto.tipoAgrupamiento',
            'puesto.tipoNivel',
        ])
            ->vigentes($vigentes, $periodo)
            ->efector($idEfector)
            ->altasPorBajas($altasPorBajas)
            ->estado($idEstado)
            ->tipoAlta($idTipoAlta)
            ->tipoAgrupamiento($idTipoAgrupamiento)
            ->fueraTermino($fueraTermino)
            ->agente($documento)
            ->periodo($periodo, $vigentes)
            ->orderByDesc('fecha_creado')
            ->get()
            ->map->formatListado()
            ->all();
    }

    /**
     * Busca formularios de bajas o continuidades de libre disponibilidad con modelos relacionados según opciones de
     * parámetros
     * @param      $tipoFormulario
     * @param      $idEfector
     * @param      $idEstado
     * @param      $idTipoAlta
     * @param      $idPeriodo
     * @param      $idTipoAgrupamiento
     * @param      $fueraTermino
     * @param      $altasPorBajas
     * @param      $exportFormat
     * @param null $idPuesto
     * @return array
     * @throws CException
     */
    public function getCambioEstadoLibreDisponibilidad($tipoFormulario, $idEfector, $idEstado, $idTipoAlta, $idPeriodo, $idTipoAgrupamiento, $fueraTermino, $altasPorBajas, $idPuesto = null)
    {
        $altasPorBajas = ($altasPorBajas === 'true' ? true : false);
        $fueraTermino = ($fueraTermino === 'true' ? true : false);
        $idTipoFormulario = 6;
        if ($tipoFormulario === 'baja') {
            $idTipoFormulario = 7;
        }

        return LdCambioEstado::with([
            'efector',
            'ldAlta',
            'ldAlta.dependenciaOrigen',
            'ldAlta.dependenciaDestino',
            'ldAlta.tipoAgrupamiento',
            'ldAlta.ldTipoAlta',
            'ldAlta.ldCodigo',
            'ldAlta.puesto',
            'ldAlta.puesto.agente',
            'ldAlta.puesto.tipoNivel',
            'ldAlta.puesto.tipoFuncion',
            'ldAlta.puesto.tipoPlanta'
        ])
            ->efector($idEfector)
            ->altasPorBajas($altasPorBajas)
            ->estado($idEstado)
            ->tipoAlta($idTipoAlta)
            ->tipoFormulario($idTipoFormulario)
            ->tipoAgrupamiento($idTipoAgrupamiento)
            ->fueraTermino($fueraTermino)
            ->puesto($idPuesto)
            ->periodo($idPeriodo)
            ->orderByDesc('fecha_creado')
            ->get()
            ->map->formatListado()
            ->all();
    }

    public function getLibresSeleccionadas($tipoFormulario, $ids)
    {
        $ids = explode(',', $ids);
        if ($tipoFormulario === 'alta') {
            return $this->model->with([
                'efector',
                'dependenciaDestino',
                'dependenciaOrigen',
                'ldTipoAlta',
                'ldCodigo',
                'puesto',
                'puesto.agente',
                'puesto.tipoFuncion',
                'puesto.tipoPlanta',
                'puesto.tipoAgrupamiento',
                'puesto.tipoNivel'
            ])
            ->ids($ids)
            ->orderByDesc('fecha_creado')
            ->get()
            ->map->formatListado()
            ->all();
        } else {
            return LdCambioEstado::with([
                'efector',
                'ldAlta',
                'ldAlta.dependenciaOrigen',
                'ldAlta.dependenciaDestino',
                'ldAlta.tipoAgrupamiento',
                'ldAlta.ldTipoAlta',
                'ldAlta.ldCodigo',
                'ldAlta.puesto',
                'ldAlta.puesto.agente',
                'ldAlta.puesto.tipoNivel',
                'ldAlta.puesto.tipoFuncion',
                'ldAlta.puesto.tipoPlanta'
            ])
                ->ids($ids)
                ->orderByDesc('fecha_creado')
                ->get()
                ->map->formatListado()
                ->all();
        }
    }
}
