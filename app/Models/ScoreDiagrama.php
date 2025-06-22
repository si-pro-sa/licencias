<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreDiagrama extends Model
{

    public $table = 'score_diagramas';
    public $timestamps = false;

    protected $primaryKey = 'idscorediagrama';
    protected $dates = ['fecha', 'carga_diagrama_at', 'carga_ep_at'];

    public $fillable = [
        'idpuesto',
        'idpuestoadicional',
        'iddependencia',
        'fecha',
        'hora_desde',
        'cantHoras',
        'licencias',
        'efectivaPrestacion', 
        'user_id_carga',
        'carga_diagrama_at',
        'user_id_ep',
        'carga_ep_at',
        'idtipo_dia',
        'idtipo_actividad',
        'cantidad_pacientes'
    ];

    protected $casts = [
        'idscorediagrama' => 'integer',
        'idpuesto' => 'integer',
        'idpuestoadicional' => 'integer',
        'iddependencia' => 'integer',
        'fecha' => 'date:Y-m-d',
        'cantHoras' => 'integer',
        'licencias' => 'boolean',
        'efectivaPrestacion' => 'boolean',
        'user_id_carga' => 'integer',
        'carga_diagrama_at' => 'date',
        'user_id_ep' => 'integer',
        'carga_ep_at' => 'date',
        'idtipo_dia'=>'integer',
        'idtipo_actividad'=>'integer',
        'cantidad_pacientes' => 'integer'
    ];

    public static $rules = [
        'idscorediagrama' => 'required',
        'idpuesto' => 'required',
        'idpuestoadicional' => 'required',
        'iddependencia' => 'required',
        'fecha' => 'required',
        'hora_desde' => 'required',
        'cantHoras' => 'required',
        'licencias' => 'required',
        'idtipo_dia'=>'required',
        'idtipo_actividad'=>'required'
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'idpuesto');
    }

    public function licenciaValidScore()
    {
        $tieneLicenciaValida = false;

        $licencias = Licencia::where('idpuesto', $this->idpuesto)->where('segundo_visado', true)->where('idtipoLicencia', 16)->whereBetween('dias', [14, 21])->whereDate('fecha_efectiva_inicio', '<=' ,$this->fecha)->whereDate('fecha_efectiva_final', '>=' ,$this->fecha)->first();

        if(isset($licencias)){

            $periodoExcluidoScore = ScorePerLicExcluido::
            whereDate('fdesde', '<=' ,$licencias->fecha_efectiva_inicio)
            ->whereDate('fhasta','>=' ,$licencias->fecha_efectiva_final)
            ->whereDate('fdesde_vigencia', '<=' ,$licencias->fecha_efectiva_inicio)
            ->whereDate('fhasta_vigencia','>=' ,$licencias->fecha_efectiva_final)
            ->first();

            if(!isset($periodoExcluidoScore)){
                $tieneLicenciaValida=true;
            }
        }
        
        return [$tieneLicenciaValida, $periodoExcluidoScore,$licencias];
    }

    use HasFactory;
}
