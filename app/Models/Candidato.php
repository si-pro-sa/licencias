<?php

namespace App\Models;

use App\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Candidato
 * @package App\Models
 *
 * @property integer idcandidato
 * @property string apellido
 * @property string nombre
 * @property integer documento
 * @property bigInteger cuil
 * @property string|\Carbon\Carbon fnacimiento
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property string domicilio
 * @property integer idlocalidad
 * @property string codigopostal
 * @property string localid
 * @property string email
 * @property string celular
 * @property string telefono
 * @property integer idtitulo
 * @property string especialidad

 */

class Candidato extends Model
{
    use SoftDeletes;

    public $table = 'candidato';
    protected $primaryKey = 'idcandidato';

    protected $fillable = [
        'documento',
        'cuil',
        'apellido',
        'nombre',
        'fnacimiento',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcandidato' => 'integer',
        'apellido' => 'string',
        'nombre' => 'string',
        'documento' => 'integer',
        'genero' => 'string',
        'fnacimiento' => 'date',
        'usuario' => 'string',
        'operacion' => 'string',
        'iddomicilio' => 'integer',
        'idlocalidad' => 'integer',
        'idtitulo' => 'integer',
        'codigopostal' => 'string',
        'localid' => 'string',
        'email' => 'string'
    ];

    public function domicilio()
    {
        return $this->belongsTo('App\Models\Domicilio', 'iddomicilio');
    }

    public function psicotecnicos()
    {
        return $this->morphMany('App\Models\EvaluacionPsicotecnica', 'evaluacion_psicotecnica');
    }

    public function evalTecnicas()
    {
        return $this->morphMany('App\Models\EvaluacionTecnica', 'candidato');
    }

    public function turnosPsicotecnicos()
    {
        return $this->morphMany('App\Models\TurnoPsicoEvaluador', 'candidato');
    }

    public function titulo()
    {
        return $this->belongsTo('App\Models\Titulo', 'idtitulo');
    }

    public function recomendacion()
    {
        return $this->morphMany('App\Models\RecomendacionCandidato', 'candidato');
    }

    public function getEdadAttribute() {
        return $this->fnacimiento->diffInYears(\Carbon\Carbon::now());
    }

    public static function buscarDocumento(int $documento)
    {
        return self::where('documento', $documento)->first();
    }
    public function ultimaPostulacion()
    {
        return $this->recomendacion->sortByDesc('created_at')->first()->especialidad->tipofuncion;
    }

    public function edad()
    {
        $fecha_actual = new DateTime();
        $edad = $fecha_actual->diff($this->fnacimiento)->y;
        return $edad;
    }
}
