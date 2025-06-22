<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoParentesco
 * @package App\Models
 * @version December 7, 2019, 2:10 am UTC
 *
 * @property integer codigo
 * @property string descripcion
 */
class TipoParentesco extends Model
{
    use SoftDeletes;

    public $table = 'tipo_parentescos';

    protected $primaryKey = 'idtipoParentesco';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'codigo',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipoParentesco' => 'integer',
        'codigo' => 'integer',
        'descripcion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'required'
    ];

    
}
