<?php
namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
* Class TipoAgrupamiento
* @package App\Models
* @version March 30, 2022, 7:47 pm UTC
*
*/
class GuardiaCodigoCosto extends Model
{
    use SoftDeletes;
    public $table = 'guardia_codigo_costo';
    protected $primaryKey = 'idguardia_codigo_costo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    /**
    * The attributes that should be casted to native types.
    *
    * @var array
    */
    protected $casts = [
        'idguardia_codigo_costo' => 'integer',
        'idguardia_codigo' => 'integer',
        'fdesde' => 'datetime'
    ];

    /**
    * Validation rules
    *
    * @var array
    */
    public static $rules = [

    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    **/
    public function guardiaCodigo()
    {
        return $this->belongsTo(\App\Models\GuardiaCodigo::class);
    }
}
