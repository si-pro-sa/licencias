<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EfectivaPrestacionObservacionCargo
 * @package App\Models
 * @version April 16, 2020, 1:34 pm -03
 *
 * @property \App\Models\EfectivaPrestacionCargo idefectivaPrestacionCargo
 * @property \App\Models\TipoObservacionNovedad idtipoObservacionNovedad
 * @property \App\Models\Sistema.usuario createdBy
 * @property \App\Models\Sistema.usuario updatedBy
 * @property \App\Models\Sistema.usuario deletedBy
 * @property integer idefectiva_prestacion_cargo
 * @property integer idtipo_observacion_novedad
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 */
class EfectivaPrestacionObservacionCargo extends Model
{
    use SoftDeletes;

    public $table = 'efectiva_prestacion_obs_cargo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $primaryKey = 'idep_observacion_cargo';

    public $fillable = [
        'idefectiva_prestacion_cargo',
        'idtipo_observacion_novedad',
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
        'idefectiva_prestacion_obs_cargo' => 'integer',
        'idefectiva_prestacion_cargo' => 'integer',
        'idtipo_observacion_novedad' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idefectiva_prestacion_cargo' => 'required',
        'idtipo_observacion_novedad' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idefectivaPrestacionCargo()
    {
        return $this->belongsTo(\App\Models\EfectivaPrestacionCargo::class, 'idefectiva_prestacion_cargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoObservacionNovedad()
    {
        return $this->belongsTo(\App\Models\TipoObservacionNovedad::class, 'idtipo_observacion_novedad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Sistema . usuario::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Sistema . usuario::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deletedBy()
    {
        return $this->belongsTo(\App\Models\Sistema . usuario::class, 'deleted_by');
    }
}
