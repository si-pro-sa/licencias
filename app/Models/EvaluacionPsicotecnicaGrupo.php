<?php

namespace App\Models;

use App\MasterModel;
use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EvaluacionPsicotecnicaGrupo
 * @package App\Models
 * @version February 3, 2020, 4:01 pm UTC
 *
 * @property User createdBy
 * @property User updatedBy
 * @property User deletedBy
 * @property \Illuminate\Database\Eloquent\Collection assessmentCenters
 * @property string evaluacion_psicotecnica_grupo
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 */
class EvaluacionPsicotecnicaGrupo extends MasterModel
{
    use SoftDeletes;

    public $table = 'evaluacion_psicotecnica_grupo';
    protected $primaryKey = 'idevaluacion_psicotecnica_grupo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'evaluacion_psicotecnica_grupo',
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
        'idevaluacion_psicotecnica_grupo' => 'integer',
        'evaluacion_psicotecnica_grupo' => 'string',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'evaluacion_psicotecnica_grupo' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function assessmentCenters()
    {
        return $this->hasMany(AssessmentCenter::class, 'idevaluacion_psicotecnica_grupo');
    }
}
