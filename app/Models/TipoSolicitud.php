<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoSolicitud
 * @package App\Models
 * @version October 20, 2020, 9:54 am -03
 *
 * @property \Illuminate\Database\Eloquent\Collection reemplazos
 * @property string tiposolicitud
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoSolicitud extends Model
{
    public $table = 'tipo_solicitud';
    protected $primaryKey = 'idtipo_solicitud';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'tiposolicitud',
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
        'idtipo_solicitud' => 'integer',
        'tiposolicitud' => 'string',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime'
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
    public function reemplazos()
    {
        return $this->hasMany(\App\Models\Reemplazo::class, 'idtipo_solicitud');
    }
}
