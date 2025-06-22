<?php

namespace App\Models;

use App\MasterModel;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoEntrevista
 * @package App\Models
 * @version January 9, 2020, 5:37 am UTC
 *
 * @property User createdBy
 * @property User updatedBy
 * @property User deletedBy
 * @property \Illuminate\Database\Eloquent\Collection evaluacionPsicotecnicas
 * @property string tipoentrevista
 * @property integer created_by
 * @property integer updated_by
 * @property integer deleted_by
 */
class TipoEntrevista extends MasterModel
{
    use SoftDeletes;

    public $table = 'tipo_entrevista';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    public $fillable = [
        'tipoentrevista',
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
        'idtipo_entrevista' => 'integer',
        'tipoentrevista' => 'string',
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
        'tipoentrevista' => 'required',
        'created_by' => 'required',
        'updated_by' => 'required',
        'created_at' => 'required',
        'updated_at' => 'required'
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
    public function evaluacionPsicotecnicas()
    {
        return $this->hasMany(\App\Models\EvaluacionPsicotecnica::class, 'idtipo_entrevista');
    }
}
