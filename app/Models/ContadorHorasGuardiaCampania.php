<?php

namespace App\Models;

use App\Models\GuardiaLinea;
use App\Models\TipoCampania;
use App\Models\ContadorHoras;
use Illuminate\Support\Collection;

class ContadorHorasGuardiaCampania extends ContadorHoras
{
    private $tipoCampania;
    private $horasGuardiaCampania;

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
            ->whereNotIn('idtipo_guardia', Guardia::$tiposGuardiaNoSumanHs);
        })
        ->get();
    }

    public function contarHoras(): void
    {
        foreach ($this->getFormularios() as $linea) {
            $this->setFechas($linea->guardia->fecha, $linea->guardia->fecha);
            $this->agregarHorasDiarias($linea->getHorasGuardia(), $linea->guardia->idtipo_campania);
        }
    }

    public function setHorasNuevas(): void
    {
        if ($this->formulario->tipoFormulario === TipoFormularioEnum::GUARDIA
            && !in_array($this->formulario->idtipo_guardia, Guardia::$tiposGuardiaNoSumanHs)) {
            $this->setFechas($this->formulario->fechaDesdeNueva, $this->formulario->fechaHastaNueva);
            $this->agregarHorasDiarias($this->formulario->getCantidadHorasGuardia(), $this->formulario->idtipo_campania);
        }
    }

    public function superaHorasMaximas(): bool
    {
        $tipoCampanias = TipoCampania::all();

        foreach ($tipoCampanias as $tipoCampania) {
            $this->tipoCampania = $tipoCampania->tipocampania;
            $this->horasMaximas = Guardia::getHorasMaximasCampaniaGuardia($tipoCampania->idtipo_campania);
            $this->horasGuardiaCampania = $this->getTotalHorasCampaniaPeriodo($tipoCampania->idtipo_campania);
            if ($this->horasGuardiaCampania > $this->horasMaximas) {
                return true;
            }
        }

        return false;
    }

    public function getError(): string
    {
        if ($this->formulario->horasNuevas > 0 && $this->formulario->tipoFormulario === TipoFormularioEnum::GUARDIA) {
            return "El Agente supera las {$this->horasMaximas} horas de Guardia en la Campaña {$this->tipoCampania} en el período {$this->getPeriodoName()}. 
                Con las {$this->formulario->horasNuevas} horas nuevas sumaría un total de {$this->horasGuardiaCampania} horas.";
        }
        return "El Agente supera las {$this->horasMaximas} horas de Guardia en la Campaña {$this->tipoCampania} en el período {$this->getPeriodoName()}. 
                Ahora posee un total de {$this->horasGuardiaCampania} horas.";
    }
}
