<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CostoLaboral
 * @package App\Models
 * @version October 20, 2020, 11:32 am -03
 *
 * @property string fdesde
 * @property string fhasta
 * @property number monto_a
 * @property number monto_b
 * @property number monto_c
 * @property number monto_d
 * @property number monto_e
 * @property number monto_f
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class CostoLaboral extends Model
{
    public $table = 'costolaboral';
    protected $primaryKey = 'idcostolaboral';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];
    public $fillable = [
        'fdesde',
        'fhasta',
        'monto_a',
        'monto_b',
        'monto_c',
        'monto_d',
        'monto_e',
        'monto_f',
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
        'idcostolaboral' => 'integer',
        'fdesde' => 'date',
        'fhasta' => 'date',
        'monto_a' => 'float',
        'monto_b' => 'float',
        'monto_c' => 'float',
        'monto_d' => 'float',
        'monto_e' => 'float',
        'monto_f' => 'float',
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
        
    ];
}
