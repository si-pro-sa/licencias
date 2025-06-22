<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoCronograma
 * @package App\Models
 * @version July 17, 2020, 2:52 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection $periodos
 * @property string $tipocronograma
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class TipoCronograma extends Model
{
    public $table = 'tipo_cronograma';
    protected $primaryKey = 'idtipo_cronograma';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];



    public $fillable = [
        'tipocronograma',
        'usuario',
        'operacion',
        'foperacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_cronograma' => 'integer',
        'tipocronograma' => 'string',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipocronograma' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function periodos()
    {
        return $this->belongsToMany(\App\Models\Periodo::class, 'cronograma');
    }
}
