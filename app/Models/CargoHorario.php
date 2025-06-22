<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoHorario
 * @package App\Models
 * @version November 3, 2019, 11:57 pm -03
 *
 * @property \App\Models\Cargo idcargo
 * @property \App\Models\TipoDium idtipoDia
 * @property \App\Models\Dependencia idefector
 * @property \App\Models\Dependencia idservicio
 * @property integer idcargo
 * @property integer idtipo_dia
 * @property integer idefector
 * @property integer idservicio
 * @property time hora_desde
 * @property time hora_hasta
 */
class CargoHorario extends Model
{
    use SoftDeletes;

    public $table = 'cargo_horario';
    public $primaryKey = 'idcargo_horario';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo',
        'idtipo_dia',
        'idefector',
        'idservicio',
        'hora_desde',
        'hora_hasta'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_horario' => 'integer',
        'idcargo' => 'integer',
        'idtipo_dia' => 'integer',
        'idefector' => 'integer',
        'idservicio' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo' => 'required',
        'idtipo_dia' => 'required',
        'idefector' => 'required',
        'idservicio' => 'required',
        'hora_desde' => 'required',
        'hora_hasta' => 'required'
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
    public function tipoDia()
    {
        return $this->belongsTo(\App\Models\TipoDium::class, 'idtipo_dia');
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
}
