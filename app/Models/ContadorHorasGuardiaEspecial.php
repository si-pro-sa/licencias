<?php
namespace App\Models;

use App\Models\GuardiaLinea;
use App\Models\ContadorHoras;
use Illuminate\Support\Collection;

class ContadorHorasGuardiaEspecial extends ContadorHoras
{
    /**
     * Retorna el listado de altas relacionadas al puesto
     * @return array|mixed|null
     */
    public function getFormularios(): Collection
    {
        return GuardiaLinea::whereHas('puesto', function ($q) {
            $q->where('idagente', $this->getIdAgente());
        })
        ->where('aprobado', true)
        ->whereIn('idguardia_tipo_estado_linea', [1, 3, 4])
        ->whereHas('guardia', function ($q) {
            $q->when($this->isPeriodoSolo() === true, function ($que) {
                $que->where('idperiodo', $this->getIdPeriodo());
            }, function ($que) {
                $que->where('fecha', '>=', $this->getFechaDesde())
                    ->where('fecha', '<=', $this->getFechaHasta());
            })
                ->whereIn('idtipo_guardia', Guardia::$tiposGuardiaNoSumanHs);
        })
        ->get();
    }

    public function contarHoras() : void
    {
        foreach ($this->getFormularios() as $linea) {
            $this->setFechas($linea->guardia->fecha, $linea->guardia->fecha);
            $this->agregarHorasDiarias($linea->getHorasGuardia(), $linea->guardia->idtipo_campania);
        }
    }

    public function setHorasNuevas(): void
    {
    }

    public function setHorasMaximas(): void
    {
        $this->horasMaximas = Guardia::getHorasMaximasCampaniaGuardia('combinada');
    }

    public function superaHorasMaximas(): bool
    {
        return $this->getTotalHorasPeriodo() > $this->horasMaximas;
    }

    public function getTotalRealHorasPeriodo()
    {
        return $this->getTotalHorasPeriodo() / 10 * 12;
    }

    public function getError(): string
    {
        if ($this->formulario->horasNuevas > 0 && $this->formulario->tipoFormulario === TipoFormularioEnum::GUARDIA) {
            return "El Agente supera las {$this->horasMaximas} horas de Guardia en Combinación de Campañas. 
                Con las {$this->formulario->horasNuevas} horas nuevas sumaría un total de {$this->getTotalHorasPeriodo()} horas.";
        }
        return "El Agente supera las {$this->horasMaximas} horas de Guardia en Combinación de Campañas. 
                Ahora posee un total de {$this->getTotalHorasPeriodo()} horas.";
    }
}
