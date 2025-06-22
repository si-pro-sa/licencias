<?php
namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\MasterModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;

class VEvaluacionPsicotecnica extends MasterModel
{
    public $table = 'v_evaluacion_psicotecnica';
    protected $primaryKey = 'idevaluacion_psicotecnica';
    protected $connection = 'pgsql_public';

    public $fillable = [
        'nombre',
        'documento',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idevaluacion_psicotecnica' => 'integer',
        'idagente' => 'integer',
        'calle' => 'string',
        'departamento' => 'string',
        'localidad' => 'string',
        'codigo_postal' => 'integer',
        'telefono' => 'integer',
        'celular' => 'integer',
        'email' => 'string',
        'documento' => 'integer',
        'nombre' => 'string',
        'fnacimiento' => 'date:d/m/Y',
        'tipofuncion' => 'string',
        'titulo' => 'string',
        'tiponivel' => 'string',
        'tiporecomendacion' => 'string',
        'recomienda_para' => 'string',
        'fecha_evaluacion' => 'date:d/m/Y',
        'fecha_creacion' => 'date:d/m/Y',
        'observaciones' => 'string',
        'firma' => 'string',
        'tipoentrevista' => 'string',
        'evaluacion_psicotecnica_grupo' => 'string',
        'puntaje' => 'float',
        'ingreso' => 'string',
        'tipo_ingreso_sin_dependencia' => 'string',
        'lugar' => 'string',
        'referido1' => 'string',
        'referido2' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }
    
    public function evaluacion_psicotecnica()
    {
        return $this->belongsTo(Agente::class, 'idagente');
    }
    
    public function ingreso()
    {
        $ret = '';
        $tipoIngreso = $this->tipoIngreso();
        if (isset($tipoIngreso['planta']))
           {$ret = 'SI';}
           else
           {$ret = ($tipoIngreso['reemplazos']['count'] == 'NO' && $tipoIngreso['libres']['count'] == 'NO' && $tipoIngreso['guardias']['count'] == 'NO') ? 'NO' : 'SI'; };
        return $ret;
    }
    
    private $tipoIngreso = null;
    
    public function tipoIngreso()
    {
        if (empty($this->tipoIngreso)) {
//            if (get_class($this->evaluacion_psicotecnica) == 'App\Models\Agente' && $this->evaluacion_psicotecnica->ultimoPuesto()) {
            if (isset($this->idagente) && $this->evaluacion_psicotecnica->ultimoPuesto()) {
                if (in_array($this->evaluacion_psicotecnica->ultimoPuesto()->idtipo_planta, [1,2,4,5,6])) {
                    $this->tipoIngreso = ['planta'=> $this->evaluacion_psicotecnica->ultimoPuesto()->tipoPlanta->tipoplanta];
                } else {
                    $this->tipoIngreso = [
                        'reemplazos' => $this->evaluacion_psicotecnica->ultimoPuesto()->getDependenciasReemplazos(),
                        'libres' => $this->evaluacion_psicotecnica->ultimoPuesto()->getDependenciasLibres(),
                        'guardias' => $this->evaluacion_psicotecnica->ultimoPuesto()->getDependenciasGuardiasAgente(),
                        'coberturas' => $this->evaluacion_psicotecnica->ultimoPuesto()->getDependenciasCoberturasAgente(),
                    ];
                }
            } else {
                $this->tipoIngreso = [
                    'reemplazos' => ['dependencias' => 'NO', 'count' => 'NO'],
                    'libres' => ['dependencias' => 'NO', 'count' => 'NO'],
                    'guardias' => ['dependencias' => 'NO', 'count' => 'NO'],
                    'coberturas' => ['dependencias' => 'NO', 'count' => 'NO'],
                ];
            }
        }
        return $this->tipoIngreso;
    }
    
}