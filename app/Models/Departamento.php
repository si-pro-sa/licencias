<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Departamento
 * @package App\Models
 * @version November 27, 2018, 7:21 am UTC
 *
 * @property string departamento
 */
class Departamento extends Model
{
    use SoftDeletes;

    public $table = 'departamento';

    protected $primaryKey = 'iddepartamento';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = ['created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];


    public $fillable = [
        'departamento'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'iddepartamento' => 'integer',
        'departamento' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function localidades()
    {
        return $this->hasMany('App\Models\Localidad','iddepartamento');
    }

    public function provincia()
    {
        return $this->belongsTo('App\Models\Provincia','idprovincia');
    }
}
