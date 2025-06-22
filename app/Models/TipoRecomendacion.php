<?php

namespace App\Models;

use App\MasterModel;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoRecomendacion
 * @package App\Models
 * @version January 7, 2020, 3:04 pm UTC
 *
 * @property integer idtipo_recomendacion
 * @property User createdBy
 * @property User deletedBy
 * @property User updatedBy
 * @property \Illuminate\Database\Eloquent\Collection evaluacionPsicotecnicas
 * @property string tiporecomendacion
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 */
class TipoRecomendacion extends MasterModel
{
    use SoftDeletes;

    public $table = 'tipo_recomendacion';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public $fillable = [
        'tiporecomendacion',
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
        'idtipo_recomendacion' => 'integer',
        'tiporecomendacion' => 'string',
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
        'tiporecomendacion' => 'required'
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
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionPsicotecnicas()
    {
        return $this->hasMany(\App\Models\EvaluacionPsicotecnica::class, 'idtipo_recomendacion');
    }
}
