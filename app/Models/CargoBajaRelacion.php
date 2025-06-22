<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoBajaRelacion
 * @package App\Models
 * @version October 28, 2019, 8:03 pm -03
 *
 * @property \App\Models\Cargo idcargoBaja
 * @property \App\Models\Cargo idcargo
 * @property integer idcargo_baja
 * @property integer idcargo
 */
class CargoBajaRelacion extends Model
{
    use SoftDeletes;

    public $table = 'cargo_baja_relacion';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo_baja',
        'idcargo',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_baja_relacion' => 'integer',
        'idcargo_baja' => 'integer',
        'idcargo' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo_baja' => 'required',
        'idcargo' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargoBaja()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo_baja');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargo()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo');
    }
}
