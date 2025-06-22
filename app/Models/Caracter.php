<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Caracter
 * @package App\Models
 * @version September 13, 2021, 4:40 am UTC
 *
 * @property integer codigo
 * @property string descripcion
 */
class Caracter extends Model
{
    use SoftDeletes;

    public $table = 'caracter';
    protected $primaryKey = 'idCaracter';
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
        'idCaracter' => 'integer',
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
