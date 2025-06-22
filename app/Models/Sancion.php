<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Sancion
 * @package App\Models
 * @version November 4, 2019, 12:26 am -03
 *
 * @property \App\Models\Agente idagente
 * @property integer idagente
 * @property string resolucion
 * @property string reseña
 * @property string conclusion
 * @property string acuerdo
 * @property string expediente
 * @property string fecha_inicio
 * @property string fecha_final
 */
class Sancion extends Model
{
    use SoftDeletes;

    public $table = 'sanciones';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'idsancion';
    protected $dates = ['deleted_at'];



    public $fillable = [
        'idagente',
        'resolucion',
        'reseña',
        'conclusion',
        'acuerdo',
        'expediente',
        'fecha_inicio',
        'fecha_final',
        'created_at' => 'date',
        'idusuario'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idsancion' => 'integer',
        'idagente' => 'integer',
        'resolucion' => 'string',
        'reseña' => 'string',
        'conclusion' => 'string',
        'acuerdo' => 'string',
        'expediente' => 'string',
        'fecha_inicio' => 'date',
        'fecha_final' => 'date',
        'created_at' => 'date',
        'idusuario' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idagente' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idagente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idusuario()
    {
        return $this->belongsTo(\App\User::class, 'idusuario');
    }
}
