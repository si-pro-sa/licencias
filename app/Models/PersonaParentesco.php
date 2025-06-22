<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PersonaParentesco
 * @package App\Models
 * @version December 7, 2019, 2:13 am UTC
 *
 * @property \App\Models\Persona idpersona
 * @property integer idtipoParentesco
 * @property integer idpersona
 */
class PersonaParentesco extends Model
{
    use SoftDeletes;

    public $table = 'persona_parentesco';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idtipoParentesco',
        'idpersona'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idpersonaParentesco' => 'integer',
        'idtipoParentesco' => 'integer',
        'idpersona' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idtipoParentesco' => 'required',
        'idpersona' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idpersona()
    {
        return $this->belongsTo(\App\Models\Persona::class, 'idpersona');
    }
}
