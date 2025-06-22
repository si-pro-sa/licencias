<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TipoEvento
 * @package App\Models
 * @version September 13, 2021, 4:42 am UTC
 *
 * @property integer codigo
 * @property string descripcion
 */
class TipoEvento extends Model
{
    use SoftDeletes;

    public $table = 'tipo_evento';
    protected $primaryKey = 'idTipoEvento';
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
        'idTipoEvento' => 'integer',
        'codigo' => 'integer',
        'descripcion' => 'string'
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

    /**
     * Get the capacitaciones.
     */
    public function capacitaciones()
    {
        return $this->hasMany(App\Models\Capacitacion::class);
    }
}
