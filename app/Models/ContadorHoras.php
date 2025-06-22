<?php
namespace App\Models;

use Carbon\Carbon;
use App\Models\Periodo;
use Illuminate\Support\Collection;

abstract class ContadorHoras
{
    protected $sumatoriaHorasDiarias;
    protected $fechaDesde;
    protected $fechaHasta;
    protected $horasMaximas;
    protected $puesto;

    public function __construct(
        protected ContadorHorasFormulario $formulario,
        protected ?ContadorHorasPuesto $contadorHorasPuesto = null,
    ) {
        $this->sumatoriaHorasDiarias = collect();
        $this->horasMaximas = 0;
        $this->setPuesto();
        $this->contarHoras();
        $this->setHorasMaximas();
        $this->setHorasNuevas();
    }

    abstract public function contarHoras() : void;

    abstract public function getFormularios() : Collection;

    abstract public function setHorasNuevas() : void;

    abstract public function superaHorasMaximas() : bool;

    abstract public function getError() : string;

    public function getIdAgente() : int
    {
        return $this->formulario->agente->idagente;
    }

    public function getCantidadFormularios(): int
    {
        return $this->formulario->horasNuevas > 0 ? ($this->getFormularios()->count() + 1) : $this->getFormularios()->count();
    }

    public function setPuesto() : void
    {
        $this->puesto = $this->formulario->agente->puestoActivo();
    }

    public function getIdPeriodo() : int
    {
        return $this->formulario->periodo->idperiodo;
    }

    public function getPeriodo() : Periodo
    {
        return $this->formulario->periodo;
    }

    public function getPeriodoName() : string
    {
        $periodoMes = substr($this->formulario->periodo->orden, 4, 2);
        $periodoAnio = substr($this->formulario->periodo->orden, 0, 4);
        return "{$periodoMes}/{$periodoAnio}";
    }

    public function getFechaDesde(): string
    {
        return $this->fechaDesde->format('Y-m-d');
    }

    public function getFechaHasta(): string
    {
        return $this->fechaDesde->format('Y-m-d');
    }

    public function getHorasMaximas(): int
    {
        return $this->horasMaximas;
    }

    public function isPeriodoSolo(): bool
    {
        return $this->formulario->periodoSolo;
    }

    public function setHorasMaximas(): void
    {
        // Reemplazante no permanente | Reemplazante no permanente - CP | Reemplazante no permanente - LD |
        // Reemplazante no permanente - CO | Reemplazante no permanente - CP-SEAC | Reemplazante no permanente - CP - COVID
        if (isset($this->puesto) &&
            $this->contadorHorasPuesto->getTotalHorasPeriodo() === 0 &&
            in_array($this->puesto->idtipo_planta, [3, 7, 9, 10, 11, 12])) {
            $this->horasMaximas = 240;
        } elseif ($this->contadorHorasPuesto->getHorasMaximas() === 120) {
            $this->horasMaximas = 120;
        } else {
            $this->horasMaximas = 0;
        }
    }

    public function setFechas(string $fechaDesde, string $fechaHasta = null): void
    {
        $this->fechaDesde = Carbon::parse($fechaDesde);
        //Seteo fecha final en caso de que no la tenga.
        $this->fechaHasta = Carbon::parse(isset($fechaHasta) && !empty($fechaHasta) ?
                                date('Y-m-d', strtotime($fechaHasta)) :
                                date('Y-m-d', strtotime('+12 months')));
    }

    public function getSumatoriaHorasPorDia(): Collection
    {
        return $this->sumatoriaHorasDiarias;
    }

    public function getTotalHorasPeriodo($ordenPeriodo = null): int
    {
        if (!isset($ordenPeriodo)) {
            $ordenPeriodo = $this->formulario->periodo->orden;
        }

        $cantidadHoras = $this->sumatoriaHorasDiarias
                                ->where('periodo', $ordenPeriodo)
                                ->sum('horas');

        //Fix para Reemplazos de 15 días
        if ($cantidadHoras === 64) {
            return 60;
        }

        return $cantidadHoras;
    }

    public function getTotalHorasCampaniaPeriodo(int $idtipo_campania): int
    {
        return $this->sumatoriaHorasDiarias->where('periodo', $this->formulario->periodo->orden)
                                            ->where('campania', $idtipo_campania)
                                            ->sum('horas');
    }

    public function agregarHorasDiarias(int $cantidadHoras, $idtipo_campania = null): void
    {
        while ($this->fechaDesde->lessThanOrEqualTo($this->fechaHasta)) {
            $horas = $cantidadHoras;
            //Corrijo cantidad de horas nuevas para libres y ree
            if ($this::class === 'App\Models\ContadorHorasLibreDisponibilidad' || $this::class === 'App\Models\ContadorHorasReemplazo') {
                //Meses con 31 días
                if ($this->fechaDesde->format('d') === '31') {
                    $horas = 0;
                }

                //Para febrero
                if (($this->fechaDesde->format('d') === '28' || $this->fechaDesde->format('d') === '29') && $this->fechaDesde->format('m') === '02') {
                    $horas = 0;
                }
            }
            //Horas existentes
            $dia['horas'] = 0;
            //Busco horas existentes en la fecha y borro la posición
            foreach ($this->sumatoriaHorasDiarias as $key => $sum) {
                if ($sum['periodo'] == $this->fechaDesde->format('Ym')
                    && $sum['fecha'] == $this->fechaDesde->format('Y-m-d')
                    && $sum['campania'] == $idtipo_campania) {
                    $dia = $sum;
                    $this->sumatoriaHorasDiarias->forget($key);
                    break;
                }
            }

            //Asigno nuevamente sumatoria de horas para fecha
            $this->sumatoriaHorasDiarias->push([
                'periodo' => $this->fechaDesde->format('Ym'),
                'fecha' => $this->fechaDesde->format('Y-m-d'),
                'horas' => $horas + $dia['horas'],
                'campania' => $idtipo_campania
            ]);

            $this->fechaDesde->addDay();
        }
    }
}
