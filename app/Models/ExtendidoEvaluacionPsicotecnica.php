<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ExtendidoEvaluacionPsicotecnica
 * @package App\Models
 *
 * @property integer idextendido_evaluacion_psicotecnica
 * @property string presentacion
 * @property string aspectos_cognitivos
 * @property string modalidad_relacional
 * @property string motivacion
 * @property integer idevaluacion_psicotecnica
 *
 */

class ExtendidoEvaluacionPsicotecnica extends Model
{
    use SoftDeletes;

    public $table = 'extendido_evaluacion_psicotecnica';

    public $primaryKey = 'idextendido_evaluacion_psicotecnica';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'presentacion',
        'aspectos_cognitivos',
        'modalidad_relacional',
        'motivacion',
        'idevaluacion_psicotecnica',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'presentacion' => 'text',
        'aspectos_cognitivos' => 'text',
        'modalidad_relacional' => 'text',
        'motivacion' => 'text',
        'idevaluacion_psicotecnica' => 'integer',
    ];

    public function psicotecnica()
    {
        return $this->belongsTo(EvaluacionPsicotecnica::class, 'idevaluacion_psicotecnica');
    }
}
