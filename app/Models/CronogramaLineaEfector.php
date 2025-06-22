<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CronogramaLineaEfector
 * @package App\Models
 * @version July 17, 2020, 4:28 am -03
 *
 * @property \App\Models\Cronograma $idcronograma
 * @property \App\Models\Dependencium $idefector
 * @property time $hora
 * @property integer $idcronograma
 * @property integer $idefector
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class CronogramaLineaEfector extends Model
{
    public $table = 'linea_efector';
    protected $primaryKey = 'idlinea_efector';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];



    public $fillable = [
        'hora',
        'idcronograma',
        'idefector',
        'usuario',
        'operacion',
        'foperacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idlinea_efector' => 'integer',
        'idcronograma' => 'integer',
        'idefector' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'hora' => 'required',
        'idcronograma' => 'required',
        'idefector' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cronograma()
    {
        return $this->belongsTo(\App\Models\Cronograma::class, 'idcronograma');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efector()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'idefector');
    }
}
