<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function Opis\Closure\serialize;

class ScoreLiquidacion extends Model
{

    public $table = 'score_liquidacion';
    public $timestamps = false;

    protected $primaryKey = 'idscore_liquidacion';
    protected $dates = ['fecha_ult_inicio','updated_guardia_final'];

    public $fillable = [
        'idperiodo',
        'idagente',
        'idpuesto',
        'idpuestoadicional',
        'iddependencia',
        'subnivel',
        'fecha_ult_inicio',
        'tipo_dia',
        'cantidad_guardia_realizadas',
        'cantidad_guardia_norealizadas',
        'cantidad_guardia_verificadas',
        'multiplicador',
        'user_guardia_final',
        'updated_guardia_final'
    ];

    protected $casts = [
        'idscore_liquidacion' => 'integer',
        'idagente' => 'integer',
        'idpuesto' => 'integer',
        'idpuestoadicional' => 'integer',
        'iddependencia' => 'integer',
        'subnivel' => 'string',
        'fecha_ult_inicio' => 'date:Y-m-d',
        'tipo_dia' => 'integer',
        'cantidad_guardia_realizadas' => 'integer',
        'cantidad_guardia_norealizadas' => 'integer',
        'cantidad_guardia_verificadas' => 'integer',
        'multiplicador' => 'integer',
        'user_guardia_final' => 'string',
        'updated_guardia_final' => 'date:Y-m-d'
    ];

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'idpuesto');
    }

    public function getdiagramasLiquidacion(){
        
        /* tipo dia 
        1 - lunes a viernes
        2 - Sabado, Domingo y Feriados
        3 - Dias especiales  */ 
    
        $puesto = $this->puesto;
        
        $diagramas = $puesto->getDiagramasPorIDPerdiodo($this->idperiodo);

        $feriadosEspeciales = [
            'Carnaval.',
            'Jueves Santo ',
            'Viernes Santo.',
            'Navidad.'
        ];

        switch ($this->tipo_dia){
            case 2 || 1:
                $feriados = Feriado::select('fecha')->where('idperiodo', $this->idperiodo)->WhereNotIn('descripcion', $feriadosEspeciales)->get()->toArray();
                break;
            case 3:
                $feriados = Feriado::select('fecha')->where('idperiodo', $this->idperiodo)->WhereIn('descripcion', $feriadosEspeciales)->get()->toArray(); 
                break;
        }

        $respuesta = [];

        foreach ($diagramas as $diagrama){
            $timestamp = strtotime($diagrama->fecha);

            switch ($this->tipo_dia){
                case 1:

                    $day_week = date('N', $timestamp);
                    $date = date('Y-m-d', $timestamp);

                    $diagramas_feriados = in_array(['fecha'=>$date], $feriados); 

                    if ($day_week<=5 && !$diagramas_feriados){
                        $respuesta[] = $diagrama;
                    }
                    break;
                    
                case 2:

                    $day_week = date('N', $timestamp);
                    $date = date('Y-m-d', $timestamp);

                    $diagramas_feriados = in_array(['fecha'=>$date], $feriados); 
                    
                    if ($day_week >= 6 || $diagramas_feriados){
                        $respuesta[] = $diagrama;
                    }
                    break;

                case 3:

                    $date = date('Y-m-d', $timestamp);
                    $diagramas_feriados = in_array(['fecha'=>$date], $feriados); 

                    if($diagramas_feriados){
                        $respuesta[] = $diagrama;
                    }
                    
                    break;
            }
        }

        return $respuesta;
        
    }

    public function ReiniciarFechaUltimoInicio($fechaReinicio){
        $timestampfechaReinicio = strtotime($fechaReinicio);
        $timestampUltInicio = strtotime($this->fecha_ult_inicio);
        
        if ($timestampfechaReinicio>$timestampUltInicio){
            ScoreLiquidacion::where('idpuesto', $this->idpuesto)
                ->where('idperiodo', $this->idperiodo)
                ->update(['fecha_ult_inicio'=>$fechaReinicio]);
            $this->ActualizarGuardiasValidadasAnteriores();
        }
    }

    public function ActulizarGuardiasValidadas(){
        $diagramas = $this->getdiagramasLiquidacion();

        $diagramasDesdeFechaInicio = [];
        $debeRecontar = $this->RecontarGuardiasValidadas();

        if($debeRecontar){
            foreach ($diagramas as $diagrama){       
                $timestampdiagrama = strtotime($diagrama->fecha);
                $timestampfecha_ult_inicio = strtotime($this->fecha_ult_inicio);
    
                if ($timestampdiagrama > $timestampfecha_ult_inicio){
                    $diagramasDesdeFechaInicio[] = $diagrama->cantHoras; 
                }   
            }
            ScoreLiquidacion::where('idscore_liquidacion', $this->idscore_liquidacion)->update(['cantidad_guardia_verificadas'=>array_sum($diagramasDesdeFechaInicio)/12]);
        }else{
            ScoreLiquidacion::where('idscore_liquidacion', $this->idscore_liquidacion)->update(['cantidad_guardia_verificadas'=>$this->cantidad_guardia_realizadas]);   
        }

        return array_sum($diagramasDesdeFechaInicio)/12;
    }

    public function ActualizarGuardiasValidadasAnteriores(){
        $liquidaciones = ScoreLiquidacion::where('idscore_liquidacion', '<', $this->idscore_liquidacion)->where('idperiodo', $this->idperiodo)->get();
        foreach($liquidaciones as $liquidacion){
            $liquidacion->ActulizarGuardiasValidadas();
        }
    }

    public function RecontarGuardiasValidadas(){
        $diagramas = $this->getdiagramasLiquidacion();
        $timestampDiagramas = [];

        foreach($diagramas as $diagrama){
            $timestampDiagramas[] = strtotime($diagrama->fecha);
        }

        $inicio = min($timestampDiagramas);

        $timestampfecha_ult_inicio = strtotime($this->fecha_ult_inicio);
        
        return $inicio <= $timestampfecha_ult_inicio; 
    }

    use HasFactory;
}
