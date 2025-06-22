<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class EvaluacionTecnica
 * @package App\Models
 *
 * @property integer idevaluacion_tecnica
 * @property date fecha_evaluacion
 * @property boolean escrito
 * @property boolean teorico
 * @property boolean practico
 * @property string conclusion
 * @property integer conclusion_rango
 * @property integer candidato_id
 * @property integer candidato_type
 *
 */

class EvaluacionTecnica extends Model
{
    use SoftDeletes;

    public $table = 'evaluacion_tecnica';

    public $primaryKey = 'idevaluacion_tecnica';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $fillable = [
        'fecha_evaluacion',
        'escrito',
        'teorico',
        'practico',
        'conclusion',
        'conclusion_rango',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fecha_evaluacion' => 'date',
        'escrito' => 'boolean',
        'teorico' => 'boolean',
        'practico' => 'boolean',
        'conclusion' => 'string',
        'conclusion_rango' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * Get the owning candidato model.
     */
    public function candidato()
    {
        return $this->morphTo();
    }

    /**
     * Exportacion de array con datos para el formato pdf
     * @return array
     */
    public function formatoPDF()
    {
        $postulante = [
            'apynom' => utf8_decode($this->candidato->apellido.' '.$this->candidato->nombre),
            'edad' => $this->candidato->edad(),
            'fnacimiento' => date("d/m/Y",strtotime($this->candidato->fnacimiento)),
            'dni' => utf8_decode($this->candidato->documento),
            'domicilio' => $this->candidato->domicilio->formato(),
            'telefono' => utf8_decode($this->candidato->celular),
            'estudios_cursados' => utf8_decode($this->candidato->recomendacion->where('candidato_id',$this->candidato_id)->first()->formacion->titulo),
            'nivel' => utf8_decode($this->candidato->recomendacion->where('candidato_id',$this->candidato_id)->first()->nivel->tiponivel),
            'fecha_evaluacion' => date('d/m/Y',strtotime($this->fecha_evaluacion)),
        ];

        $modo_evaluacion = [
            'escrito' => $this->escrito ? 'SI':'NO',
            'teorico' => $this->teorico ? 'SI':'NO',
            'practico' => $this->practico ? 'SI':'NO',
        ];

        $conclusion = [
          'texto' => $this->conclusion,
          'rango' => $this->conclusion_rango,
        ];

        $evaluacion_tecnica = [
            'postulante' => $postulante,
            'modo_evaluacion' => $modo_evaluacion,
            'conclusion' => $conclusion,
        ];

        return $evaluacion_tecnica;
    }

    public static function exportarExcel()
    {
        $evaluaciones = EvaluacionTecnica::orderBy('fecha_evaluacion', 'DESC')->get();

        $data = [];

        foreach($evaluaciones as $key => $evaluacion) {
            if(count($evaluacion->candidato->recomendacion)==0) {
                continue;
            }
            $data[$key] = [
                'tel' => $evaluacion->candidato->celular,
                'documento' => $evaluacion->candidato->documento,
                'apellido_y_nombre' => $evaluacion->candidato->apellido.", ".$evaluacion->candidato->nombre,
                'fnacimiento' => date("d/m/Y",strtotime($evaluacion->candidato->fnacimiento)),
                'nivel' => $evaluacion->isAgente() ? $evaluacion->candidato->ultimoPuesto()->tipoNivel->tiponivel : $evaluacion->candidato->recomendacion->last()->nivel->tiponivel,
                'referido1' => $evaluacion->candidato->recomendacion->last()->referidoInterno->nombre,
                'referido2' => $evaluacion->candidato->recomendacion->last()->referidoPolitico->nombre,
                'escrito' => $evaluacion->escrito ? 'ESCRITO' : '-',
                'teorico' => $evaluacion->teorico ? 'TEÓRICO' : '-',
                'practico' => $evaluacion->practico ? 'PRÁCTICO' : '-',
                'conclusion' => $evaluacion->conclusion(),
                'ingreso' => $evaluacion->isAgente() ? "SI":"NO",
                'tipo_ingreso' => $evaluacion->isAgente() ? $evaluacion->candidato->ingreso() : "NO CORRESPONDE",
                'lugar_trabajo' => $evaluacion->isAgente() ? $evaluacion->candidato->lugarDeTrabajo(): "NO CORRESPONDE",
            ];
        }
        return $data;
    }

    public function isAgente()
    {
        if($this->candidato_type == "App\Models\Agente") {
            return true;
        }
        return false;

    }

    public function conclusion()
    {
        switch($this->conclusion_rango) {
            case 2:
                return "MEDIO";
            case 3:
                return "ALTO";
            default:
                return "BAJO";
        }
    }


}
