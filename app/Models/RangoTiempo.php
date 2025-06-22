<?php

namespace App\Models;

use League\Period\Bounds;
use League\Period\Period;

class RangoTiempo
{
    private const DIA_ACTUAL = '2021-01-01';
    private const DIA_SIGUIENTE = '2021-01-02';

    private $diaSiguienteNoLaborable;

    //Time Strings
    private $horaDesdeString;
    private $horaHastaString;

    //Unix Timestamps
    private $horaDesde;
    private $horaHasta;

    private $horaDesdeDiaSiguiente;
    private $horaHastaDiaSiguiente;

    public function __construct(string $horaDesdeString, string $horaHastaString, $diaSiguienteNoLaborable = false, ?string $horaDesdeDiaSiguienteString = null, ?string $horaHastaDiaSiguienteString = null)
    {
        $this->horaDesdeString = $this->corregirFormatoHora($horaDesdeString);
        $this->horaHastaString = $this->corregirFormatoHora($horaHastaString);

        $this->horaDesde = strtotime($horaDesdeString);
        $this->horaHasta = strtotime($horaHastaString);

        $this->diaSiguienteNoLaborable = $diaSiguienteNoLaborable;
    }

    private function corregirFormatoHora($hora): string
    {
        $horaArray = explode(':', trim($hora));
        if (isset($horaArray[0], $horaArray[1])) {
            if ($horaArray[1] === '59') {
                return date('H:i', strtotime("{$horaArray[0]}:{$horaArray[1]} +1 minutes"));
            } else {
                return "{$horaArray[0]}:{$horaArray[1]}";
            }
        }

        return '';
    }

    public function setHoraDesde($hora, $dia = null): void
    {
        if (is_null($dia)) {
            $dia = self::DIA_ACTUAL;
        }

        if (is_int($hora)) {
            $hora = date('H:i', $hora);
        }

        $this->horaDesde = strtotime("{$dia} {$hora}");
        $this->horaDesdeDiaSiguiente = strtotime("{$dia} {$hora} +1 days");
    }

    public function setHoraHasta($hora, $dia = null): void
    {
        if (is_null($dia)) {
            $dia = self::DIA_ACTUAL;
        }

        if (is_int($hora)) {
            $hora = date('H:i', $hora);
        }

        $this->horaHasta = strtotime("{$dia} {$hora}");
        $this->horaHastaDiaSiguiente = strtotime("{$dia} {$hora} +1 days");
    }

    /**        Convierto las horas a tiempo de Unix.
     * En el caso de Comparar el rango de tiempo con otro, corregirá los días y horas a utilizar para cada rango.
     * Tambien se tiene en cuenta si el dia siguiente no es laborable.
     */
    public function setUnixTimestamps(RangoTiempo $rango = null): void
    {
        $horaDesde = $this->horaDesde;
        $horaHasta = $this->horaHasta;
        $rangoHoraDesde = $rango->horaDesde;
        $rangoHoraHasta = $rango->horaHasta;

        /*
        *   Determino a que día pertenecen
        */
        //Hora Desde
        $this->setHoraDesde($horaDesde);

        //Hora Hasta
        if ($horaDesde >= $horaHasta) {
            if ($this->diaSiguienteNoLaborable) {
                $this->setHoraHasta('23:59');
            } else {
                $this->setHoraHasta($horaHasta, self::DIA_SIGUIENTE);
            }
        } else {
            $this->setHoraHasta($horaHasta);
        }

        //Hora Desde de Rango
        if ($horaDesde >= $horaHasta && $rangoHoraDesde < $rangoHoraHasta) {
            $rango->setHoraDesde($rangoHoraDesde, self::DIA_SIGUIENTE);
        } else {
            $rango->setHoraDesde($rangoHoraDesde);
        }

        //Hora Hasta de Rango
        if (($horaDesde >= $horaHasta && $rangoHoraDesde < $rangoHoraHasta)
            || ($horaDesde < $horaHasta && $rangoHoraDesde >= $rangoHoraHasta)
            || ($horaDesde >= $horaHasta && $rangoHoraDesde >= $rangoHoraHasta)
        ) {
            $rango->setHoraHasta($rangoHoraHasta, self::DIA_SIGUIENTE);
        } else {
            $rango->setHoraHasta($rangoHoraHasta);
        }
    }

    /**
     * @throws Exception
     */
    public function getDiffHoras(): float
    {
        $horaDesde = strtotime(date(self::DIA_ACTUAL . ' H:i', $this->horaDesde));
        if ($this->horaDesde >= $this->horaHasta) {
            $horaHasta = strtotime(date(self::DIA_SIGUIENTE . ' H:i', $this->horaHasta));
        } else {
            $horaHasta = strtotime(date(self::DIA_ACTUAL . ' H:i', $this->horaHasta));
        }
        return (float) ($horaHasta - $horaDesde) / 60 / 60;
    }

    /**
     * Verifica si una PARTE o TODO el Rango ingresado esta DENTRO de otro rango horario.
     */
    public function tieneInterposicionConRango(RangoTiempo $rango): bool
    {
        $this->setUnixTimestamps($rango);

        // $horaDesde = $this->horaDesde;
        // $horaHasta = $this->horaHasta;

        // $horaDesdeDiaSiguiente = $this->horaDesdeDiaSiguiente;
        // $horaHastaDiaSiguiente = $this->horaHastaDiaSiguiente;

        // $horaDesdeRango = $rango->horaDesde;
        // $horaHastaRango = $rango->horaHasta;

        // \Log::info(date('Y-m-d H:i', $horaDesde));
        // \Log::info(date('Y-m-d H:i', $horaHasta));
        // \Log::info(date('Y-m-d H:i', $horaDesdeRango));
        // \Log::info(date('Y-m-d H:i', $horaHastaRango));
        // \Log::info(date('Y-m-d H:i', $horaDesdeDiaSiguiente));
        // \Log::info(date('Y-m-d H:i', $horaHastaDiaSiguiente));

        $period1 = Period::fromTimestamp($this->horaDesde, $this->horaHasta, Bounds::ExcludeAll);
        $period2 = Period::fromTimestamp($rango->horaDesde, $rango->horaHasta, Bounds::ExcludeAll);

        if (
            $this->horaDesdeString === $rango->horaDesdeString
            && $this->horaHastaString === $rango->horaHastaString
        ) {
            return true;
        }

        if (
            $this->horaHastaString === $rango->horaDesdeString
            || (date('Y-m-d', $this->horaHasta) === self::DIA_SIGUIENTE && $this->diaSiguienteNoLaborable)
        ) {
            return false;
        }

        if ($period1->overlaps($period2)) {
            return true;
        }

        return false;

        // //Mayores a los extremos: HORA_DESDE < RANGO_DESDE && HORA_HASTA > RANGO_HASTA
        // if (($horaDesde < $horaDesdeRango && $horaHasta > $horaHastaRango)
        //     //rangos coinciden extremos
        //     || ($horaDesde === $horaDesdeRango && $horaHasta === $horaHastaRango)
        //     //rangos coincide inicio y HORA_HASTA > RANGO_HASTA
        //     || ($horaDesde === $horaDesdeRango && $horaHasta > $horaDesdeRango)
        //     //rangos coincide fin y HORA_DESDE < RANGO_DESDE
        //     || ($horaDesde < $horaHastaRango && $horaHasta === $horaHastaRango)
        //     //HORA_DESDE < RANGO_DESDE && HORA_HASTA > RANGO_DESDE && HORA_HASTA < RANGO_HASTA
        //     || ($horaDesde < $horaDesdeRango && $horaHasta > $horaDesdeRango && $horaHasta < $horaHastaRango)
        //     //HORA_DESDE > RANGO_DESDE && HORA_DESDE < RANGO_HASTA && HORA_HASTA > RANGO_DESDE && HORA_HASTA < RANGO_HASTA
        //     || ($horaDesde > $horaDesdeRango && $horaDesde < $horaHastaRango && $horaHasta > $horaDesdeRango && $horaHasta < $horaHastaRango)
        //     //HORA_DESDE > RANGO_DESDE && HORA_DESDE < RANGO_HASTA && HORA_HASTA > RANGO_HASTA
        //     || ($horaDesde > $horaDesdeRango && $horaDesde < $horaHastaRango && $horaHasta > $horaHastaRango)) {
        //     return true;
        // }

        // //Mismo chequeo pero para si el Rango a chequear fuese el DÍA SIGUIENTE completamente
        // if (!$this->diaSiguienteNoLaborable &&
        //     //Mayores a los extremos: HORA_DESDE < RANGO_DESDE && HORA_HASTA > RANGO_HASTA
        //     (($horaDesdeDiaSiguiente < $horaDesdeRango && $horaHastaDiaSiguiente > $horaHastaRango)
        //         //rangos coinciden extremos
        //         || ($horaDesdeDiaSiguiente === $horaDesdeRango && $horaHastaDiaSiguiente === $horaHastaRango)
        //         //rangos coincide inicio y HORA_HASTA > RANGO_HASTA
        //         || ($horaDesdeDiaSiguiente === $horaDesdeRango && $horaHastaDiaSiguiente > $horaDesdeRango)
        //         //rangos coincide fin y HORA_DESDE < RANGO_DESDE
        //         || ($horaDesdeDiaSiguiente < $horaHastaRango && $horaHastaDiaSiguiente === $horaHastaRango)
        //         //HORA_DESDE < RANGO_DESDE && HORA_HASTA > RANGO_DESDE && HORA_HASTA < RANGO_HASTA
        //         || ($horaDesdeDiaSiguiente < $horaDesdeRango && $horaHastaDiaSiguiente > $horaDesdeRango && $horaHastaDiaSiguiente < $horaHastaRango)
        //         //HORA_DESDE > RANGO_DESDE && HORA_DESDE < RANGO_HASTA && HORA_HASTA > RANGO_DESDE && HORA_HASTA < RANGO_HASTA
        //         || ($horaDesdeDiaSiguiente > $horaDesdeRango && $horaDesdeDiaSiguiente < $horaHastaRango && $horaHastaDiaSiguiente > $horaDesdeRango && $horaHastaDiaSiguiente < $horaHastaRango)
        //         //HORA_DESDE > RANGO_DESDE && HORA_DESDE < RANGO_HASTA && HORA_HASTA > RANGO_HASTA
        //         || ($horaDesdeDiaSiguiente > $horaDesdeRango && $horaDesdeDiaSiguiente < $horaHastaRango && $horaHastaDiaSiguiente > $horaHastaRango))) {
        //     return true;
        // }

        // return false;
    }

    /**
     * Verifica que si TODO el rango de horario ingresado se encuentra DENTRO de otro rango de horario.
     */
    public function estaDentroDeRango(RangoTiempo $rango): bool
    {
        $this->setUnixTimestamps($rango);

        $horaDesde = $this->horaDesde;
        $horaHasta = $this->horaHasta;

        $horaDesdeDiaSiguiente = $this->horaDesdeDiaSiguiente;
        $horaHastaDiaSiguiente = $this->horaHastaDiaSiguiente;

        $horaDesdeRango = $rango->horaDesde;
        $horaHastaRango = $rango->horaHasta;

        if ($rango->horaDesdeString === $rango->horaHastaString) {
            return true;
        }
        //Chequeo dentro de rango
        if (
            $horaDesde >= $horaDesdeRango &&
            $horaDesde <= $horaHastaRango &&
            $horaHasta >= $horaDesdeRango &&
            $horaHasta <= $horaHastaRango
        ) {
            return true;
        }

        //Chequeo dentro de rango de día siguiente
        if (
            $horaDesdeDiaSiguiente < $horaHastaDiaSiguiente &&
            $horaDesdeDiaSiguiente >= $horaDesdeRango &&
            $horaDesdeDiaSiguiente <= $horaHastaRango &&
            $horaHastaDiaSiguiente >= $horaDesdeRango &&
            $horaHastaDiaSiguiente <= $horaHastaRango
        ) {
            return true;
        }

        return false;
    }
}
