<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoHorario
 * @package App\Models
 * @version May 31, 2020, 4:15 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection $reemplazos
 * @property \Illuminate\Database\Eloquent\Collection $horarioPuestos
 * @property string $tipohorario
 */
class TipoHorario extends Model
{
    public $table = 'tipo_horario';
    protected $primaryKey = 'idtipo_horario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];


    public $fillable = [
        'tipohorario'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_horario' => 'integer',
        'tipohorario' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reemplazos()
    {
        return $this->hasMany(\App\Models\Reemplazo::class, 'idtipo_horario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function horarioPuestos()
    {
        return $this->hasMany(\App\Models\HorarioPuesto::class, 'idtipo_horario');
    }
}
