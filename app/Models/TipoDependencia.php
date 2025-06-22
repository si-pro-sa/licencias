<?php
namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoDependencia
 * @package App\Models
 * @version October 28, 2019, 8:10 pm -03
 *
 * @property string tipodia
 */
class TipoDependencia extends Model
{
    public $table = 'tipo_dependencia';
    protected $primaryKey = 'idtipo_dependencia';

    public $fillable = [
        'tipodependencia'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipo_dependencia' => 'integer',
        'tipodependencia' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipodependencia' => 'required'
    ];
}
