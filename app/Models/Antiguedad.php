<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Antiguedad
 * @package App\Models
 * @version January 13, 2020, 2:17 am UTC
 *
 * @property \App\Models\Agente idagente
 * @property integer idagente
 * @property integer año
 * @property string pedido
 * @property string disponible
 * @property boolean vigente
 */
class Antiguedad extends Model
{
    use SoftDeletes;

    public $table = 'antiguedades';
    protected $primaryKey = 'idantiguedad';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idagente',
        'año',
        'pedido',
        'disponible',
        'vigente',
        'idusuario'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idantiguedad' => 'integer',
        'idagente' => 'integer',
        'año' => 'integer',
        'pedido' => 'integer',
        'disponible' => 'integer',
        'vigente' => 'boolean',
        'idusuario' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idagente' => 'required',
        'año' => 'required',
        'pedido' => 'required',
        'disponible' => 'required',
        'vigente' => 'required',
        'idusuario' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idagente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idusuario()
    {
        return $this->belongsTo(\App\User::class, 'idusuario');
    }
}
