<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class TipoAgrupamiento
 * @package App\Models
 * @version November 30, 2018, 7:47 pm UTC
 *
 * @property string tipoagrupamiento
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoAgrupamiento extends Model
{
    public $table = 'tipo_agrupamiento';
    public $primaryKey = 'idtipo_agrupamiento';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tipoagrupamiento',
        'usuario',
        'operacion',
        'foperacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_agrupamiento' => 'integer',
        'tipoagrupamiento' => 'string',
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

    public function scopeTipoAgrupamiento($query, $tipoagrupamiento){
        if (trim($tipoagrupamiento) != "") {
            $raw = DB::raw('LOWER(CONCAT(idtipo_agrupamiento,tipoagrupamiento))');
            $query->where($raw, 'like', '%' . strtolower($tipoagrupamiento) . '%');
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reemplazos()
    {
        return $this->hasMany(\App\Models\Reemplazo::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class);
    }

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
    public function ldAlta()
    {
        return $this->hasMany(\App\Models\LdAltum::class);
    }
}
