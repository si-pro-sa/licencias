<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CapacitacionAgente
 * @package App\Models
 * @version September 14, 2021, 5:26 pm UTC
 *
 * @property \App\Models\Agente "idagente"
 * @property integer idCapacitacion
 * @property integer idAgente
 */
class CapacitacionAgente extends Model
{
    use SoftDeletes;

    public $table = 'capacitacion_agente';
    protected $primaryKey = 'idCapacitacionAgente';
    protected $connection = 'pgsql_public';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idCapacitacion',
        'idAgente'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idCapacitacionAgente' => 'integer',
        'idCapacitacion' => 'integer',
        'idAgente' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idCapacitacion' => 'required',
        'idAgente' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idAgente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function capacitacion()
    {
        return $this->belongsTo(\App\Models\Capacitacion::class, 'idCapacitacion');
    }
}
