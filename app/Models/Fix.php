<?php

namespace App\Models;

use App\Models\CargoHorario;
use App\Models\HorarioPuesto;

class Fix
{
    public static function fixCargosHorariosRotativos()
    {
        $horarios = CargoHorario::where('idtipo_dia', 9)
                                    ->where('cantidad_mensual', 0)
                                    ->get();
        foreach ($horarios as $horario) {
            $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
            $cantidad_dias = 120 / $rango->getDiffHoras();
            $horario->cantidad_mensual = (int) $cantidad_dias;
            if (!$horario->save()) {
                return 'error';
            }
        }
        return 'true';
    }

    public static function fixPuestosHorariosRotativos()
    {
        $horarios = HorarioPuesto::where('idtipo_dia', 9)
                                    ->where('cantidad_mensual', 0)
                                    ->get();
        foreach ($horarios as $horario) {
            $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
            $cantidad_dias = 120 / $rango->getDiffHoras();
            $horario->cantidad_mensual = (int) $cantidad_dias;
            if (!$horario->save()) {
                return 'error';
            }
        }

        return 'true';
    }

    public static function getIdsCargosHorariosRotativosConEfectorAdicional()
    {
        $horarios = CargoHorario::where('idtipo_dia', 9)
                                    ->get();
        $horariosConRotativosEnUnEfector = [];
        foreach ($horarios as $horario) {
            $horariosAdicionales = CargoHorario::where('idcargo', $horario->idcargo)
                                            ->where('idtipo_dia', '<>', 9)
                                            ->count();
            $rango = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
            $horasRotativas = (int) ($horario->cantidad_mensual * $rango->getDiffHoras());

            if ($horariosAdicionales > 0 && $horasRotativas === 120) {
                array_push($horariosConRotativosEnUnEfector, $horario->idcargo);
            }
        }

        return $horariosConRotativosEnUnEfector;
    }

    public static function getIdsPuestosHorariosRotativosConEfectorAdicional()
    {
        $horarios = HorarioPuesto::with('puesto')
                                    ->where('idtipo_dia', 9)
                                    ->where('puesto_type', 'App\Models\Puesto')
                                    ->get();
        $horariosConRotativosEnUnEfector = [];
        foreach ($horarios as $horario) {
            $horariosAdicionales = $horario->puesto->puestosAdicionales->count();
            if ($horariosAdicionales > 0) {
                array_push($horariosConRotativosEnUnEfector, $horario->puesto_id);
            }
        }

        return $horariosConRotativosEnUnEfector;
    }
}
