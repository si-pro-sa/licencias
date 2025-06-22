<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoTipoObservacion
 * @package App\Models
 * @version October 28, 2019, 8:06 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstadoObs
 * @property string cargotipo_observacion
 */
class CargoTipoObservacion extends Model
{
    use SoftDeletes;

    public $table = 'cargo_tipo_observacion';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'cargotipo_observacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_observacion' => 'integer',
        'cargotipo_observacion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cargotipo_observacion' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstadoObs()
    {
        return $this->hasMany(\App\Models\CargoCambioEstadoOb::class, 'idcargo_tipo_observacion');
    }
}
