<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class CargoTipoFirma
 * @package App\Models
 * @version January 28, 2020, 10:42 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstados
 * @property string cargotipo_firma
 */
class CargoTipoFirma extends Model
{

    public $table = 'cargo_tipo_firma';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'idcargo_tipo_firma';


    protected $dates = [];


    public $fillable = [
        'cargotipo_firma'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_firma' => 'integer',
        'cargotipo_firma' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cargotipo_firma' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstados()
    {
        return $this->hasMany(\App\Models\CargoCambioEstado::class, 'idcargo_tipo_firma');
    }
}
