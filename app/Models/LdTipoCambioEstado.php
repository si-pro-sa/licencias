<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LdTipoCambioEstado
 * @package App\Models
 * @version January 29, 2021, 7:23 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection $ldCambioEstados
 * @property string $ldtipo_cambio_estado
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class LdTipoCambioEstado extends Model
{
    public $table = 'ld_tipo_cambio_estado';
    protected $primaryKey = 'idld_tipo_cambio_estado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];



    public $fillable = [
        'ldtipo_cambio_estado',
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
        'idld_tipo_cambio_estado' => 'integer',
        'ldtipo_cambio_estado' => 'string',
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
        'ldtipo_cambio_estado' => 'required|string|max:191',
        'usuario' => 'nullable|string|max:191',
        'operacion' => 'nullable|string|max:1',
        'foperacion' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldCambioEstados()
    {
        return $this->hasMany(\App\Models\LdCambioEstado::class, 'idld_tipo_cambio_estado');
    }
}
