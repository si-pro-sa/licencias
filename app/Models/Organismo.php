<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Organismo.
 *
 * @version July 3, 2020, 7:37 pm UTC
 *
 * @property int idorganismo
 * @property string organismo
 * @property int iddependencia
 */
class Organismo extends Model
{
    public $table = 'organismo';

    protected $primaryKey = 'idorganismo';

    public $timestamps = false;

    public $fillable = [
        'organismo',
        'iddependencia',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idorganismo' => 'integer',
        'organismo' => 'string',
        'iddependencia' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'organismo' => 'required',
        'iddependencia' => 'required|numeric',
    ];

    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'iddependencia');
    }
}
