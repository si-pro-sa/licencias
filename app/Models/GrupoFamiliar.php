<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GrupoFamiliar
 * @package App\Models
 * @version September 22, 2019, 9:16 pm -03
 *
 * @property \App\Models\Agente idagente
 * @property integer nExpediente
 * @property boolean aprobado
 * @property boolean activo
 * @property string vencimiento
 */
class GrupoFamiliar extends Model
{
    use SoftDeletes;

    public $table = 'grupo_familiares';
    public $primaryKey = 'idgrupoFamiliar';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'nExpediente',
        'idagente',
        'aprobado',
        'activo',
        'vencimiento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idgrupoFamiliar' => 'integer',
        'nExpediente' => 'integer',
        'idagente' => 'integer',
        'aprobado' => 'boolean',
        'activo' => 'boolean',
        'vencimiento' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idgrupoFamiliar' => 'required',
        'nExpediente' => 'required',
        'idagente' => 'required',
        'aprobado' => 'required',
        'activo' => 'required',
        'vencimiento' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }
    public function personas()
    {
        return $this->belongsToMany(\App\Models\Persona::class, 'grupo_familiar_personas','idpersona','idgrupoPersona');
        //return $this->belongsToMany(\App\Models\Persona::class)->using('App\Models\GrupoFamiliarPersona')
    }
}
