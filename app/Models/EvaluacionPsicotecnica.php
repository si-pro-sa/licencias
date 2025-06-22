<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class EvaluacionPsicotecnica
 * @package App\Models
 *
 * @property integer idevaluacion_psicotecnica
 * @property string tipo_entrevista
 * @property date fecha_evaluacion
 * @property integer desempeno
 * @property integer aspectos_cognitivos
 * @property integer aspectos_psicoafectivos
 * @property integer motivacion
 * @property boolean condicionantes
 * @property string tipo_condicionantes
 * @property boolean experiencia_laboral
 * @property boolean sector_publico
 * @property boolean reune_competencias
 * @property string observaciones
 * @property string atencion_usuario
 * @property string trabajo_en_equipo
 * @property string adaptabilidad
 * @property string tolerancia_presion
 * @property string organizacion
 * @property integer idpsicoevaluador
 * @property integer idtipo_puesto
 * @property integer evaluacion_psicotecnica_id
 * @property integer evaluacion_psicotecnica_type
 *
 */


class EvaluacionPsicotecnica extends Model
{
    use SoftDeletes;

    public $table = 'evaluacion_psicotecnica';

    public $primaryKey = 'idevaluacion_psicotecnica';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'tipo_entrevista',
        'fecha_evaluacion',
        'desempeno',
        'aspectos_cognitivos',
        'aspectos_psicoafectivos',
        'motivacion',
        'condicionantes',
        'tipo_condicionantes',
        'experiencia_laboral',
        'sector_publico',
        'reune_competencias',
        'observaciones',
        'atencion_usuario',
        'trabajo_en_equipo',
        'adaptabilidad',
        'tolerancia_presion',
        'organizacion',
        'idpsicoevaluador',
        'idtipo_puesto',
        'evaluacion_psicotecnica_id',
        'evaluacion_psicotecnica_type',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tipo_entrevista' => 'string',
        'fecha_evaluacion' => 'date',
        'desempeno' => 'integer',
        'aspectos_cognitivos' => 'integer',
        'aspectos_psicoafectivos' => 'integer',
        'motivacion' => 'integer',
        'condicionantes' => 'boolean',
        'tipo_condicionantes' => 'string',
        'experiencia_laboral' => 'boolean',
        'sector_publico' => 'boolean',
        'reune_competencias' => 'boolean',
        'observaciones' => 'string',
        'atencion_usuario' => 'integer',
        'adaptabilidad' => 'integer',
        'tolerancia_presion' => 'integer',
        'organizacion' => 'integer',
        'idpsicoevaludaor' => 'integer',
        'idtipo_puesto' => 'integer',
        'evaluacion_psicotecnica_id' => 'integer',
        'evaluacion_psicotecnica_type' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    public function extendido()
    {
        return $this->hasOne(ExtendidoEvaluacionPsicotecnica::class, 'idevaluacion_psicotecnica');
    }

    public function puestoRecomendado()
    {
        return $this->belongsTo(TipoFuncion::class, 'idtipo_funcion');
    }

    /**
     * Get the owning candidato model.
     */
    public function evaluacion_psicotecnica()
    {
        return $this->morphTo();
    }

    public function psicoevaluador()
    {
        return $this->belongsTo(PsicoEvaluador::class, 'idpsicoevaluador');
    }




    public function formatoPDF()
    {
        if(count($this->evaluacion_psicotecnica->recomendacion)>0) {
            $formacion = utf8_decode($this->evaluacion_psicotecnica->recomendacion->where('evaluacion_psicotecnica_id',$this->candidato_id)->first()->formacion->titulo);
            $nivel = utf8_decode($this->evaluacion_psicotecnica->recomendacion->where('evaluacion_psicotecnica_id',$this->candidato_id)->first()->nivel->tiponivel);
        } else {
            $formacion = "";
            $nivel = "";
        }
        $postulante = [
            'apynom' => utf8_decode(strtoupper($this->evaluacion_psicotecnica->apellido).' '.strtoupper($this->evaluacion_psicotecnica->nombre)),
            'dni' => utf8_decode($this->evaluacion_psicotecnica->documento),
            'telefono' => utf8_decode($this->evaluacion_psicotecnica->celular),
            'formacion' => $formacion,
            'nivel' => $nivel,
            'fecha_evaluacion' => date('d/m/Y',strtotime($this->fecha_evaluacion)),
            'domicilio' => $this->evaluacion_psicotecnica->domicilio->formato(),
            'puesto_postulado' => $this->evaluacion_psicotecnica->ultimaPostulacion(),
        ];


        $perfil_laboral = [
            'desempeno' => $this->desempeno,
            'aspectos_cognitivos' => $this->aspectos_cognitivos,
            'aspectos_psicoafectivos' => $this->aspectos_psicoafectivos,
            'motivacion' => $this->motivacion,
            'condicionantes' => $this->condicionantes ? 'SI' : 'NO',
            'tipo_condicionantes' => $this->tipo_condicionantes,
            'experiencia_laboral' => $this->experiencia_laboral ? 'SI' : 'NO',
            'sector_publico' => $this->sector_publico ? 'SI' : 'NO',
            'reune_competencias' => $this->reune_competencias ? 'SI' : 'NO',
            'recomendado_para' => $this->puestoRecomendado->tipofuncion,
            'observaciones' => $this->observaciones,
        ];

        $competencias = [
            0 => $this->atencion_usuario,
            1 => $this->trabajo_en_equipo,
            2 => $this->adaptabilidad,
            3 => $this->tolerancia_presion,
            4 => $this->organizacion,
        ];

        $firma = [
            'nombre' => $this->psicoevaluador->firma,
            'matricula' => "MP ".$this->psicoevaluador->matricula,
        ];

        $psicotecnico = [
            'postulante' => $postulante,
            'perfil_laboral' => $perfil_laboral,
            'competencias' => $competencias,
            'firma' => $firma,
        ];

        return $psicotecnico;
    }

    public static function consolidado(array $filtros=null)
    {

        $data = [];
        if(isset($filtros)) {
            $ids = EvaluacionPsicotecnica::consolidadoFiltrado($filtros);
            $evaluaciones = EvaluacionPsicotecnica::whereIn('idevaluacion_psicotecnica',$ids)->get();
        } else {
            $evaluaciones = EvaluacionPsicotecnica::orderBy('fecha_evaluacion', 'DESC')->get();
        }

        foreach($evaluaciones as $key => $evaluacion) {
            if(count($evaluacion->evaluacion_psicotecnica->recomendacion)==0) {
                continue;
            }
            $data[$key] = [
                'tel' => $evaluacion->evaluacion_psicotecnica->celular,
                'documento' => $evaluacion->evaluacion_psicotecnica->documento,
                'apellido_y_nombre' => $evaluacion->evaluacion_psicotecnica->apellido.", ".$evaluacion->evaluacion_psicotecnica->nombre,
                'fnacimiento' => date("d/m/Y",strtotime($evaluacion->evaluacion_psicotecnica->fnacimiento)),
                'puesto' => $evaluacion->puestoRecomendado->tipofuncion,
                'nivel' => $evaluacion->isAgente() ? $evaluacion->evaluacion_psicotecnica->ultimoPuesto()->tipoNivel->tiponivel : $evaluacion->evaluacion_psicotecnica->recomendacion->last()->nivel->tiponivel,
                'apto' => $evaluacion->reune_competencias ? "APTO":"NO APTO",
                'referido1' => $evaluacion->evaluacion_psicotecnica->recomendacion->last()->referidoInterno->nombre,
                'referido2' => $evaluacion->evaluacion_psicotecnica->recomendacion->last()->referidoPolitico->nombre,
                'observacion' => $evaluacion->observaciones,
                'entrevistador' => $evaluacion->psicoevaluador->firma,
                'tipo_evaluacion' => $evaluacion->tipo_entrevista,
                'ingreso' => $evaluacion->isAgente() ? "SI":"NO",
                'tipo_ingreso' => $evaluacion->isAgente() ? $evaluacion->evaluacion_psicotecnica->ingreso() : "NO CORRESPONDE",
                'lugar_trabajo' => $evaluacion->isAgente() ? $evaluacion->evaluacion_psicotecnica->lugarDeTrabajo(): "NO CORRESPONDE",
            ];
        }
        return $data;
    }

    public static function consolidadoFiltrado(array $filtros)
    {
        $referido1 = $filtros['referido1'];
        $departamento = $filtros['iddepartamento'];
        $formacion = $filtros['idformacion']; //Tabla de Títulos
        $ids_agentes = DB::table('evaluacion_psicotecnica')
                ->join('agente','evaluacion_psicotecnica.evaluacion_psicotecnica_id','=','agente.idagente')
                ->join('domicilio','agente.iddomicilio','=','domicilio.iddomicilio')
                ->join('localidad','domicilio.idlocalidad','=','localidad.idlocalidad')
                ->join('recomendacion_candidato','agente.idagente','=','recomendacion_candidato.candidato_id')
                ->where('evaluacion_psicotecnica.evaluacion_psicotecnica_type','=','App\Models\Agente')
                ->when($referido1, function ($query, $referido1) {
                    return $query->where('recomendacion_candidato.idtipo_referido_interno','=',$referido1);
                })
                ->when($departamento, function ($query, $departamento) {
                    return $query
                            ->where('localidad.iddepartamento','=',$departamento);
                })
                ->when($formacion, function ($query, $formacion) {
                    return $query
                            ->where('recomendacion_candidato.idtitulo','=',$formacion);
                })
                ->select('evaluacion_psicotecnica.idevaluacion_psicotecnica')
                ->get()->toArray();

        $ids_candidatos = DB::table('evaluacion_psicotecnica')
            ->join('candidato','evaluacion_psicotecnica.evaluacion_psicotecnica_id','=','candidato.idcandidato')
            ->join('domicilio','candidato.iddomicilio','=','domicilio.iddomicilio')
            ->join('localidad','domicilio.idlocalidad','=','localidad.idlocalidad')
            ->join('recomendacion_candidato','candidato.idcandidato','=','recomendacion_candidato.candidato_id')
            ->where('evaluacion_psicotecnica.evaluacion_psicotecnica_type','=','App\Models\Candidato')
            ->when($referido1, function ($query, $referido1) {
                return $query->where('recomendacion_candidato.idtipo_referido_interno','=',$referido1);
            })
            ->when($departamento, function ($query, $departamento) {
                return $query
                    ->where('localidad.iddepartamento','=',$departamento);
            })
            ->when($formacion, function ($query, $formacion) {
                return $query
                    ->where('recomendacion_candidato.idtitulo','=',$formacion);
            })
            ->select('evaluacion_psicotecnica.idevaluacion_psicotecnica')
            ->get()->toArray();
        $data = [];
        foreach ($ids_agentes as $id) {
            $data[] = $id->idevaluacion_psicotecnica;
        }
        foreach ($ids_candidatos as $id) {
            $data[] = $id->idevaluacion_psicotecnica;
        }
        return $data;
    }


    public function isAgente()
    {
        if($this->evaluacion_psicotecnica_type == "App\Models\Agente") {
            return true;
        }
        return false;

    }

    private function valorarCompetencia(int $valorIngresado)
    {
        switch ($valorIngresado){
            case 1:
                return 1;
            case 2:
                return 1;
            case 3:
                return 2;
            case 4:
                return 2;
            case 5:
                return 3;
            default:
                return 1;
        }
    }
}
