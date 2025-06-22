<?php
namespace App\Models;

use App\Models\TipoFormularioEnum;
use App\Models\ContadorHorasPuesto;
use App\Models\ContadorHorasGuardia;
use App\Models\ContadorHorasReemplazo;
use App\Models\ContadorHorasGuardiaCampania;
use App\Models\ContadorHorasLibreDisponibilidad;

class ValidacionHorasMaximas
{
    private $contadorHorasFormulario;
    private $contadorHorasGuardia;
    private $contadorHorasGuardiaCampania;
    private $contadorHorasLibreDisponibilidad;
    private $contadorHorasReemplazo;
    private $contadorHorasPuesto;
    private $puestoActivo;

    public function __construct(
        int $idagente,
        string $tipoFormulario,
        string $fechaDesdeNueva,
        ?string $fechaHastaNueva = null,
        int $horasNuevas = 0,
        int $idtipo_campania = 0,
        int $idtipo_guardia = 0
    ) {
        $this->fechaDesde = $fechaDesdeNueva;
        $this->fechaHasta = $fechaHastaNueva;
        $this->horasNuevas = $horasNuevas;

        $agente = Agente::find($idagente);
        $this->puestoActivo = $agente->puestoActivo();
        $periodo = Periodo::getPeriodoConFecha($fechaDesdeNueva);
        $this->contadorHorasFormulario = new ContadorHorasFormulario($agente, $periodo, $fechaDesdeNueva, $fechaHastaNueva, $horasNuevas, TipoFormularioEnum::fromString($tipoFormulario), true, $idtipo_guardia, $idtipo_campania);

        $this->contadorHorasPuesto = new ContadorHorasPuesto($this->contadorHorasFormulario);
        $this->contadorHorasGuardia = new ContadorHorasGuardia($this->contadorHorasFormulario, $this->contadorHorasPuesto);
        $this->contadorHorasGuardiaCampania = new ContadorHorasGuardiaCampania($this->contadorHorasFormulario, $this->contadorHorasPuesto);
        $this->contadorHorasLibreDisponibilidad = new ContadorHorasLibreDisponibilidad($this->contadorHorasFormulario, $this->contadorHorasPuesto);
        $this->contadorHorasReemplazo = new ContadorHorasReemplazo($this->contadorHorasFormulario, $this->contadorHorasPuesto);
    }

    //VALIDACIÓN
    public function agenteSuperaLimiteHorasMensuales()
    {
        if (!isset($this->puestoActivo, $this->puestoActivo->idtipo_planta)) {
            return 'El Agente no posee un Puesto Activo';
        }

        if ($this->contadorHorasPuesto->superaHorasMaximas()) {
            return $this->contadorHorasPuesto->getError();
        }

        if ($this->contadorHorasLibreDisponibilidad->superaHorasMaximas()) {
            return $this->contadorHorasLibreDisponibilidad->getError();
        }

        if ($this->contadorHorasReemplazo->superaHorasMaximas()) {
            return $this->contadorHorasReemplazo->getError();
        }

        if ($this->contadorHorasGuardia->superaHorasMaximas()) {
            return $this->contadorHorasGuardia->getError();
        }

        if ($this->contadorHorasGuardiaCampania->superaHorasMaximas()) {
            return $this->contadorHorasGuardiaCampania->getError();
        }

        $periodos = Periodo::getIdsPeriodos($this->fechaDesde, $this->fechaHasta);
        foreach ($periodos['ordenes'] as $ordenPeriodo) {
            $periodoMes = substr($ordenPeriodo, 4, 2);
            $periodoAnio = substr($ordenPeriodo, 0, 4);
            $horasPuesto = $this->contadorHorasPuesto->getTotalHorasPeriodo();
            $horasLD = $this->contadorHorasLibreDisponibilidad->getTotalHorasPeriodo($ordenPeriodo);
            $horasGuardia = $this->contadorHorasGuardia->getTotalHorasPeriodo($ordenPeriodo);
            $horasReemplazo = $this->contadorHorasReemplazo->getTotalHorasPeriodo($ordenPeriodo);
            $horasTotales = $horasGuardia + $horasLD + $horasReemplazo + $horasPuesto;
            $horasTotalesSinGuardia = $horasGuardia + $horasLD + $horasReemplazo + $horasPuesto;
            $horasMaximasTotales = 240;

            //Control para formularios presentados de LD, REE y PUESTO
            if (($horasLD + $horasReemplazo + $horasPuesto) > $horasMaximasTotales) {
                return "El Agente supera las {$horasMaximasTotales} horas totales de formularios.
                            Suma un total de {$horasTotalesSinGuardia} horas en el período {$periodoMes}/{$periodoAnio}.
                            (Puesto: {$horasPuesto} | LD: {$horasLD} | Reemplazos: {$horasReemplazo}).";
            }

            //Control para cargas del módulo de Guardias
            if ($this->contadorHorasFormulario->tipoFormulario === TipoFormularioEnum::GUARDIA) {
                if (in_array($this->puestoActivo->idtipo_planta, [4, 5]) && ($horasLD > 0 || $horasReemplazo > 0)) {
                    return "El Agente no puede tener Libre Disponibilidad, Reemplazos ni Coberturas.
                            (Puesto: {$horasPuesto} | LD: {$horasLD} | Guardia: {$horasGuardia} | Reemplazos: {$horasReemplazo}).";
                }

                if ($horasTotales > 240 && $this->formulario->idtipo_campania === 1) {
                    return 'No puede tener más horas de Guardias Normales';
                }
            }

            $horasMaximasTotales = $this->contadorHorasGuardia->getHorasMaximas() + 120;

            if ($horasTotales > $horasMaximasTotales) {
                return "El Agente supera las {$horasMaximasTotales} horas totales de formularios.
                            Con las horas diarias nuevas sumaría un total de {$horasTotales} horas en el período {$periodoMes}/{$periodoAnio}.
                            (Puesto: {$horasPuesto} | LD: {$horasLD} | Guardia: {$horasGuardia} | Reemplazos: {$horasReemplazo}).";
            }
        }

        return false;
    }
}
