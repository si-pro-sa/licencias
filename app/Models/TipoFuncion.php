<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class TipoFuncion
 * @package App\Models
 * @version January 2, 2019, 7:47 pm UTC
 *
 * @property string tipofuncion
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoFuncion extends Model
{
    public $table = 'tipo_funcion';
    protected $primaryKey = 'idtipo_funcion';
    protected $connection = 'pgsql_public';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'tipofuncion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_funcion' => 'integer',
        'tipofuncion' => 'string',
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

    public function scopeTipoFuncion($query, $tipofuncion){
        if (trim($tipofuncion) != "") {
            $raw = DB::raw('LOWER(CONCAT(idtipo_funcion,tipofuncion))');
            $query->where($raw, 'like', '%' . strtolower($tipofuncion) . '%');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class,'idpuesto');
    }

    public function evaluacionPsicotecnica()
    {
        return $this->hasMany(EvaluacionPsicotecnica::class,'idevaluacion_psicotecnica');
    }

    public function recomendacion()
    {
        return $this->hasMany('App\Model\RecomendacionCandidato');
    }
}
