<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class DependenciaUsuario
 * @package App\Models
 * @version November 27, 2018, 7:16 am UTC
 *
 * @property integer iddependencia
 * @property integer idusuario
 * @property integer iddependencia_hija
 * @property string usuario
 * @property string operacion
 * @property time foperacion
 */
class DependenciaUsuario extends Model
{
    public $table = 'dependenciausuario';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];


    public $fillable = [
        'iddependencia',
        'idusuario',
        'iddependencia_hija',
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
        'iddependenciausuario' => 'integer',
        'iddependencia' => 'integer',
        'idusuario' => 'integer',
        'iddependencia_hija' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaHija()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependencia_hija', 'iddependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaPadre()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependencia_padre', 'iddependencia');
    }
}
