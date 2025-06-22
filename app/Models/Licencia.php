<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Licencia
 * @package App\Models
 * @version September 22, 2019, 11:05 pm -03
 *
 * @property \App\Models\Agente idagente
 * @property \App\Models\Puesto idpuesto
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection
 * @property \Illuminate\Database\Eloquent\Collection personas
 * @property integer idtipoLicencia
 * @property string fecha_inicio
 * @property string fecha_final
 * @property boolean primer_visado
 * @property boolean segundo_visado
 * @property boolean tercera_visado
 * @property boolean cuarta_visado
 */
class Licencia extends Model
{
    use SoftDeletes;

    public $table = 'licencias';
    public $primaryKey = 'idlicencia';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'idCapacitacion',
        'idefector',
        'idpuesto',
        'idagente',
        'idtipoLicencia',
        'primer_visado',
        'segundo_visado',
        'tercera_visado',
        'cuarta_visado',
        'fecha_pedido_inicio',
        'fecha_pedido_final',
        'fecha_evento_inicio',
        'fecha_evento_final',
        'fecha_interrupcion_inicio',
        'fecha_interrupcion_final',
        'fecha_efectiva_inicio',
        'fecha_efectiva_final',
        'observacion_primera',
        'observacion_segunda',
        'observacion_tercera',
        'observacion_cuarta',
        'razon',
        'evento_lugar',
        'evento_nombre',
        'alcance',
        'modalidad',
        'resolucion',
        'caracter',
        'idagenteResponsable',
        'fecha_visado_primero',
        'fecha_visado_segundo',
        'fecha_visado_interrupcion',
        'dias',
        'id_agente_primer_visado',
        'id_agente_segundo_visado',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idCapacitacion' => 'integer',
        'idefector' => 'integer',
        'idlicencia' => 'integer',
        'idpuesto' => 'integer',
        'idagente' => 'integer',
        'idtipoLicencia' => 'integer',
        'fecha_pedido_inicio' => 'date',
        'fecha_pedido_final' => 'date',
        'fecha_evento_inicio' => 'date',
        'fecha_evento_final' => 'date',
        'fecha_interrupcion_inicio' => 'date',
        'fecha_interrupcion_final' => 'date',
        'fecha_efectiva_inicio' => 'date',
        'fecha_efectiva_final' => 'date',
        'fecha_visado_primero' => 'date',
        'fecha_visado_segundo' => 'date',
        'fecha_visado_interrupcion' => 'date',
        'primer_visado' => 'boolean',
        'segundo_visado' => 'boolean',
        'tercera_visado' => 'boolean',
        'cuarta_visado' => 'boolean',
        'observacion_primera' => 'string',
        'observacion_segunda' => 'string',
        'observacion_tercera' => 'string',
        'observacion_cuarta' => 'string',
        'razon' => 'string',
        'alcance' => 'string',
        'caracter' => 'string',
        'evento_lugar' => 'string',
        'evento_nombre' => 'string',
        'idagenteResponsable' => 'integer',
        'dias' => 'integer',
        'id_agente_primer_visado' => 'integer',
        'id_agente_segundo_visado' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idlicencia' => 'required',
        'idpuesto' => 'required',
        'idagente' => 'required',
        'idtipoLicencia' => 'required',
        'fecha_pedido_inicio' => 'required',
        'fecha_pedido_final' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function capacitacion()
    {
        return $this->belongsTo(\App\Models\Capacitacion::class, 'idCapacitacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function personas()
    {
        return $this->hasOne(\App\Models\LicenciaFamiliar::class, 'licencia_familiares');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoLicencia()
    {
        return $this->belongsTo(\App\Models\TipoLicencia::class, 'idtipoLicencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licencia_saldos()
    {
        return $this->hasMany(\App\Models\LicenciaSaldos::class, 'idlicencia', 'idlicencia');
    }
}
