<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class TipoSexo
 * @package App\Models
 * @version December 10, 2019, 3:05 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection localidads
 * @property string tiposexo
 * @property string abreviatura
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class TipoSexo extends Model
{
    public $table = 'tipo_sexo';
    protected $primaryKey = 'idtipo_sexo';

    // const UPDATED_AT = 'foperacion';

    // protected $dates = ['foperacion'];

    public $fillable = [
        'tiposexo',
        'abreviatura',
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
        'idtipo_sexo' => 'integer',
        'tiposexo' => 'string',
        'abreviatura' => 'string',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function localidades()
    {
        return $this->belongsToMany(\App\Models\Localidad::class, 'agente');
    }
}
