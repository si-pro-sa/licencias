<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class PuestoAdicional
 * @package App\Models
 * @version October 28, 2019, 8:09 pm -03
 *
 * @property \App\Models\Puesto idpuesto
 * @property \App\Models\Dependencia iddependencia
 * @property integer idpuesto
 * @property integer iddependencia
 */
class PuestoAdicional extends Model
{
    public $table = 'puesto_adicional';
    protected $primaryKey = 'idpuesto_adicional';
    public $timestamps = false;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'idpuesto',
        'iddependencia',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idpuesto_adicional' => 'integer',
        'idpuesto' => 'integer',
        'iddependencia' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idpuesto' => 'required',
        'iddependencia' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     **/
    public function horarios()
    {
        return $this->morphMany(\App\Models\HorarioPuesto::class, 'puesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependencia()
    {
        return $this->belongsTo(\App\Models\Dependencia::class, 'iddependencia');
    }
}
