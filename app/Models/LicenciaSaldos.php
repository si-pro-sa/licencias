<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class LicenciaSaldos
 * @package App\Models
 * @version March 7, 2020, 2:20 pm UTC
 *
 * @property \App\Models\Licencia idlicencia
 * @property \App\Models\Agente idagente
 * @property integer año
 * @property integer mes
 * @property integer dias
 * @property integer saldoMensual
 * @property integer saldoAnual
 * @property integer idtipoLicencia
 */
class LicenciaSaldos extends Model
{
    use SoftDeletes;



    public $table = 'licencia_saldos';
    public $primaryKey = 'idLicenciaSaldos';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idlicencia',
        'idagente',
        'año',
        'mes',
        'dias',
        'saldoMensual',
        'saldoAnual',
        'idtipoLicencia'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idLicenciaSaldos' => 'integer',
        'idlicencia' => 'integer',
        'idagente' => 'integer',
        'año' => 'integer',
        'mes' => 'integer',
        'dias' => 'integer',
        'saldoMensual' => 'integer',
        'saldoAnual' => 'integer',
        'idtipoLicencia' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idlicencia' => 'required',
        'idagente' => 'required',
        'año' => 'required',
        'mes' => 'required',
        'dias' => 'required',
        'saldoMensual' => 'required',
        'saldoAnual' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function licencia()
    {
        return $this->belongsTo(\App\Models\Licencia::class, 'idlicencia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }
}
