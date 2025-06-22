<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoTipoVisado
 * @package App\Models
 * @version October 28, 2019, 8:05 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstados
 * @property string cargotipo_visado
 */
class CargoTipoVisado extends Model
{
    public $table = 'cargo_tipo_visado';
    public $primaryKey = 'idcargo_tipo_visado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'cargotipo_visado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_visado' => 'integer',
        'cargotipo_visado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cargotipo_visado' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstados()
    {
        return $this->hasMany(\App\Models\CargoCambioEstado::class, 'idcargo_tipo_visado');
    }
}
