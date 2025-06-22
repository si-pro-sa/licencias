<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoNivel
 * @package App\Models
 * @version January 2, 2019, 7:54 pm UTC
 *
 * @property string tiponivel
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property integer idtipo_estado
 */
class TipoNivel extends Model
{
    public $table = 'tipo_nivel';
    protected $primaryKey = 'idtipo_nivel';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tiponivel',
        'usuario',
        'operacion',
        'foperacion',
        'idtipo_estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_nivel' => 'integer',
        'tiponivel' => 'string',
        'usuario' => 'string',
        'operacion' => 'string',
        'idtipo_estado' => 'integer'
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
    public function ldCodigos()
    {
        return $this->hasMany(\App\Models\LdCodigo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class,'idpuesto');
    }

    public function recomendacion()
    {
        return $this->hasMany('App\Model\RecomendacionCandidato');
    }
}
