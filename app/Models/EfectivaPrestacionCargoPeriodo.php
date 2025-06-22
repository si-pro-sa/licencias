<?php

namespace App\Models;

use App\Models\MasterModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EfectivaPrestacionCargoPeriodo
 * @package App\Models
 * @version April 15, 2020, 10:19 am -03
 *
 * @property \App\Models\EfectivaPrestacionCargo efectivaPrestacionCargo
 * @property \App\Models\Dependencia efector
 * @property integer idep_cargo
 * @property integer idefector
 * @property string fecha_desde
 * @property string fecha_hasta
 */
class EfectivaPrestacionCargoPeriodo extends Model
{
    use SoftDeletes;

    public $table = 'efectiva_prestacion_cargo_periodo';


    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'fecha_desde', 'fecha_hasta'];


    public $fillable = [
        'idep_cargo',
        'idefector',
        'fecha_desde',
        'fecha_hasta'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idep_cargo_periodo' => 'integer',
        'idep_cargo' => 'integer',
        'idefector' => 'integer',
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idep_cargo' => 'required|numeric',
        'idefector' => 'required|numeric',
        'fecha_desde' => 'required|date',
        'fecha_hasta' => 'required|date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efectivaPrestacionCargo()
    {
        return $this->belongsTo(\App\Models\EfectivaPrestacionCargo::class, 'idep_cargo', 'idep_cargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efector()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'idefector', 'iddependencia');
    }
}
