<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Guardia
 * @package App\Models
 * @version March 8, 2020, 8:39 pm -03
 *
 * @property \App\Models\Dependencium idefector
 * @property \App\Models\Periodo idperiodo
 * @property \App\Models\Dependencium idservicio
 * @property \App\Models\TipoCampanium idtipoCampania
 * @property \App\Models\TipoFormulario idtipoFormulario
 * @property \App\Models\TipoGuardium idtipoGuardia
 * @property \Illuminate\Database\Eloquent\Collection guardiaLineas
 * @property string fecha
 * @property boolean fuera_termino
 * @property integer cantidad_lv
 * @property integer cantidad_sdf
 * @property integer cantidad_novedad_lv
 * @property integer cantidad_novedad_sdf
 * @property integer idperiodo
 * @property integer idefector
 * @property integer idservicio
 * @property integer idtipo_guardia
 * @property integer idtipo_campania
 * @property integer idtipo_formulario
 * @property string created_by
 * @property string updated_by
 * @property string deleted_by
 */
class Guardia extends Model
{
    use SoftDeletes;

    public $table = 'guardia';

    protected $primaryKey = 'idguardia';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    //Tipos de Guardia que suman horas en el conteo general de un agente
    public $tiposGuardiaSumanHs = [1, 3, 8, 10, 11];


    public $fillable = [
        'fecha',
        'fuera_termino',
        'cantidad_lv',
        'cantidad_sdf',
        'cantidad_novedad_lv',
        'cantidad_novedad_sdf',
        'idperiodo',
        'idefector',
        'idservicio',
        'idtipo_guardia',
        'idtipo_campania',
        'idtipo_formulario',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idguardia' => 'integer',
        'fecha' => 'date',
        'fuera_termino' => 'boolean',
        'cantidad_lv' => 'integer',
        'cantidad_sdf' => 'integer',
        'cantidad_novedad_lv' => 'integer',
        'cantidad_novedad_sdf' => 'integer',
        'idperiodo' => 'integer',
        'idefector' => 'integer',
        'idservicio' => 'integer',
        'idtipo_guardia' => 'integer',
        'idtipo_campania' => 'integer',
        'idtipo_formulario' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha' => 'required',
        'fuera_termino' => 'required',
        'cantidad_lv' => 'required',
        'cantidad_sdf' => 'required',
        'cantidad_novedad_lv' => 'required',
        'cantidad_novedad_sdf' => 'required',
        'idperiodo' => 'required',
        'idefector' => 'required',
        'idservicio' => 'required',
        'idtipo_guardia' => 'required',
        'idtipo_campania' => 'required',
        'idtipo_formulario' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efector()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'idefector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function servicio()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'idservicio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCampania()
    {
        return $this->belongsTo(\App\Models\TipoCampanium::class, 'idtipo_campania');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFormulario()
    {
        return $this->belongsTo(\App\Models\TipoFormulario::class, 'idtipo_formulario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoGuardia()
    {
        return $this->belongsTo(\App\Models\TipoGuardium::class, 'idtipo_guardia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function lineasGuardia()
    {
        return $this->hasMany(\App\Models\GuardiaLinea::class, 'idguardia', 'idguardia');
    }
}
