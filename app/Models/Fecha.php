<?php
namespace App\Models;

use Carbon\Carbon;

class Fecha
{
    private $fechaDesde;
    private $fechaHasta;

    private $carbonFechaDesde;
    private $carbonFechaHasta;

    private $unixFechaDesde;

    private static $nombresMes = [
        ['mes' => 'ENERO', 'month' => 'January'],
        ['mes' => 'FEBRERO', 'month' => 'February'],
        ['mes' => 'MARZO', 'month' => 'March'],
        ['mes' => 'ABRIL', 'month' => 'April'],
        ['mes' => 'MAYO', 'month' => 'May'],
        ['mes' => 'JUNIO', 'month' => 'June'],
        ['mes' => 'JULIO', 'month' => 'July'],
        ['mes' => 'AGOSTO', 'month' => 'August'],
        ['mes' => 'SETIEMBRE', 'month' => 'September'],
        ['mes' => 'SEPTIEMBRE', 'month' => 'September'],
        ['mes' => 'OCTUBRE', 'month' => 'October'],
        ['mes' => 'NOVIEMBRE', 'month' => 'November'],
        ['mes' => 'DICIEMBRE', 'month' => 'December'],
    ];

    public function __construct($fechaDesde, $fechaHasta = null)
    {
        $this->carbonFechaDesde = $this->parseFecha($fechaDesde);
        $this->fechaDesde = $this->carbonFechaDesde->format('Y-m-d');
        $this->unixFechaDesde = strtotime($this->carbonFechaDesde->format('Y-m-d'));
        if (isset($fechaHasta)) {
            $this->carbonFechaHasta = $this->parseFecha($fechaHasta);
            $this->fechaHasta = $this->carbonFechaHasta->format('Y-m-d');
        }
    }

    public function parseFecha(string $fecha, $ultimoDia = false): Carbon
    {
        $campos = explode('/', $fecha);
        if (ctype_alpha($fecha)) {
            $englishName = collect(self::$nombresMes)->firstWhere('mes', $fecha) ?? 'January';
            $day = $ultimoDia ? 'last' : 'first';
            return Carbon::parse("{$day} day of {$englishName}");
        } elseif (count($campos) < 3) {
            $anio = date('Y');
            $fecha = str_replace('/', '-', "{$fecha}/{$anio}");
            return Carbon::parse($fecha);
        }
        $fecha = str_replace('/', '-', $fecha);
        return Carbon::parse($fecha);
    }

    public static function parse(string $fecha, $ultimoDia = false): string
    {
        $campos = explode('/', $fecha);
        if (ctype_alpha($fecha)) {
            $englishName = collect(self::$nombresMes)->firstWhere('mes', $fecha) ?? 'January';
            $day = $ultimoDia ? 'last' : 'first';
            return Carbon::parse("{$day} day of {$englishName}")->format('Y-m-d');
        } elseif (count($campos) < 3) {
            $anio = date('Y');
            $fecha = str_replace('/', '-', "{$fecha}/{$anio}");
            return Carbon::parse($fecha)->format('Y-m-d');
        }
        $fecha = str_replace('/', '-', $fecha);
        return Carbon::parse($fecha)->format('Y-m-d');
    }

    public function getFechaDesde(): string
    {
        return $this->fechaDesde;
    }

    public function getFechaHasta(): string
    {
        return $this->fechaHasta;
    }

    public function getUnixTimestamp(): string
    {
        return $this->unixFechaDesde;
    }

    /**
     * @param $fechaDesde
     * @param $fechaHasta
     * @return int
     * @throws Exception
     */
    public function getDiffDias()
    {
        return (int) $this->carbonFechaDesde->diffInDays($this->carbonFechaHasta);
    }

    /**
     * Verifica si una fecha pertenece a un Rango dado
     * @param string $fecha
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return bool
     */
    public function fechaPerteneceARango(string $fecha) : bool
    {

        // Convert to timestamp
        $start_ts = strtotime($this->fechaDesde);
        $end_ts = strtotime($this->fechaHasta);
        $user_ts = strtotime($fecha);

        // Check that user date is between start & end
        return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }
    
    public function addMonths($months) : object
    {
        if (!$this->fechaDesde) {
            $this->fechaDesde = date('Y-m-d');
        }

        $date = Carbon::parse($this->fechaDesde);

        // We extract the day of the month as $start_day
        $start_day = $date->format('j');

        // We add 1 month to the given date
        $date->modify("+{$months} month");

        // We extract the day of the month again so we can compare
        $end_day = $date->format('j');

        if ($start_day != $end_day) {
            // The day of the month isn't the same anymore, so we correct the date
            $date->modify('last day of last month');
        }

        return $date;
    }
}
