<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoGrupoFuncion
 * @package App\Models
 * @version October 28, 2019, 7:46 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection grupoFuncions
 * @property integer tipogrupo_funcion
 */
class TipoGrupoFuncion extends Model
{
    use SoftDeletes;

    public $table = 'tipo_grupo_funcion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'tipogrupo_funcion',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_grupo_funcion' => 'integer',
        'tipogrupo_funcion' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipogrupo_funcion' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function grupoFunciones()
    {
        return $this->hasMany(\App\Models\GrupoFuncion::class, 'idtipo_grupo_funcion');
    }
}
