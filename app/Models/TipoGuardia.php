<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoGuardia
 * @package App\Models
 * @version June 7, 2020, 3:07 am -03
 *
 * @property \Illuminate\Database\Eloquent\Collection $tipoNivels
 * @property \Illuminate\Database\Eloquent\Collection $guardia
 * @property \Illuminate\Database\Eloquent\Collection $guardiaCupos
 * @property string $tipoguardia
 * @property string $created_by
 * @property string $updated_by
 * @property string $deleted_by
 */
class TipoGuardia extends Model
{
    public $table = 'tipo_guardia';
    protected $primaryKey = 'idtipo_guardia';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];



    public $fillable = [
        'tipoguardia',
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
        'idtipo_guardia' => 'integer',
        'tipoguardia' => 'string',
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
        'tipoguardia' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tipoNivels()
    {
        return $this->belongsToMany(\App\Models\TipoNivel::class, 'guardia_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function guardia()
    {
        return $this->hasMany(\App\Models\Guardium::class, 'idtipo_guardia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function guardiaCupos()
    {
        return $this->hasMany(\App\Models\GuardiaCupo::class, 'idtipo_guardia');
    }
}
