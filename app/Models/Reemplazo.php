<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Reemplazo
 * @package App\Models
 * @version March 8, 2020, 9:03 pm -03
 *
 * @property \App\Models\Agente idagenteReemplazado
 * @property \App\Models\Agente idagenteReemplazante
 * @property \App\Models\Dependencium iddependencia
 * @property \App\Models\Periodo idperiodo
 * @property \App\Models\Puesto idpuestoReemplazante
 * @property \App\Models\TipoAgrupamiento idtipoAgrupamiento
 * @property \App\Models\TipoHorario idtipoHorario
 * @property \App\Models\TipoNovedad idtipoNovedad
 * @property \App\Models\TipoSolicitud idtipoSolicitud
 * @property \App\Models\TipoFuncion idtipoFuncion
 * @property \App\Models\TipoNivel idtipoNivelReemplazante
 * @property \App\Models\TipoNivel idtipoNivelReemplazado
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionReemplazos
 * @property \Illuminate\Database\Eloquent\Collection novedadReemplazos
 * @property integer idperiodo
 * @property integer idformulario
 * @property integer iddependencia
 * @property integer idagente_reemplazado
 * @property integer idagente_reemplazante
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property string fdesde
 * @property string fhasta
 * @property integer idtipo_nivel
 * @property integer idtipo_funcion
 * @property integer idtipo_agrupamiento
 * @property integer idtipo_novedad
 * @property boolean aprobado
 * @property boolean desaprobado
 * @property integer idusuario
 * @property integer iddependenciapadre
 * @property integer idtipo_horario
 * @property string horario
 * @property integer idpuesto_reemplazado
 * @property integer idpuesto_reemplazante
 * @property integer idtipo_solicitud
 * @property integer idreemplazo_padre
 * @property integer estado
 * @property boolean novedad
 * @property integer idtipo_nivel_reemplazado
 * @property integer idtipo_nivel_reemplazante
 */
class Reemplazo extends Model
{
    public $table = 'reemplazo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['fdesde', 'fhasta'];

    public $fillable = [
        'idperiodo',
        'idformulario',
        'iddependencia',
        'idagente_reemplazado',
        'idagente_reemplazante',
        'usuario',
        'operacion',
        'foperacion',
        'fdesde',
        'fhasta',
        'idtipo_nivel',
        'idtipo_funcion',
        'idtipo_agrupamiento',
        'idtipo_novedad',
        'aprobado',
        'desaprobado',
        'idusuario',
        'iddependenciapadre',
        'idtipo_horario',
        'horario',
        'idpuesto_reemplazado',
        'idpuesto_reemplazante',
        'idtipo_solicitud',
        'idreemplazo_padre',
        'estado',
        'novedad',
        'idtipo_nivel_reemplazado',
        'idtipo_nivel_reemplazante'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idreemplazo' => 'integer',
        'idperiodo' => 'integer',
        'idformulario' => 'integer',
        'iddependencia' => 'integer',
        'idagente_reemplazado' => 'integer',
        'idagente_reemplazante' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime',
        'fdesde' => 'date',
        'fhasta' => 'date',
        'idtipo_nivel' => 'integer',
        'idtipo_funcion' => 'integer',
        'idtipo_agrupamiento' => 'integer',
        'idtipo_novedad' => 'integer',
        'aprobado' => 'boolean',
        'desaprobado' => 'boolean',
        'idusuario' => 'integer',
        'iddependenciapadre' => 'integer',
        'idtipo_horario' => 'integer',
        'horario' => 'string',
        'idpuesto_reemplazado' => 'integer',
        'idpuesto_reemplazante' => 'integer',
        'idtipo_solicitud' => 'integer',
        'idreemplazo_padre' => 'integer',
        'estado' => 'integer',
        'novedad' => 'boolean',
        'idtipo_nivel_reemplazado' => 'integer',
        'idtipo_nivel_reemplazante' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idperiodo' => 'required',
        'idformulario' => 'required',
        'iddependencia' => 'required',
        'idagente_reemplazado' => 'required',
        'idagente_reemplazante' => 'required',
        'usuario' => 'required',
        'operacion' => 'required',
        'foperacion' => 'required',
        'fdesde' => 'required',
        'fhasta' => 'required',
        'idtipo_nivel' => 'required',
        'idtipo_funcion' => 'required',
        'idtipo_agrupamiento' => 'required',
        'idusuario' => 'required',
        'iddependenciapadre' => 'required',
        'idpuesto_reemplazado' => 'required',
        'idpuesto_reemplazante' => 'required',
        'idtipo_solicitud' => 'required',
        'estado' => 'required',
        'novedad' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idagenteReemplazado()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente_reemplazado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idagenteReemplazante()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente_reemplazante');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function iddependencia()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'iddependencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idperiodo()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idpuestoReemplazante()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto_reemplazante');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoAgrupamiento()
    {
        return $this->belongsTo(\App\Models\TipoAgrupamiento::class, 'idtipo_agrupamiento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoHorario()
    {
        return $this->belongsTo(\App\Models\TipoHorario::class, 'idtipo_horario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoNovedad()
    {
        return $this->belongsTo(\App\Models\TipoNovedad::class, 'idtipo_novedad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoSolicitud()
    {
        return $this->belongsTo(\App\Models\TipoSolicitud::class, 'idtipo_solicitud');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoFuncion::class, 'idtipo_funcion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoNivelReemplazante()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel_reemplazante');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoNivelReemplazado()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel_reemplazado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionReemplazos()
    {
        return $this->hasMany(\App\Models\EfectivaPrestacionReemplazo::class, 'idreemplazo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function novedadReemplazos()
    {
        return $this->hasMany(\App\Models\NovedadReemplazo::class, 'idreemplazo');
    }
}
