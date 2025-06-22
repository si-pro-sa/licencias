<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Localidad
 * @package App\Models
 * @version November 27, 2018, 7:20 am UTC
 *
 * @property string localidad
 * @property integer iddepartamento
 */
class Localidad extends Model
{
    use SoftDeletes;

    public $table = 'localidad';

    public $primaryKey = 'idlocalidad';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    protected $hidden = ['created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];

    public $fillable = [
        'localidad',
        'iddepartamento',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idlocalidad' => 'integer',
        'localidad' => 'string',
        'iddepartamento' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function departamento()
    {
        return $this->belongsTo('App\Models\Departamento','iddepartamento');
    }

    public function provincia()
    {
        return $this->belongsTo('App\Models\Provincia','idprovincia');
    }

    public function domicilio()
    {
        return $this->hasOne('App\Models\Domicilio','idlocalidad');
    }

    public static function getByProvincia($idprovincia)
    {
        return Localidad::where('idprovincia',$idprovincia)->orderBy('localidad')->get();

    }
}
