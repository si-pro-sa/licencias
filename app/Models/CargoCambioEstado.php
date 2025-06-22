<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoCambioEstado
 * @package App\Models
 * @version October 28, 2019, 8:04 pm -03
 *
 * @property \App\Models\Cargo idcargo
 * @property \App\Models\Periodo idperiodoDesde
 * @property \App\Models\Periodo idperiodoHasta
 * @property \App\Models\CargoTipoVisado idcargoTipoVisado
 * @property \App\Models\TipoFormulario idtipoFormulario
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstadoObs
 * @property integer idcargo
 * @property integer idperiodo_desde
 * @property string fecha_desde
 * @property integer idperiodo_hasta
 * @property string fecha_hasta
 * @property integer idcargo_tipo_visado
 * @property integer idtipo_formulario
 * @property string fecha_ingreso
 * @property string fecha_devolucion
 * @property string fecha_entrega_organismo
 * @property string observaciones_internas
 */
class CargoCambioEstado extends Model
{
    use SoftDeletes;

    public $table = 'cargo_cambio_estado';
    public $primaryKey = 'idcargo_cambio_estado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo',
        'idperiodo_desde',
        'fecha_desde',
        'idperiodo_hasta',
        'fecha_hasta',
        'idcargo_tipo_visado',
        'idtipo_formulario',
        'fecha_ingreso',
        'fecha_devolucion',
        'fecha_entrega_organismo',
        'observaciones_internas'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_cambio_estado' => 'integer',
        'idcargo' => 'integer',
        'idperiodo_desde' => 'integer',
        'fecha_desde' => 'date',
        'idperiodo_hasta' => 'integer',
        'fecha_hasta' => 'date',
        'idcargo_tipo_visado' => 'integer',
        'idtipo_formulario' => 'integer',
        'fecha_ingreso' => 'date',
        'fecha_devolucion' => 'date',
        'fecha_entrega_organismo' => 'date',
        'observaciones_internas' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo' => 'required',
        'idperiodo_desde' => 'required',
        'fecha_desde' => 'required',
        'idperiodo_hasta' => 'required',
        'idcargo_tipo_visado' => 'required',
        'idtipo_formulario' => 'required',
        'observaciones_internas' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargo()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodoDesde()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo_desde');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodoHasta()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo_hasta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargoTipoVisado()
    {
        return $this->belongsTo(\App\Models\CargoTipoVisado::class, 'idcargo_tipo_visado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFormulario()
    {
        return $this->belongsTo(\App\Models\TipoFormulario::class, 'idtipo_formulario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstadoObs()
    {
        return $this->hasMany(\App\Models\CargoCambioEstadoObservacion::class, 'idcargo_cambio_estado');
    }
}
