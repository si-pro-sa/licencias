<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoNovedad
 * @package App\Models
 * @version June 7, 2020, 3:44 am -03
 *
 * @property \App\Models\TipoEstado $idtipoEstado
 * @property \Illuminate\Database\Eloquent\Collection $tipoFormularios
 * @property \Illuminate\Database\Eloquent\Collection $reemplazos
 * @property string $tiponovedad
 * @property integer $codigo
 * @property string $abreviatura
 * @property boolean $goce_haberes
 * @property boolean $afecta_presentismo
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 * @property integer $idtipo_estado
 */
class TipoNovedad extends Model
{
    public $table = 'tipo_novedad';
    protected $primaryKey = 'idtipo_novedad';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = [];

    public $fillable = [
        'tiponovedad',
        'codigo',
        'abreviatura',
        'goce_haberes',
        'afecta_presentismo',
        'usuario',
        'operacion',
        'foperacion',
        'idtipo_estado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_novedad' => 'integer',
        'tiponovedad' => 'string',
        'codigo' => 'integer',
        'abreviatura' => 'string',
        'goce_haberes' => 'boolean',
        'afecta_presentismo' => 'boolean',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime',
        'idtipo_estado' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idtipoEstado()
    {
        return $this->belongsTo(\App\Models\TipoEstado::class, 'idtipo_estado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tipoFormularios()
    {
        return $this->belongsToMany(\App\Models\TipoFormulario::class, 'formulariotiponovedad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reemplazos()
    {
        return $this->hasMany(\App\Models\Reemplazo::class, 'idtipo_novedad');
    }
}
