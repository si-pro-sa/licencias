<?php

namespace App\Models;

use Carbon\Carbon;
use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PeriodoEPCargo
 * @package App\Models
 *
 * @property date fdesde
 * @property date fhasta
 * @property integer dias
 * @property integer idefectiva_prestacion_cargo
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 *
 */
class PeriodoEPCargo extends MasterModel
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'periodo_ep_cargo';
    public $connection = 'pgsql_public';
    protected $primaryKey = 'idperiodo_ep_cargo';

    public $fillable = [
        'idefectiva_prestacion_cargo',
        'fdesde',
        'fhasta',
        'dias',
    ];

    protected $casts = [
        'idefectiva_prestacion_cargo' => 'integer',
        'fdesde' => 'datetime:d/m/Y',
        'fhasta' => 'datetime:d/m/Y',
        'dias' => 'integer',
    ];

     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'updated_by');
    }

    public function format()
    {
        return [
            'fdesde' => $this->fdesde->format('Y-m-d'),
            'fhasta' => $this->fhasta->format('Y-m-d'),
        ];
    }

    /**
     * Chequea que los periodos
     * enviados no esten superpuestos.
     */
    public static function isPeriodosSuperpuestos(array $periodos) //Recibe un array de Periodos
    {
        $size = count($periodos);
        if ($size >= 2) {
            for ($i = 0; $i < $size - 1; $i++) {
                for ($j = $i + 1; $j < $size; $j++) {
                    if (($periodos[$i]['fdesde'] <= $periodos[$j]['fhasta']) && ($periodos[$i]['fhasta'] >= $periodos[$j]['fdesde'])) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Chequea que los periodos
     * enviados no esten invertidos.
     */
    public static function isPeriodoInvertido(array $periodos)
    {
        foreach ($periodos as $periodo) {
            if ($periodo['fhasta'] < $periodo['fdesde']) {
                return true;
            }
        }
        return false;
    }
    /**
     * Chequea que los periodos
     * enviados esten dentro de
     * los limites del periodo actual.
     */
    public static function isPeriodoFueraDeLimite(array $periodos, Periodo $periodo_vigente)
    {
        foreach ($periodos as $periodo) {
            if ($periodo['fdesde'] < $periodo_vigente->fdesde || $periodo['fhasta'] > $periodo_vigente->fhasta) {
                return true;
            }
        }
        return false;
    }

    /**
     *  Chequea que los periodos enviados esten
     *  dentro de los limites de la autorización
     *  de la cobertura
     */
    public static function isFueraFechasDeAutorizacion(array $periodos, Cargo $cargo)
    {
        foreach ($periodos as $periodo) {
            if ($periodo['fdesde'] < $cargo->fecha_inicio_alta->format('Y-m-d') ||
                 (isset($cargo->fecha_vencimiento_real) ? ($periodo['fhasta'] > $cargo->fecha_vencimiento_real->format('Y-m-d')):false)) {
                    return true;
            }
        }
        return false;
    }

    /**
     * Permite guardar los periodos desdoblados de una EfectivaPrestacionCargo
     * Devuelve el total de dias si se realiza correctamente.
     * Devuelve 0 si existe algun problema.
     */
    public static function savePeriodos(array $periodos, EfectivaPrestacionCargo $ep)
    {
        if (PeriodoEPCargo::isPeriodoInvertido($periodos)) {
            return 0;
        }
        if (PeriodoEPCargo::isPeriodosSuperpuestos($periodos)) {
            return 0;
        }

        if (PeriodoEPCargo::isFueraFechasDeAutorizacion($periodos, $ep->cargo)) {
            return 0;
        }

        $total_dias = 0;
        if (isset($periodos) && count($periodos) > 0) {
            foreach ($periodos as $periodo) {
                $dias_periodo = Util::contarDiasEntreFechas($periodo['fdesde'],$periodo['fhasta']);
                $total_dias = $total_dias + $dias_periodo;
                PeriodoEPCargo::create([
                    'idefectiva_prestacion_cargo' => $ep->idefectiva_prestacion_cargo,
                    'fdesde' => $periodo['fdesde'],
                    'fhasta' => $periodo['fhasta'],
                    'dias' => $dias_periodo,
                ]);
            }

        }
        return $total_dias;
    }
    /**
     * Busca los limites de un periodo de ep
     * según la autorización.
     */
    public static function limitPeriodo($fdesde, $fhasta, Periodo $periodo)
    {
        $limites_periodo = [
            'fdesde' => $periodo->fdesde,
            'fhasta' => $periodo->fhasta,
        ];
        if (isset($fdesde) && Util::isFechasIgualMes($fdesde, $periodo->fdesde)) {
            if ($fdesde->day >= $periodo->fdesde->day) {
                $limites_periodo['fdesde'] = $fdesde;
            }
        }
        if (isset($fhasta) && Util::isFechasIgualMes($fhasta, $periodo->fhasta)) {
            if ($fhasta->day <= $periodo->fhasta->day) {
                $limites_periodo['fhasta'] = $fhasta;
            }
        }

        return $limites_periodo;
    }
}
