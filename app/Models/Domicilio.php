<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Domicilio
 * @package App\Models
 * @version Agosto 7, 2019, 11:12 am UTC
 *
 * @property integer idlocalidad
 * @property string calle
 * @property integer numero
 * @property string departamento
 * @property string piso
 * @property string block
 * @property string codigo_postal
 * @property integer iddepartamento
 * @property integer idprovincia
 */

class Domicilio extends Model
{
    use SoftDeletes;

    public $table = 'domicilio';
    public $primaryKey = 'iddomicilio';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $hidden = ['created_at','updated_at','deleted_at'];

    protected $dates = ['deleted_at'];


    public $fillable = [
        'calle',
        'numero',
        'departamento',
        'piso',
        'block',
        'codigo_postal',
        'idprovincia',
        'idlocalidad',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'iddomicilio' => 'integer',
        'calle' => 'string',
        'numero' => 'integer',
        'departamento' => 'string',
        'piso' => 'string',
        'block' => 'string',
        'idlocalidad' => 'integer',
        'iddepartamento' => 'integer',
        'idprovincia' => 'integer',
        'codigo_postal' => 'integer',
    ];

    public function candidato() {
        return $this->hasOne('App\Models\Candidato','iddomicilio');
    }

    public function provincia() {
        return $this->belongsTo('App\Models\Provincia','idprovincia');
    }

    public function localidad() {
        return $this->belongsTo('App\Models\Localidad','idlocalidad');
    }

    public function formato() {
        $calle = $this->calle . ' ' . $this->numero;
        $edificio = (isset($this->piso) ? ('Piso: '. $this->piso):'') . (isset($this->departamento) ? ('Dpto: ' . $this->departamento): '') . ((isset($this->block)) ? ('Block: ' . $this->block):'');
        $localidad = strtoupper($this->localidad->localidad) . ' - ' . strtoupper($this->provincia->nombre) . ' - CP: '. $this->codigo_postal;
        $data = [
            'calle' => $calle,
            'edificio' => $edificio,
            'localidad' => $localidad,
        ];
        return $data;
    }



}
