<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoPlanta
 * @package App\Models
 * @version January 3, 2019, 5:48 am UTC
 *
 * @property string tipoplanta
 */
class TipoPlanta extends Model
{
    public $table = 'tipo_planta';
    protected $primaryKey = 'idtipo_planta';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tipoplanta'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_planta' => 'integer',
        'tipoplanta' => 'string'
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
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class);
    }
}
