<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoCambioEstadoObservacion
 * @package App\Models
 * @version October 28, 2019, 8:06 pm -03
 *
 * @property \App\Models\CargoCambioEstado idcargoCambioEstado
 * @property \App\Models\CargoTipoObservacion idcargoTipoObservacion
 * @property integer idcargo_cambio_estado
 * @property integer idcargo_tipo_observacion
 */
class CargoCambioEstadoObservacion extends Model
{
    use SoftDeletes;

    public $table = 'cargo_cambio_estado_obs';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo_cambio_estado',
        'idcargo_tipo_observacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_cambio_estado_obs' => 'integer',
        'idcargo_cambio_estado' => 'integer',
        'idcargo_tipo_observacion' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo_cambio_estado' => 'required',
        'idcargo_tipo_observacion' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargoCambioEstado()
    {
        return $this->belongsTo(\App\Models\CargoCambioEstado::class, 'idcargo_cambio_estado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargoTipoObservacion()
    {
        return $this->belongsTo(\App\Models\CargoTipoObservacion::class, 'idcargo_tipo_observacion');
    }
}
