<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class EstadoReintegros
 * @package App\Models
 * @version Enero, 2023, 20:30 UTC
 *
 * @property string codigo
 * @property string descripcion
 * @property integer idEstadoReintegro
 */
class EstadoReintegros extends Model
{
    use SoftDeletes;

    public $table = 'estado_reintegros';
    protected $primaryKey = 'idEstadoReintegro';
    protected $connection = 'pgsql_public';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'codigo',
        'descripcion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idEstadoReintegro' => 'integer',
        'codigo' => 'integer',
        'descripcion' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'required'
    ];

    public function reintegros()
    {
        return $this->hasMany(App\Models\Reintegros::class);
    }
}
