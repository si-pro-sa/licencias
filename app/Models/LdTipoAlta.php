<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LdTipoAlta
 * @package App\Models
 * @version June 6, 2020, 9:04 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection $ldCodigos
 * @property \Illuminate\Database\Eloquent\Collection $ldAlta
 * @property string $ldtipo_alta
 * @property boolean $habilitada
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class LdTipoAlta extends Model
{
    public $table = 'ld_tipo_alta';

    protected $primaryKey = 'idld_tipo_alta';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];

    public $fillable = [
        'ldtipo_alta',
        'habilitada',
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
        'idld_tipo_alta' => 'integer',
        'ldtipo_alta' => 'string',
        'habilitada' => 'boolean',
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
        'ldtipo_alta' => 'required',
        'habilitada' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldCodigos()
    {
        return $this->hasMany(\App\Models\LdCodigo::class, 'idld_tipo_alta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldAlta()
    {
        return $this->hasMany(\App\Models\LdAltum::class, 'idld_tipo_alta');
    }
}
