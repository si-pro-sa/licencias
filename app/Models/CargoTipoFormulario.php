<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CargoTipoFormulario
 * @package App\Models
 * @version December 12, 2019, 9:39 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstados
 * @property string cargotipo_formulario
 */
class CargoTipoFormulario extends Model
{

    public $table = 'cargo_tipo_formulario';
    protected $primaryKey = 'idcargo_tipo_formulario';

    public $fillable = [
        'cargotipo_formulario'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_formulario' => 'integer',
        'cargotipo_formulario' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cargotipo_formulario' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstados()
    {
        return $this->hasMany(\App\Models\CargoCambioEstado::class, 'idcargo_tipo_formulario');
    }
}
