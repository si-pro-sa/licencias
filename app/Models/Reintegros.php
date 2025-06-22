<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Class Reintegros
 * @package App\Models
 * @version Enero, 2023, 20:30 UTC
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
 */
class Reintegros extends Model
{
    use SoftDeletes;

    public $table = 'reintegros';
    protected $primaryKey = 'idReintegro';
    protected $connection = 'pgsql_public';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idlicencia',
        'idAgente',
        'idEstadoReintegro',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idReintegro' => 'integer',
        'idlicencia' => 'integer',
        'idAgente' => 'integer',
        'idEstadoReintegro' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idAgente' => 'required',
        'idEstadoReintegro' => 'required'
    ];

    public function reintegroDetalles()
    {
        return $this->hasMany(App\Models\ReintegroDetalles::class);
    }
    public function estadoReintegro()
    {
        return $this->belongsTo(App\Models\EstadoReintegros::class);
    }
}
