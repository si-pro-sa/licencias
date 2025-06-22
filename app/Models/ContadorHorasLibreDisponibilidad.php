<?php
namespace App\Models;

use Carbon\Carbon;
use App\Models\LdAlta;
use App\Models\ContadorHoras;
use App\Models\TipoFormularioEnum;
use Illuminate\Support\Collection;

//@TODO restar horas de libres con baja presentada
class ContadorHorasLibreDisponibilidad extends ContadorHoras
{
    /**
     * Retorna el listado de altas relacionadas al puesto
     * @return array|mixed|null
     */
    public function getFormularios(): Collection
    {
        return LdAlta::select('idld_alta', 'fdesde', 'fhasta', 'idld_codigo')
        ->whereHas('puesto', function ($que) {
            $que->where('idagente', $this->getIdAgente());
        })
        ->where('idld_estado', 3)
        ->orderByDesc('fdesde')
        ->limit(50)
        ->get();
    }

    public function contarHoras() : void
    {
        foreach ($this->getFormularios() as $libre) {
            if (($this->isPeriodoSolo() && $libre->isVigente($this->getPeriodo())) || !$this->isPeriodoSolo()) {
                $this->setFechas($libre->fdesde, $libre->getFechaHasta());
                if ($libre->ldCodigo->horas_semanales > 0) {
                    $this->agregarHorasDiarias($libre->ldCodigo->horas_semanales / 30);
                }
            }
        }
    }

    public function setHorasNuevas(): void
    {
        if ($this->formulario->tipoFormulario === TipoFormularioEnum::LIBRE_DISPONIBILIDAD) {
            $this->setFechas($this->formulario->fechaDesdeNueva, $this->formulario->fechaHastaNueva);
            if ($this->formulario->horasNuevas > 0) {
                $this->agregarHorasDiarias($this->formulario->horasNuevas / 30);
            }
        }
    }

    public function superaHorasMaximas(): bool
    {
        return $this->getTotalHorasPeriodo() > $this->horasMaximas;
    }

    public function getCantidadFormularios(): int
    {
        $contador = 1;
        foreach ($this->getFormularios() as $libre) {
            if ($libre->isVigente($this->getPeriodo())) {
                $contador++;
            }
        }
        return $contador;
    }

    public function getError(): string
    {
        if ($this->formulario->horasNuevas > 0 && $this->formulario->tipoFormulario === TipoFormularioEnum::LIBRE_DISPONIBILIDAD) {
            return "El Agente supera las {$this->horasMaximas} horas de Libre Disponibilidad en el período {$this->getPeriodoName()}. 
                Con las {$this->formulario->horasNuevas} horas diarias nuevas sumaría un total de {$this->getTotalHorasPeriodo()} horas.";
        }
        return "El Agente supera las {$this->horasMaximas} horas de Libre Disponibilidad en el período {$this->getPeriodoName()}. 
                Ahora posee un total de {$this->getTotalHorasPeriodo()} horas.";
    }
}
