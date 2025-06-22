<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GrupoFamiliarPersona
 * @package App\Models
 * @version September 22, 2019, 9:37 pm -03
 *
 * @property \App\Models\Persona idpersona
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
 * @property integer idpersona
 * @property integer idgrupoFamiliar
 */
class GrupoFamiliarPersona extends Pivot
{
    use SoftDeletes;

    public $table = 'grupo_familiar_personas';
    public $primaryKey = 'idgrupoPersona';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'idpersona',
        'idgrupoFamiliar',
        'idtipoParentesco'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idgrupoPersona' => 'integer',
        'idpersona' => 'integer',
        'idgrupoFamiliar' => 'integer',
        'idtipoParentesco' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idgrupoPersona' => 'required',
        'idpersona' => 'required',
        'idgrupoFamiliar' => 'required',
        'idtipoParentesco' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function persona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'idpersona');
    }
    public function grupo()
    {
        return $this->belongsTo(\App\Models\GrupoFamiliar::class, 'idgrupoFamiliar');
    }

}
