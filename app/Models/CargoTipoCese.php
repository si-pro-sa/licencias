<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CargoTipoCese
 * @package App\Models
 * @version June 13, 2020, 4:10 am -03
 *
 * @property \App\Models\TipoCargo $tipoCargo
 * @property \App\Models\TipoCese $tipoCese
 * @property integer $idtipo_cargo
 * @property integer $idtipo_cese
 * @property boolean $agente_reemplazado
 */
class CargoTipoCese extends Model
{
    public $table = 'cargo_tipo_cese';
    protected $primaryKey = 'idcargo_tipo_cese';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = [];

    public $fillable = [
        'idtipo_cargo',
        'idtipo_cese',
        'agente_reemplazado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_cese' => 'integer',
        'idtipo_cargo' => 'integer',
        'idtipo_cese' => 'integer',
        'agente_reemplazado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idtipo_cargo' => ['required', ],
        'idtipo_cese' => 'required',
        'agente_reemplazado' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCargo()
    {
        return $this->belongsTo(\App\Models\TipoCargo::class, 'idtipo_cargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCese()
    {
        return $this->belongsTo(\App\Models\TipoCese::class, 'idtipo_cese');
    }
}
