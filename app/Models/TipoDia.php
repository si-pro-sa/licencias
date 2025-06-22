<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoDia
 * @package App\Models
 * @version October 28, 2019, 8:10 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection horarioPuestos
 * @property \Illuminate\Database\Eloquent\Collection horarioDependencia
 * @property string tipodia
 * @property string nombre_corto
 */
class TipoDia extends Model
{
    public $table = 'tipo_dia';
    protected $primaryKey = 'idtipo_dia';

    public $fillable = [
        'tipodia',
        'nombre_corto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_dia' => 'integer',
        'tipodia' => 'string',
        'nombre_corto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipodia' => 'required',
        'nombre_corto' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function horarioPuestos()
    {
        return $this->hasMany(\App\Models\HorarioPuesto::class, 'idtipo_dia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function horarioDependencia()
    {
        return $this->hasMany(\App\Models\HorarioDependencia::class, 'idtipo_dia');
    }
}
