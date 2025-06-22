<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Titulo
 * @package App\Models
 * @version January 2, 2019, 6:56 pm UTC
 *
 * @property \App\Models\TipoAreatematica tipoAreatematica
 * @property \App\Models\TipoNiveleducativo tipoNiveleducativo
 * @property string titulo
 * @property integer idtipo_areatematica
 * @property integer idtipo_niveleducativo
 * @property integer duracion
 * @property string duracion_observacion
 * @property string areatematica
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class Titulo extends Model
{
    public $table = 'titulo';
    protected $primaryKey = 'idtitulo';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'titulo',
        'idtipo_areatematica',
        'idtipo_niveleducativo',
        'duracion',
        'duracion_observacion',
        'areatematica',
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
        'idtitulo' => 'integer',
        'titulo' => 'string',
        'idtipo_areatematica' => 'integer',
        'idtipo_niveleducativo' => 'integer',
        'duracion' => 'integer',
        'duracion_observacion' => 'string',
        'areatematica' => 'string',
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
    public function tipoAreatematica()
    {
        return $this->belongsTo(\App\Models\TipoAreatematica::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoNiveleducativo()
    {
        return $this->belongsTo(\App\Models\TipoNiveleducativo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function agentes()
    {
        return $this->hasMany(\App\Models\Agente::class, 'agentetitulo');
    }

    public function candidatos()
    {
        return $this->hasMany(\App\Models\Candidato::class, 'idtitulo');
    }

    public function recomendacion()
    {
        return $this->hasMany('App\Model\RecomendacionCandidato');
    }
}
