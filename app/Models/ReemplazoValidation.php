<?php

namespace App\Models;

use App\Models\Fecha;
use App\Models\Dependencia;

trait ReemplazoValidation
{
    public function validate()
    {
        if ($this->hasEfectivaPrestacion()) {
            return 'El Reemplazo ya posee una Efectiva Prestación presentada';
        }

        if (!$this->hasPuesto('puestoReemplazado')) {
            return 'El Agente Titular no posee Puesto abierto';
        }

        if (!$this->hasPuesto('puestoReemplazante')) {
            return 'El Agente Reemplazante no posee Puesto abierto';
        }

        if (!$this->isPlantaHabilitada('puestoReemplazado')) {
            return "La planta del agente Titular no está habilitada para realizar Reemplazos";
        }

        if (!$this->isPlantaHabilitada('puestoReemplazante')) {
            return "La planta del agente Reemplazante no está habilitada para realizar Reemplazos";
        }

        if (!$this->dependenciaAgentePerteneceAEfector()) {
            return "El Agente Titular no pertenece al Efector elegido.";
        }

        $superaLimiteMensualHoras = $this->superaHorasMensualesAgente();
        if (is_string($superaLimiteMensualHoras)) {
            return $superaLimiteMensualHoras;
        }
        return true;
    }

    public function hasEfectivaPrestacion(): bool
    {
        return $this->reemplazo->efectivaPrestacionReemplazos->count() > 0;
    }

    public function hasPuesto($tipoPuesto = 'puestoReemplazante'): string
    {
        return isset($this->reemplazo->$tipoPuesto) && is_null($this->reemplazo->$tipoPuesto->fhasta);
    }

    /**
     * Bloqueo la creacion de reemplazos para los residentes, residentes nacionales
     * y en el caso de titular del reemplazo tampoco podrán los transitorios ni cobertura de cargo.
     * @param $attribute
     * @param $params
     */
    public function isPlantaHabilitada($tipoPuesto = 'puestoReemplazante'): bool
    {
        return isset($this->reemplazo->$tipoPuesto->idtipo_planta)
                && $this->reemplazo->isPlantaPermitida($this->reemplazo->$tipoPuesto->idtipo_planta, $tipoPuesto);
    }

    /**
     * Chequeo que el Titular del Reemplazo sea del Efector seleccionado
     * @param $attribute
     * @param $params
     */
    public function dependenciaAgentePerteneceAEfector()
    {
        //chequeo que existan los objetos
        if (!isset($this->reemplazo->dependencia)) {
            return false;
        }
        $idDependenciaPadre = $this->reemplazo->dependencia->getIdPadre();
        $dependencia = Dependencia::where('iddependencia', $idDependenciaPadre)
                                    ->first();
        //Busco las dependencias de la AO si es de la red sino de un hospital central
        return in_array($this->reemplazo->puestoReemplazado->iddependencia, $dependencia->getIdsDescendencia());
    }

    public function superaHorasMensualesAgente()
    {
        $fecha = new Fecha($this->reemplazo->fdesde, $this->reemplazo->fhasta);
        $horasNuevas = $fecha->getDiffDias() * 4;
        return $this->reemplazo->puestoReemplazante->superaHorasMensualesAgente('reemplazos', $horasNuevas, $this->reemplazo->fdesde, $this->reemplazo->fhasta);
    }
}
