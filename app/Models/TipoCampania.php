<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoCampania
 * @package App\Models
 * @version November 6, 2019, 11:29 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection guardiaCupos
 * @property \Illuminate\Database\Eloquent\Collection guardia
 * @property string tipocampania
 * @property string created_by
 * @property string updated_by
 * @property string deleted_by
 */
class TipoCampania extends Model
{
    use SoftDeletes;

    public $table = 'tipo_campania';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'tipocampania',
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
        'idtipo_campania' => 'integer',
        'tipocampania' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipocampania' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function guardiaCupos()
    {
        return $this->hasMany(\App\Models\GuardiaCupo::class, 'idtipo_campania');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function guardia()
    {
        return $this->hasMany(\App\Models\Guardium::class, 'idtipo_campania');
    }
}
