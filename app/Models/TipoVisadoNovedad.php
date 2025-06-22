<?php

namespace App\Models;

use App\MasterModel;

/**
 * Class TipoVisadoNovedad
 * @package App\Models
 * @version September, 2022, 9:15 pm -03
 *
 * @property \Illuminate\Database\Eloquent\Collection novedadCargos
 * @property string tipovisado
 */
class TipoVisadoNovedad extends MasterModel
{
    public $table = 'tipo_visado_novedad';
    protected $primaryKey = 'idtipovisadonovedad';


    public $fillable = [
        'tipovisado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idtipovisadonovedad' => 'integer',
        'tipovisado' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tipovisado' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function novedadCargos()
    {
        return $this->hasMany(\App\Models\NovedadCargo::class, 'idtipo_visado_novedad');
    }
}
