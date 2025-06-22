<?php

namespace App\Models;

use App\MasterModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EfectivaPrestacionCargo
 * @package App\Models
 * @version April 16, 2020, 1:30 pm -03
 *
 * @property \App\Models\Cargo idcargo
 * @property \App\Models\CargoTipoVisadoEp idcargoTipoVisadoEp
 * @property \App\Models\Periodo idperiodo
 * @property \App\Models\Sistema.usuario createdBy
 * @property \App\Models\Sistema.usuario updatedBy
 * @property \App\Models\Sistema.usuario deletedBy
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionObsCargos
 * @property integer idcargo
 * @property integer idcargo_tipo_visado_ep
 * @property integer dias
 * @property integer idperiodo
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 */
class EfectivaPrestacionCargo extends MasterModel
{
    use SoftDeletes;

    public $table = 'efectiva_prestacion_cargo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    protected $primaryKey = 'idep_cargo';

    public $fillable = [
        'idcargo',
        'idcargo_tipo_visado_ep',
        'dias',
        'idperiodo',
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
        'idep_cargo' => 'integer',
        'idcargo' => 'integer',
        'idcargo_tipo_visado_ep' => 'integer',
        'dias' => 'integer',
        'idperiodo' => 'integer',
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
        'idcargo' => 'required',
        'idcargo_tipo_visado_ep' => 'required',
        'dias' => 'required',
        'idperiodo' => 'required'
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
    public function cargoTipoVisadoEp()
    {
        return $this->belongsTo(\App\Models\CargoTipoVisadoEp::class, 'idcargo_tipo_visado_ep');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionObsCargo()
    {
        return $this->hasMany(\App\Models\EfectivaPrestacionObsCargo::class, 'idep_cargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionCargoPeriodos()
    {
        return $this->hasMany(\App\Models\EfectivaPrestacionCargoPeriodo::class, 'idep_cargo');
    }
}
