<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EvaluacionPsicotecnica
 * @package App\Models
 *
 * @property integer idpsicoevaluador
 * @property integer idagente
 * @property integer matricula
 * @property string firma
 *
 */

class PsicoEvaluador extends Model
{
    use SoftDeletes;

    public $table = 'psicoevaluador';

    public $primaryKey = 'idpsicoevaluador';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'idagente',
        'matricula',
        'firma',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idagente' => 'integer',
        'matricula' => 'integer',
        'firma' => 'string',
    ];


    public function agente()
    {
        return $this->belongsTo(Agente::class,'idagente');
    }

    public function psicoTecnicos()
    {
        return $this->hasMany(EvaluacionPsicotecnica::class,'idpsicoevaluador');
    }

}
