<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoDevolucionRelacion
 * @package App\Models
 * @version October 28, 2019, 8:04 pm -03
 *
 * @property \App\Models\Cargo idcargoDevuelto
 * @property \App\Models\Cargo idcargo
 * @property integer idcargo_devuelto
 * @property integer idcargo
 */
class CargoDevolucionRelacion extends Model
{
    use SoftDeletes;

    public $table = 'cargo_devolucion_relacion';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo_devuelto',
        'idcargo'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_devolucion_relacion' => 'integer',
        'idcargo_devuelto' => 'integer',
        'idcargo' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo_devuelto' => 'required',
        'idcargo' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargoDevuelto()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo_devuelto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargo()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo');
    }
}
