<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoTipoVisadoEP
 * @package App\Models
 * @version October 28, 2019, 8:05 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionCargos
 * @property string cargo_tipo_visado_ep
 */
class CargoTipoVisadoEP extends Model
{
    use SoftDeletes;

    public $table = 'cargo_tipo_visado_ep';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'cargo_tipo_visado_ep'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_tipo_visado_ep' => 'integer',
        'cargo_tipo_visado_ep' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cargo_tipo_visado_ep' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionCargos()
    {
        return $this->hasMany(\App\Models\EfectivaPrestacionCargo::class, 'idcargo_tipo_visado_ep');
    }
}
