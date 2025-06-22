<?php
namespace App\Repositories;

use App\Models\GuardiaCodigo;
use App\Models\Guardia;
use App\Models\Periodo;
use App\Models\RangoTiempo;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class EfectivaPrestacionGuardiaRepository
 * @package App\Repositories
 * @version March 8, 2020, 8:39 pm -03
*/

class EfectivaPrestacionGuardiaRepository extends BaseRepository
{
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
        return Guardia::class;
    }

    /**
     * Exporto PDF de Efectiva Prestación de Guardias.
     *
     * @param $idefector
     * @param $idservicio
     * @param $idtipo_guardia
     * @param $idperiodo
     * @param $idtipo_campania
     * @param $fuera_termino
     * @param $fecha_desde
     * @param $fecha_hasta
     * @param $exportFormat
     *
     * @throws CException
     */
    public function exportarEfectivaPrestacionAdelia(int $idperiodo)
    {
        $listadoFechasMes = Periodo::listadoDiasMes($idperiodo);
        //GUARDIAS DE FIESTAS
        $guardiasFiestasAgente = [];
        if (!empty($listadoFechasMes['fiestas'])) {
            $guardiasFiestasAgente = self::contarGuardiasAgente($listadoFechasMes['fiestas']);
        }
        //GUARDIAS DE SÁBADOS DOMINGOS Y FERIADOS
        $guardiasSDFAgente = self::contarGuardiasAgente($listadoFechasMes['sdf']);
        //GUARDIAS DE LUNES A VIERNES
        $guardiasLVAgente = self::contarGuardiasAgente($listadoFechasMes['lv']);

        $guardias = DB::table('guardia_linea')
            ->join('guardia', 'guardia_linea.idguardia', '=', 'guardia.idguardia')
            ->join('efectiva_prestacion_guardia AS epg', 'guardia_linea.idguardia_linea', '=', 'epg.idguardia_linea')
            ->join('puesto', 'guardia_linea.idpuesto', '=', 'puesto.idpuesto')
            ->join('agente', 'puesto.idagente', '=', 'agente.idagente')
            ->leftJoin('dependencia AS e', 'e.iddependencia', '=', 'guardia.idefector')
            ->leftJoin('dependencia', 'dependencia.iddependencia', '=', 'guardia.idservicio')
            ->leftJoin('tipo_nivel', 'puesto.idtipo_nivel', '=', 'tipo_nivel.idtipo_nivel')
            ->leftJoin('tipo_funcion', 'puesto.idtipo_funcion', '=', 'tipo_funcion.idtipo_funcion')
            ->leftJoin('tipo_guardia', 'guardia.idtipo_guardia', '=', 'tipo_guardia.idtipo_guardia')
            ->where('guardia.idperiodo', $idperiodo)
            ->groupBy(
                'guardia_linea.idpuesto',
                'agente.apellido',
                'agente.nombre',
                'nombre',
                'documento',
                'efector',
                'fecha',
                'servicio',
                'idservicio',
                'funcion',
                'nivel',
                'puesto.idtipo_nivel',
                'tipo_guardia',
                'guardia.idtipo_guardia'
            )
            ->select(
                DB::raw(
                    'DISTINCT CONCAT(agente.apellido, \' \', agente.nombre) AS nombre,
                agente.documento AS documento,
                e.dependencia AS efector,
                dependencia.dependencia AS servicio,
                guardia.idservicio as idservicio,
                guardia.fecha as fecha,
                tipo_funcion.tipofuncion AS funcion,
                tipo_nivel.tiponivel AS nivel,
                tipo_guardia.tipoguardia AS tipo_guardia,
                guardia_linea.idpuesto as idpuesto,
                guardia.idtipo_guardia as idtipo_guardia,
                puesto.idtipo_nivel AS idtipo_nivel'
                )
            )

            ->orderBy('nombre')
            ->get();

        //Armo array con todas las las guardias encontradas y lo combino con las horas de guardia de cada puesto-servicio
        $guardiasFiestasArray = [];
        $guardiasLVSDF = [];
        foreach ($guardias as $key => $guardia) {
            $arrayKey = "{$guardia->idpuesto}-{$guardia->idservicio}-{$guardia->idtipo_guardia}";

            $guardias[$key]->cantidad_sdf = $guardiasSDFAgente[$arrayKey] ?? 0;
            $codigoSDF = GuardiaCodigo::getMontoCodigo($guardias[$key]->cantidad_sdf, $guardia->idtipo_nivel, $guardia->idtipo_guardia, 2, $guardia->fecha);
            $guardias[$key]->codigo_sdf = $codigoSDF['nombre'];
            $guardias[$key]->importe_sdf = '$ ' . $codigoSDF['total'];

            $guardias[$key]->cantidad_lv = $guardiasLVAgente[$arrayKey] ?? 0;
            $codigoLV = GuardiaCodigo::getMontoCodigo($guardias[$key]->cantidad_lv, $guardia->idtipo_nivel, $guardia->idtipo_guardia, 1, $guardia->fecha);
            $guardias[$key]->codigo_lv = $codigoLV['nombre'];
            $guardias[$key]->importe_lv = '$ ' . $codigoLV['total'];

            if ($guardias[$key]->cantidad_lv > 0 || $guardias[$key]->cantidad_sdf > 0) {
                $guardiasLVSDF[$arrayKey] = $guardias[$key];
            }

            //Si en el mes a exportar hay fechas de fiestas, agregar los contadores
            if (!empty($listadoFechasMes['fiestas'])) {
                $guardias[$key]->cantidad_fiestas = $guardiasFiestasAgente[$arrayKey] ?? 0;
                $codigoFiestas = GuardiaCodigo::getMontoCodigo($guardias[$key]->cantidad_fiestas, $guardia->idtipo_nivel, $guardia->idtipo_guardia, 3, $guardia->fecha);
                if (isset($codigoFiestas['nombre'], $codigoFiestas['total']) && (int) $codigoFiestas['total'] > 0) {
                    if (!isset($guardiasFiestasArray[$arrayKey])) {
                        $guardiasFiestasArray[$arrayKey] = $guardias[$key];
                    }
                    $guardiasFiestasArray[$arrayKey]['codigo_lv'] = '';
                    $guardiasFiestasArray[$arrayKey]['importe_lv'] = '';
                    $guardiasFiestasArray[$arrayKey]['cantidad_lv'] = '';
                    $guardiasFiestasArray[$arrayKey]['codigo_sdf'] = $codigoFiestas['nombre'];
                    $guardiasFiestasArray[$arrayKey]['importe_sdf'] = '$ ' . $codigoFiestas['total'];
                    $guardiasFiestasArray[$arrayKey]['cantidad_sdf'] = $guardias[$key]->cantidad_fiestas;
                }
            }
        }
        return array_merge(array_values($guardiasLVSDF), array_values($guardiasFiestasArray));
    }

    public static function contarGuardiasAgente(array $dias)
    {
        if (!empty($dias)) {
            $guardiasAgente = [];
            //Busco líneas de Guardia que cumplan las condiciones
            $guardias = DB::table('guardia_linea')
            ->join('guardia', 'guardia_linea.idguardia', '=', 'guardia.idguardia')
            ->join('efectiva_prestacion_guardia', 'guardia_linea.idguardia_linea', '=', 'efectiva_prestacion_guardia.idguardia_linea')
            ->leftJoin('efectiva_prestacion_observacion_guardia', 'efectiva_prestacion_guardia.idep_guardia', '=', 'efectiva_prestacion_observacion_guardia.idep_guardia')
            ->select('guardia_linea.idpuesto', 'guardia.idservicio', 'guardia.idtipo_guardia', 'guardia_linea.hora_desde', 'guardia_linea.hora_hasta', 'efectiva_prestacion_guardia.idep_guardia', 'efectiva_prestacion_observacion_guardia.idtipo_observacion_novedad')
            ->whereIn('guardia.fecha', $dias)
            ->get();
            //Armo un array para contar las guardias de un agente con las mismas y calculo las horas de guardia
            foreach ($guardias as $ep) {
                if (isset($ep->idpuesto, $ep->idservicio, $ep->idtipo_guardia)) {
                    $arrayKey = "{$ep->idpuesto}-{$ep->idservicio}-{$ep->idtipo_guardia}";
                    if (!isset($guardiasAgente[$arrayKey])) {
                        $guardiasAgente[$arrayKey] = 0;
                    }
                    $rangoTiempo = new RangoTiempo($ep->hora_desde, $ep->hora_hasta);
                    $guardiasAgente[$arrayKey] += $rangoTiempo->getDiffHoras() > 6 ? 1 : 0.5;
                }
            }
        }
        return $guardiasAgente;
    }
}
