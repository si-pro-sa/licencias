<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LdCodigo
 * @package App\Models
 * @version March 8, 2020, 8:01 pm -03
 *
 * @property \App\Models\LdTipoAltum idldTipoAlta
 * @property \App\Models\TipoAgrupamiento idtipoAgrupamiento
 * @property \App\Models\TipoFuncionJerarquica idtipoFuncionJerarquica
 * @property \App\Models\TipoNivel idtipoNivel
 * @property \Illuminate\Database\Eloquent\Collection ldAlta
 * @property \Illuminate\Database\Eloquent\Collection ldCodigoCostos
 * @property string ldcodigo
 * @property integer horas_semanales
 * @property number importe
 * @property integer idtipo_nivel
 * @property integer idtipo_agrupamiento
 * @property integer idld_tipo_alta
 * @property integer idtipo_funcion_jerarquica
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class LdCodigo extends Model
{
    public $table = 'ld_codigo';

    protected $primaryKey = 'idld_codigo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'ldcodigo',
        'horas_semanales',
        'importe',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idld_tipo_alta',
        'idtipo_funcion_jerarquica',
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
        'idld_codigo' => 'integer',
        'ldcodigo' => 'string',
        'horas_semanales' => 'integer',
        'importe' => 'float',
        'idtipo_nivel' => 'integer',
        'idtipo_agrupamiento' => 'integer',
        'idld_tipo_alta' => 'integer',
        'idtipo_funcion_jerarquica' => 'integer',
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
        'ldcodigo' => 'required',
        'horas_semanales' => 'required',
        'importe' => 'required',
        'idld_tipo_alta' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ldTipoAlta()
    {
        return $this->belongsTo(\App\Models\LdTipoAltum::class, 'idld_tipo_alta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoAgrupamiento()
    {
        return $this->belongsTo(\App\Models\TipoAgrupamiento::class, 'idtipo_agrupamiento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFuncionJerarquica()
    {
        return $this->belongsTo(\App\Models\TipoFuncionJerarquica::class, 'idtipo_funcion_jerarquica');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoNivel()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldAlta()
    {
        return $this->hasMany(\App\Models\LdAltum::class, 'idld_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldCodigoCostos()
    {
        return $this->hasMany(\App\Models\LdCodigoCosto::class, 'idld_codigo');
    }
}
