<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cargo
 * @package App\Models
 * @version October 31, 2019, 3:55 pm -03
 *
 * @property \App\Models\RecomendacionCandidato idrecomendacionCandidato
 * @property \App\Models\Dependencia idefector
 * @property \App\Models\Dependencia idservicio
 * @property \App\Models\TipoFuncion idtipoFuncion
 * @property \App\Models\TipoNivel idtipoNivel
 * @property \App\Models\TipoAgrupamiento idtipoAgrupamiento
 * @property \App\Models\Titulo idtitulo
 * @property \App\Models\TipoEspecialidad idtipoEspecialidad
 * @property \Illuminate\Database\Eloquent\Collection cargoBajaRelacions
 * @property \Illuminate\Database\Eloquent\Collection cargoBajaRelacion4s
 * @property \Illuminate\Database\Eloquent\Collection cargoDevolucionRelacions
 * @property \Illuminate\Database\Eloquent\Collection cargoDevolucionRelacion5s
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstados
 * @property \Illuminate\Database\Eloquent\Collection cargoReemplazados
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionCargos
 * @property integer idrecomendacion_candidato
 * @property integer idefector
 * @property integer idservicio
 * @property integer idtipo_funcion
 * @property integer idtipo_nivel
 * @property integer idtipo_agrupamiento
 * @property integer idtitulo
 * @property integer idtipo_especialidad
 * @property string produccion_esperada
 * @property string razones_brecha
 * @property boolean cobertura_provisoria
 * @property boolean foto_carnet
 * @property boolean diagrama_servicio
 * @property boolean resolucion_ministerial
 * @property boolean formulario_baja_cobertura
 * @property boolean formulario_resumen_servicio
 * @property boolean formulario_devolucion
 * @property boolean titulo_academico
 * @property boolean copia_dni
 * @property boolean copia_cuil
 * @property boolean resumen_evaluacion
 *
 */
class Cargo extends Model
{
    use SoftDeletes;

    public $table = 'cargo';
    public $connection = 'pgsql_public';
    public $primaryKey = 'idcargo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'idrecomendacion_candidato',
        'idefector',
        'idservicio',
        'idtipo_funcion',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idtitulo',
        'idtipo_especialidad',
        'produccion_esperada',
        'razones_brecha',
        'cobertura_provisoria',
        'foto_carnet',
        'diagrama_servicio',
        'resolucion_ministerial',
        'formulario_baja_cobertura',
        'formulario_resumen_servicio',
        'formulario_devolucion',
        'titulo_academico',
        'copia_dni',
        'copia_cuil',
        'resumen_evaluacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo' => 'integer',
        'idrecomendacion_candidato' => 'integer',
        'idefector' => 'integer',
        'idservicio' => 'integer',
        'idtipo_funcion' => 'integer',
        'idtipo_nivel' => 'integer',
        'idtipo_agrupamiento' => 'integer',
        'idtitulo' => 'integer',
        'idtipo_especialidad' => 'integer',
        'produccion_esperada' => 'string',
        'razones_brecha' => 'string',
        'cobertura_provisoria' => 'boolean',
        'foto_carnet' => 'boolean',
        'diagrama_servicio' => 'boolean',
        'resolucion_ministerial' => 'boolean',
        'formulario_baja_cobertura' => 'boolean',
        'formulario_resumen_servicio' => 'boolean',
        'formulario_devolucion' => 'boolean',
        'titulo_academico' => 'boolean',
        'copia_dni' => 'boolean',
        'copia_cuil' => 'boolean',
        'resumen_evaluacion' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idrecomendacion_candidato' => 'required',
        'idefector' => 'required',
        'idservicio' => 'required',
        'idtipo_funcion' => 'required',
        'idtipo_nivel' => 'required',
        'idtipo_agrupamiento' => 'required',
        'idtitulo' => 'required',
        'idtipo_especialidad' => 'required',
        'produccion_esperada' => 'required',
        'razones_brecha' => 'required',
        'cobertura_provisoria' => 'required',
        'foto_carnet' => 'required',
        'diagrama_servicio' => 'required',
        'resolucion_ministerial' => 'required',
        'formulario_baja_cobertura' => 'required',
        'formulario_resumen_servicio' => 'required',
        'formulario_devolucion' => 'required',
        'titulo_academico' => 'required',
        'copia_dni' => 'required',
        'copia_cuil' => 'required',
        'resumen_evaluacion' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function recomendacionCandidato()
    {
        return $this->belongsTo(\App\Models\RecomendacionCandidato::class, 'idrecomendacion_candidato');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efector()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'idefector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function servicio()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'idservicio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoFuncion::class, 'idtipo_funcion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoNivel()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoAgrupamiento()
    {
        return $this->belongsTo(\App\Models\TipoAgrupamiento::class, 'idtipo_agrupamiento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function titulo()
    {
        return $this->belongsTo(\App\Models\Titulo::class, 'idtitulo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoEspecialidad()
    {
        return $this->belongsTo(\App\Models\TipoEspecialidad::class, 'idtipo_especialidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoBajaRelacions()
    {
        return $this->hasMany(\App\Models\CargoBajaRelacion::class, 'idcargo_baja');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoBajaRelacion4s()
    {
        return $this->hasMany(\App\Models\CargoBajaRelacion::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoDevolucionRelacions()
    {
        return $this->hasMany(\App\Models\CargoDevolucionRelacion::class, 'idcargo_devuelto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoDevolucionRelacion5s()
    {
        return $this->hasMany(\App\Models\CargoDevolucionRelacion::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstados()
    {
        return $this->hasMany(\App\Models\CargoCambioEstado::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function cargoReemplazado()
    {
        return $this->hasOne(\App\Models\CargoReemplazado::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivasPrestacionesCargo()
    {
        return $this->hasMany(\App\Models\EfectivaPrestacionCargo::class, 'idcargo');
    }

    public function getCreatedAtFormattedAttribute() {
        return $this->created_at->format('d/m/y');
    }

    public function getCoberturaProvisoriaFormattedAttribute() {
        return $this->cobertura_provisoria ? 'CP' : 'CV';
    }
}
