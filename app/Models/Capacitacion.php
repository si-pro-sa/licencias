<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Capacitacion
 * @package App\Models
 * @version Noviembre 27, 2023, 4:43 am UTC
 *
 * @property string resolucion
 * @property string razon
 * @property string evento_nombre
 * @property string evento_lugar
 * @property string fecha_evento_inicio
 * @property string fecha_evento_final
 * @property integer idCaracter
 * @property integer idTipoEvento
 * @property integer idAlcanceCapacitacion
 * @property string programa
 */
class Capacitacion extends Model
{
    use SoftDeletes;

    public $table = 'capacitacion';
    protected $primaryKey = 'idCapacitacion';
    protected $connection = 'pgsql_public';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'resolucion',
        'razon',
        'evento_nombre',
        'evento_lugar',
        'fecha_evento_inicio',
        'fecha_evento_final',
        'idTipoEvento',
        'idAlcanceCapacitacion',
        'programa'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idCapacitacion' => 'integer',
        'resolucion' => 'string',
        'razon' => 'string',
        'evento_nombre' => 'string',
        'evento_lugar' => 'string',
        'fecha_evento_inicio' => 'date',
        'fecha_evento_final' => 'date',
        'idTipoEvento' => 'integer',
        'idAlcanceCapacitacion' => 'integer',
        'programa' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idTipoEvento' => 'required',
        'idAlcanceCapacitacion' => 'required'
    ];

    public function capacitacionAgentes()
    {
        return $this->hasMany(CapacitacionAgente::class);
    }

    public function tipoEvento()
    {
        return $this->belongsTo(TipoEvento::class, 'idTipoEvento');
    }

    public function alcanceCapacitacion()
    {
        return $this->belongsTo(AlcanceCapacitacion::class, 'idAlcanceCapacitacion');
    }
}
