<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class ReintegroDetalles
 * @package App\Models
 * @version Enero, 2023, 20:30 UTC
 *
 * @property string codigo
 * @property string descripcion
 * @property integer monto
 * @property integer numero
 * @property integer idReintegro
 * @property integer idReintegroDetalle
 */
class ReintegroDetalles extends Model
{
    use SoftDeletes;

    public $table = 'reintegro_detalles';
    protected $primaryKey = 'idReintegroDetalle';
    protected $connection = 'pgsql_public';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idReintegro',
        'numero',
        'descripcion',
        'codigo',
        'monto'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'numero' => 'integer',
        'descripcion' => 'string',
        'monto' => 'integer',
        'codigo' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'codigo' => 'required',
        'descripcion' => 'required',
        'monto' => 'required',
        'numero' => 'required'
    ];

    public function reintegro()
    {
        return $this->belongsTo(App\Models\Reintegros::class);
    }
}
