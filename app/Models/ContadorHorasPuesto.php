<?php
namespace App\Models;

use Carbon\Carbon;
use App\Models\ContadorHoras;
use Illuminate\Support\Collection;
use App\Models\HorasMaximasGrupoFuncionEnum;

//@TODO restar horas de libres con baja presentada
class ContadorHorasPuesto extends ContadorHoras
{
    public function getFormularios(): Collection
    {
        if (isset($this->puesto)) {
            $horariosPuesto = $this->puesto->horarios()->get();
            if (isset($horariosPuesto) && $horariosPuesto->count() > 0) {
                foreach ($this->puesto->puestosAdicionales as $puestoAdicional) {
                    $horariosPuesto = $horariosPuesto->merge($puestoAdicional->horarios()->get());
                }
                return $horariosPuesto;
            }

            if (isset($this->puesto->horario_historico)) {
                return $this->puesto->horario_historico->get();
            }
        }
        return collect();
    }

    public function contarHoras() : void
    {
        $horarios = $this->getFormularios();
        $plantasQueSumanHoras = [1, 2, 4, 5, 6, 7, 11, 12];

        //horarios Puesto
        if ((isset($this->puesto, $horarios) && !isset($horarios->idtipo_horario) && $horarios->count() > 0)
            && (in_array($this->puesto->idtipo_planta, $plantasQueSumanHoras)
                || ($this->puesto->idtipo_planta === 3 && $this->formulario->agente->tieneCargoActivo()))) {
            foreach ($horarios as $h) {
                $fechaDesde = Carbon::parse($this->getPeriodo()->fdesde);
                $fechaHasta = Carbon::parse($this->getPeriodo()->fdesde);
                $fechaHasta->addDays($h->multiplicador_mensual - 1);
                $this->setFechas($fechaDesde->format('Y-m-d'), $fechaHasta->format('Y-m-d'));
                $rango = new RangoTiempo($h->hora_desde, $h->hora_hasta);
                $this->agregarHorasDiarias($rango->getDiffHoras());
            }
        }

        //Horario Histórico
        if (isset($this->puesto, $horarios, $horarios->idtipo_horario)
            && (in_array($this->puesto->idtipo_planta, $plantasQueSumanHoras)
                || ($this->puesto->idtipo_planta === 3 && $this->formulario->agente->tieneCargoActivo()))) {
            $fechaDesde = Carbon::parse($this->getPeriodo()->fdesde);
            $fechaHasta = Carbon::parse($this->getPeriodo()->fdesde);
            $fechaHasta->addDays(29);
            $this->setFechas($fechaDesde, $fechaHasta);
            $this->agregarHorasDiarias(4);
        }
    }

    public function getTotalHorasPeriodo($ordenPeriodo = null): int
    {
        if (!isset($ordenPeriodo)) {
            $ordenPeriodo = $this->formulario->periodo->orden;
        }

        return $this->sumatoriaHorasDiarias
                    ->where('periodo', $ordenPeriodo)
                    ->sum('horas');
    }

    public function setHorasNuevas(): void
    {
        if ($this->formulario->tipoFormulario === TipoFormularioEnum::CARGO) {
            $this->setFechas($this->formulario->fechaDesdeNueva, $this->formulario->fechaHastaNueva);
            $this->agregarHorasDiarias($this->formulario->horasNuevas / 30);
        }
    }

    public function setHorasMaximas(): void
    {
        if (isset($this->puesto)) {
            $grupo = GrupoFuncion::select('idtipo_grupo_funcion')
                                    ->whereIn('idtipo_grupo_funcion', HorasMaximasGrupoFuncionEnum::getIdGrupos())
                                    ->where('idtipo_funcion', $this->puesto->idtipo_funcion)
                                    ->first();
            if (isset($grupo)) {
                $this->horasMaximas = HorasMaximasGrupoFuncionEnum::fromId((int) $grupo->idtipo_grupo_funcion)->value;
            } else {
                $this->horasMaximas = 120;
            }
        }
    }

    public function superaHorasMaximas(): bool
    {
        return $this->getTotalHorasPeriodo() > $this->horasMaximas;
    }

    public function getError(): string
    {
        return "El Agente debe tener {$this->horasMaximas} horas de Puesto. 
                Tiene un total de {$this->getTotalHorasPeriodo()} horas en el período {$this->getPeriodoName()}.";
    }
}
