<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class Periodo
 * @package App\Models
 * @version April 15, 2019, 12:42 pm -03
 *
 * @property \App\Models\TipoMes tipoMes
 * @property string periodo
 * @property integer idtipo_mes
 * @property integer anio
 * @property date fdesde
 * @property date fhasta
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property date finiciocarga
 * @property integer orden
 */
class Periodo extends Model
{
    public $primaryKey = 'idperiodo';
    public $table = 'periodo';

    public $fillable = [
        'periodo',
        'idtipo_mes',
        'anio',
        'fdesde',
        'fhasta',
        'usuario',
        'operacion',
        'foperacion',
        'finiciocarga',
        'orden'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idperiodo' => 'integer',
        'periodo' => 'string',
        'idtipo_mes' => 'integer',
        'anio' => 'integer',
        'fdesde' => 'date',
        'fhasta' => 'date',
        'usuario' => 'string',
        'operacion' => 'string',
        'finiciocarga' => 'date',
        'orden' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function scopePeriodo($query, $periodo){
        if (trim($periodo) != "" && $periodo != '...') {
            $raw = DB::raw('LOWER(CONCAT(idperiodo,periodo))');
            $query->where($raw, 'like', '%' . strtolower($periodo) . '%');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoMes()
    {
        return $this->belongsTo(\App\Models\TipoMes::class);
    }

    /**
     * Devuelve una fecha formateada como 31/12/2019 a 2019-12-31
     * @param string $fecha
     * @return string
     */
    public static function corregirFormatoFecha(?string $fecha)
    {
        //Corrijo formato de fecha
        $format = strpos($fecha, '-');

        if (isset($fecha) && !empty($fecha) && $format === false) {
            $fecha = str_replace('/', '-', $fecha);
            $fecha = date('Y-m-d', strtotime($fecha));
        }
        return $fecha;
    }

    /**
     * Con la fecha ingresada o actual calcula el período al que pertenece y devuelve el id
     * @param null|string $fecha
     * @return Periodo
     */
    public static function getPeriodoConFecha(?string $fecha): Periodo
    {
        $fecha = self::corregirFormatoFecha($fecha);

        if (!isset($fecha) && empty($fecha)) {
            $fecha = time();
        } else {
            $fecha = strtotime($fecha);
        }

        $anio = date('Y', $fecha);
        $mes = date('m', $fecha);

        return Periodo::where(['anio' => $anio, 'idtipo_mes' => $mes])->first();
    }
}
