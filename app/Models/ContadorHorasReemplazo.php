<?php
namespace App\Models;

use App\Models\Reemplazo;
use App\Models\ContadorHoras;
use App\Models\TipoFormularioEnum;
use Illuminate\Support\Collection;

//@TODO restar horas de libres con baja presentada
class ContadorHorasReemplazo extends ContadorHoras
{
    /**
     * Retorna el listado de altas relacionadas al puesto
     * @return array|mixed|null
     */
    public function getFormularios(): Collection
    {
        return Reemplazo::select('fdesde', 'fhasta')
        ->where('idagente_reemplazante', $this->getIdAgente())
        ->when($this->isPeriodoSolo(), function ($query) {
            return $query->where('idperiodo', $this->getIdPeriodo());
        }, function ($que) {
            return $que->where('fdesde', '>=', $this->getFechaDesde());
        })
        ->where('aprobado', true)
        ->where('estado', 1)
        ->orderByDesc('fdesde')
        ->get();
    }

    public function contarHoras() : void
    {
        foreach ($this->getFormularios() as $reemplazo) {
            $this->setFechas($reemplazo->fdesde, $reemplazo->fhasta);
            $this->agregarHorasDiarias(4);
        }
    }

    public function setHorasNuevas(): void
    {
        if ($this->formulario->tipoFormulario === TipoFormularioEnum::REEMPLAZO) {
            $this->setFechas($this->formulario->fechaDesdeNueva, $this->formulario->fechaHastaNueva);
            if ($this->formulario->horasNuevas > 0) {
                $this->agregarHorasDiarias(4);
            }
        }
    }

    public function superaHorasMaximas(): bool
    {
        return $this->getTotalHorasPeriodo() > $this->horasMaximas;
    }

    public function getError(): string
    {
        if ($this->formulario->horasNuevas > 0 && $this->formulario->tipoFormulario === TipoFormularioEnum::REEMPLAZO) {
            return "El Agente supera las {$this->horasMaximas} horas de Reemplazo. 
                Con las {$this->formulario->horasNuevas} horas diarias nuevas sumaría un total de {$this->getTotalHorasPeriodo()} horas en el período {$this->getPeriodoName()}.";
        }
        return "El Agente supera las {$this->horasMaximas} horas de Reemplazo en el período {$this->getPeriodoName()}. 
                Ahora posee un total de {$this->getTotalHorasPeriodo()} horas.";
    }
}
