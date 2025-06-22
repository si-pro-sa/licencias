<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GuardiaLinea
 * @package App\Models
 * @version March 8, 2020, 8:39 pm -03
 *
 * @property \App\Models\Guardium idguardia
 * @property \App\Models\GuardiaTipoEstadoLinea idguardiaTipoEstadoLinea
 * @property \App\Models\Puesto idpuesto
 * @property \Illuminate\Database\Eloquent\Collection guardiaTipoVisados
 * @property integer idguardia
 * @property time hora_desde
 * @property time hora_hasta
 * @property integer idpuesto
 * @property integer idguardia_tipo_estado_linea
 * @property string created_by
 * @property string updated_by
 * @property string deleted_by
 * @property boolean aprobado
 */
class GuardiaLinea extends Model
{
    use SoftDeletes;

    public $table = 'guardia_linea';

    protected $primaryKey = 'idguardia_linea';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'idguardia',
        'hora_desde',
        'hora_hasta',
        'idpuesto',
        'idguardia_tipo_estado_linea',
        'created_by',
        'updated_by',
        'deleted_by',
        'aprobado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idguardia_linea' => 'integer',
        'idguardia' => 'integer',
        'idpuesto' => 'integer',
        'idguardia_tipo_estado_linea' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string',
        'aprobado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idguardia' => 'required',
        'hora_desde' => 'required',
        'hora_hasta' => 'required',
        'idpuesto' => 'required',
        'idguardia_tipo_estado_linea' => 'required',
        'aprobado' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function guardia()
    {
        return $this->belongsTo(\App\Models\Guardia::class, 'idguardia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function guardiaTipoEstadoLinea()
    {
        return $this->belongsTo(\App\Models\GuardiaTipoEstadoLinea::class, 'idguardia_tipo_estado_linea');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function guardiaTipoVisados()
    {
        return $this->belongsToMany(\App\Models\GuardiaTipoVisado::class, 'efectiva_prestacion_guardia');
    }
}
