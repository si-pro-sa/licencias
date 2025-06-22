<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ReferenteDependencia
 * @package App\Models
 * @version December 9, 2018, 2:04 pm UTC
 *
 * @property integer idusuario
 * @property integer iddependencia
 * @property boolean vista_completa
 */
class ReferenteDependencia extends Model
{
    use SoftDeletes;

    public $table = 'referente_dependencia';
    protected $primaryKey = 'idreferente_dependencia';
    protected $connection = 'pgsql_eval';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'idusuario',
        'iddependencia',
        'vista_completa'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idreferente_dependencia' => 'integer',
        'idusuario' => 'integer',
        'iddependencia' => 'integer',
        'vista_completa' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idusuario' => 'required|unique:pgsql_eval.referente_dependencia'
    ];
}