<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class GrupoFuncion
 * @package App\Models
 * @version October 28, 2019, 7:48 pm -03
 *
 * @property \App\Models\TipoGrupoFuncion idtipoGrupoFuncion
 * @property \App\Models\TipoFuncion idtipoFuncion
 * @property integer idtipo_grupo_funcion
 * @property integer idtipo_funcion
 */
class GrupoFuncion extends Model
{
    public $table = 'grupo_funcion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'idtipo_grupo_funcion',
        'idtipo_funcion',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idgrupo_funcion' => 'integer',
        'idtipo_grupo_funcion' => 'integer',
        'idtipo_funcion' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idtipo_grupo_funcion' => 'required',
        'idtipo_funcion' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoGrupoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoGrupoFuncion::class, 'idtipo_grupo_funcion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoFuncion::class, 'idtipo_funcion');
    }
}
