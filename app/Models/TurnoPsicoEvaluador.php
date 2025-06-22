<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TurnoPsicoEvaluador
 * @package App\Models
 *
 * @property integer idturno_psicoevaluador
 * @property date fecha
 * @property time hora
 * @property boolean asistio
 * @property integer candidato_id
 * @property string candidato_type
 * @property integer idpsicoevaluador
 *
 */
class TurnoPsicoEvaluador extends Model
{
    use SoftDeletes;

    public $table = 'turno_psicoevaluador';

    public $primaryKey = 'idturno_psicoevaluador';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'candidato_id',
        'candidato_type',
        'idpsicoevaluador'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'time',
        'asistio' => 'boolean',
        'candidato_id' => 'integer',
        'candidato_type' => 'string',
        'idpsicoevaluador' => 'integer'
    ];

    public function psicoevaluador()
    {
        return $this->belongsTo(PsicoEvaluador::class,'idpsicoevaluador');
    }

    public function candidato()
    {
        return $this->morphTo();
    }

    /**
     * Reprograma un turno ya creado
     * @param $fecha
     * @param $hora
     * @return bool
     */
    public function reprogramar($fecha, $hora)
    {
        $this->fecha = $fecha;
        $this->hora = $hora;

        if($this->save()) {
            return true;
        }
        return false;
    }
}
