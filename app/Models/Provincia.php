<?php

namespace App\Models;
use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincia extends Model
{
    use SoftDeletes;
    public $table = 'provincia';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $primaryKey = 'idprovincia';

    protected $dates = ['deleted_at'];

    protected $hidden = ['created_by','created_at','updated_by','updated_at','deleted_by','deleted_at'];


    public $fillable = [
        'nombre',
        'nombre_completo',
        'codigo',
    ];

    public function departamentos()
    {
        return $this->hasMany('App\Models\Departamento','idprovincia');
    }

    public function localidades()
    {
        return $this->hasMany('App\Models\Localidad','idprovincia');
    }

    public function domicilio()
    {
        return $this->hasOne('App\Models\Domicilio','idprovincia');
    }


}
