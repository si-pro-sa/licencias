<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Persona
 * @package App\Models
 * @version September 22, 2019, 4:50 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property integer documento
 * @property string nombre
 * @property string apellido
 * @property string fecha_nacimiento
 * @property string parentesco
 * @property boolean discapacidad
 */
class Persona extends Model
{
    use SoftDeletes;
    public $table = 'personas';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'idpersona';

    protected $dates = ['deleted_at'];

    //protected $hidden = ['created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];


    public $guarded = [];

    public function grupos() {
        //return $this->belongsToMany('App\Models\Grupo_Familiar')->using('App\Models\Grupo_Persona');
        return $this->belongsToMany(\App\Models\GrupoFamiliar::class, 'grupo_familiar_personas','idgrupoFamiliar','idgrupoPersona');
    }
    public function licencia() {
        return $this->hasMany('App\Models\Licencia_Familiar');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idpersona' => 'integer',
        'documento' => 'integer',
        'nombre' => 'string',
        'apellido' => 'string',
        'fecha_nacimiento' => 'date',
        'parentesco' => 'string',
        'discapacidad' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idpersona' => 'required',
        'documento' => 'required',
        'nombre' => 'required',
        'apellido' => 'required',
        'fecha_nacimiento' => 'required',
        'discapacidad' => 'required'
    ];




}
