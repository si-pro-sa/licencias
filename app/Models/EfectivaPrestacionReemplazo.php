<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class EfectivaPrestacionReemplazo
 * @package App\Models
 * @version March 8, 2021, 11:33 am -03
 *
 * @property \App\Models\Periodo $idperiodo
 * @property \App\Models\Reemplazo $idreemplazo
 * @property \App\Models\Dependencium $idefector
 * @property \App\Models\Dependencium $idservicio
 * @property \Illuminate\Database\Eloquent\Collection $novedadReemplazos
 * @property \Illuminate\Database\Eloquent\Collection $periodoEfectivaPrestacions
 * @property \Illuminate\Database\Eloquent\Collection $auditoria.visadoEpReemplazos
 * @property integer $total_dias
 * @property number $costo_total
 * @property integer $idefector
 * @property boolean $primera_vez
 * @property integer $idperiodo
 * @property integer $idreemplazo
 * @property string $usuario
 * @property string|\Carbon\Carbon $foperacion
 * @property string $operacion
 * @property boolean $enviada
 * @property integer $idservicio
 * @property boolean $observado
 */
class EfectivaPrestacionReemplazo extends Model
{
    public $table = 'efectiva_prestacion_reemplazo';
    protected $dates = [];
    public $fillable = [
        'total_dias',
        'costo_total',
        'idefector',
        'primera_vez',
        'idperiodo',
        'idreemplazo',
        'usuario',
        'foperacion',
        'operacion',
        'enviada',
        'idservicio',
        'observado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idefectivaprestacion_reemplazo' => 'integer',
        'total_dias' => 'integer',
        'costo_total' => 'float',
        'idefector' => 'integer',
        'primera_vez' => 'boolean',
        'idperiodo' => 'integer',
        'idreemplazo' => 'integer',
        'usuario' => 'string',
        'foperacion' => 'datetime',
        'operacion' => 'string',
        'enviada' => 'boolean',
        'idservicio' => 'integer',
        'observado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'total_dias' => 'required|integer',
        'costo_total' => 'required|numeric',
        'idefector' => 'required|integer',
        'primera_vez' => 'required|boolean',
        'idperiodo' => 'required|integer',
        'idreemplazo' => 'required|integer',
        'usuario' => 'nullable|string|max:255',
        'foperacion' => 'nullable',
        'operacion' => 'required|string|max:1',
        'enviada' => 'nullable|boolean',
        'idservicio' => 'nullable|integer',
        'observado' => 'nullable|boolean'
    ];

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
    public function reemplazo()
    {
        return $this->belongsTo(\App\Models\Reemplazo::class, 'idreemplazo');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function novedadReemplazos()
    {
        return $this->hasMany(\App\Models\NovedadReemplazo::class, 'idefectivaprestacion_reemplazo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function periodoEfectivaPrestacions()
    {
        return $this->hasMany(\App\Models\PeriodoEfectivaPrestacion::class, 'idefectiva_prestacion_reemplazo');
    }
}
