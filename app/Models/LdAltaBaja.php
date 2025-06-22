<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LdAltaBaja
 * @package App\Models
 * @version April 16, 2021, 10:34 am -03
 *
 * @property \App\Models\LdAltum $idldAlta
 * @property \App\Models\LdCambioEstado $idldBaja
 * @property string|\Carbon\Carbon $fecha_creado
 * @property integer $idld_alta
 * @property integer $idld_baja
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class LdAltaBaja extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'ld_alta_baja_relacion';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'fecha_creado',
        'idld_alta',
        'idld_baja',
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
        'idld_alta_relacion' => 'integer',
        'fecha_creado' => 'datetime',
        'idld_alta' => 'integer',
        'idld_baja' => 'integer',
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
        'fecha_creado' => 'required',
        'idld_alta' => 'required|integer',
        'idld_baja' => 'required|integer',
        'usuario' => 'nullable|string|max:191',
        'operacion' => 'nullable|string|max:1',
        'foperacion' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idldAlta()
    {
        return $this->belongsTo(\App\Models\LdAltum::class, 'idld_alta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idldBaja()
    {
        return $this->belongsTo(\App\Models\LdCambioEstado::class, 'idld_baja');
    }
}
