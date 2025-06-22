<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class LdVisadoCambioEstado
 * @package App\Models
 * @version January 29, 2021, 11:08 am -03
 *
 * @property \App\Models\LdCambioEstado $idldCambioEstado
 * @property integer $idld_cambio_estado
 * @property string $usuario
 * @property string $operacion
 * @property string|\Carbon\Carbon $foperacion
 */
class LdVisadoCambioEstado extends Model
{
    use HasFactory;

    public $table = 'ld_visado_cambio_estado';
    protected $primaryKey = 'idld_visado_cambio_estado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = [];



    public $fillable = [
        'idld_cambio_estado',
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
        'idld_visado_cambio_estado' => 'integer',
        'idld_cambio_estado' => 'integer',
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
        'idld_cambio_estado' => 'required|integer',
        'usuario' => 'nullable|string|max:191',
        'operacion' => 'nullable|string|max:1',
        'foperacion' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function lDCambioEstado()
    {
        return $this->belongsTo(\App\Models\LdCambioEstado::class, 'idld_cambio_estado');
    }
}
