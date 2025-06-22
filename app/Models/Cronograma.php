<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Periodo;
use App\Models\CronogramaLineaEfector;

/**
 * Class Cronograma
 * @package App\Models
 * @version July 17, 2020, 4:25 am -03
 *
 * @property \App\Models\Periodo $periodo
 * @property \App\Models\TipoCronograma $tipoCronograma
 * @property \Illuminate\Database\Eloquent\Collection $dependencia
 * @property string|\Carbon\Carbon $fecha_creado
 * @property string $fecha
 * @property integer $idperiodo
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 * @property integer $idtipo_cronograma
 */
class Cronograma extends Model
{
    public $table = 'cronograma';
    protected $primaryKey = 'idcronograma';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];


    public $fillable = [
        'fecha_creado',
        'fecha',
        'idperiodo',
        'usuario',
        'operacion',
        'foperacion',
        'idtipo_cronograma'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcronograma' => 'integer',
        'fecha_creado' => 'datetime',
        'fecha' => 'date',
        'idperiodo' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime',
        'idtipo_cronograma' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha_creado' => 'required',
        'fecha' => 'required',
        'idperiodo' => 'required',
        'idtipo_cronograma' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCronograma()
    {
        return $this->belongsTo(\App\Models\TipoCronograma::class, 'idtipo_cronograma');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function lineasEfector()
    {
        return $this->belongsToMany(\App\Models\CronogramaLineaEfector::class, 'idcronograma');
    }

    /**
     * Devuelve los cronogramas de los efectores
     */
    public static function getCronogramas(?array $idsEfectores = []): array
    {
        $cronogramas = ['solicitudes' => [], 'novedades' => []];
        if (is_array($idsEfectores) && count($idsEfectores) > 0) {
            $periodo = Periodo::getPeriodoConFecha(date('Y-m-d', strtotime('+1 month', time())));
            $lineasEfector = CronogramaLineaEfector::whereHas('cronograma', function ($query) use ($periodo) {
                $query->with('tipoCronograma')
                    ->where('idperiodo', $periodo->idperiodo);
            })
            ->whereIn('idefector', $idsEfectores)
            ->get();
            if (isset($lineasEfector) && count($lineasEfector) > 0) {
                foreach ($lineasEfector as $le) {
                    if (isset($le, $le->cronograma->fecha, $le->hora)) {
                        $tipoCronograma = $le->cronograma->idtipo_cronograma === 1 ? 'solicitudes' : 'novedades';
                        $cronogramas[$tipoCronograma][] = [
                            'idefector' => $le->idefector,
                            'efector' => $le->efector->codigo_nombre,
                            'fecha_cierre' => date('d/m/Y', strtotime($le->cronograma->fecha)),
                            'hora_cierre' => date('H:i', strtotime($le->hora)),
                            'tipo_cronograma' => $le->cronograma->tipoCronograma->tipocronograma
                            ];
                    }
                }
            }
        }
        return $cronogramas;
    }
}
