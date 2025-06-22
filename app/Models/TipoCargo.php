<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoCargo
 * @package App\Models
 * @version May 11, 2020, 10:50 am -03
 *
 * @property \Illuminate\Database\Eloquent\Collection cargos
 * @property string tipocargo
 * @property string tipocargo_corto
 */
class TipoCargo extends Model
{
    public $table = 'tipo_cargo';
    protected $primaryKey = 'idtipo_cargo';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public $fillable = [
        'tipocargo',
        'tipocargo_corto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_cargo' => 'integer',
        'tipocargo' => 'string',
        'tipocargo_corto' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipocargo' => 'required',
        'tipocargo_corto' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargos()
    {
        return $this->hasMany(\App\Models\Cargo::class, 'idtipo_cargo');
    }
}
