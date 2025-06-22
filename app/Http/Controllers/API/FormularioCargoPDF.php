<?php

namespace App\Impresiones;

use App\Impresiones\PDF;

class FormularioCargoPDF extends PDF
{
    /**
     * @var
     */
    private $cambioEstado;

    private $dotacion;

    private $dotacionEfector;

    private $dotacionServicio;

    private $horasEfectores;

    private $diagramacionHabitual;

    private $diagramacionCobertura;
    /**
     * Títulos de Tabla
     * @var array
     */
    private $titulosPagina = [];
    /**
     * Milímetros a Píxeles
     * @var
     */

    /**
     * Impresión de Efectiva Prestación de Guardias Comunes y Críticas constructor.
     * @param $campania
     * @param $periodo
     * @param $efector
     * @param $cambioEstado
     */
    public function __construct($cambioEstado)
    {
        parent::__construct();
        $this->cambioEstado = $cambioEstado;
        $this->dotacion = $this->cambioEstado->cargo->getDotacion();
        $this->dotacionEfector = $this->cambioEstado->cargo->getDotacionEfector();
        $this->dotacionServicio = $this->cambioEstado->cargo->getDotacionServicio();
        $this->horasEfectores = $this->cambioEstado->cargo->getCantidadHorasEfectores();
        $this->diagramacionHabitual = $this->cambioEstado->cargo->getDiagramacionHabitual();
        $this->diagramacionCobertura = $this->cambioEstado->cargo->getDiagramacionCobertura();

        $this->titulosPagina[] = $this->cambioEstado->idcargo_tipo_formulario === 1 ?
            'FORMULARIO 3.1 - SOLICITUD DE COBERTURA DE CARGO' :
            'FORMULARIO 3.2 - SOLICITUD DE CONTINUIDAD COBERTURA DE CARGO';

        $this->inicioTabla = 27.21;
        $this->SetFont('arial', 'B', 10);
        $this->SetMargins(37.79, 20);
        $this->AddPage('L', 'Legal');
        $this->Table();
    }

    /**
     * Header de la Página
     */
    public function Header()
    {
        $this->SetFont('Arial', '', 8);
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 8);
        $this->SetLineWidth(0.25);

        $this->drawLogo();

        $this->setY($this->inicioTabla);
        //Encabezado Página
        foreach ($this->titulosPagina as $titulo) {
            $this->createTituloPagina($titulo);
        }

        $this->showInfo($this->cambioEstado->created_at, $this->cambioEstado->idcargo_tipo_formulario === 1 ? 'F-3.1' : 'F-3.2');

        $this->newRow();
    }

    /**
     * Contenido de la Página
     */
    public function Table()
    {
        //Organismo
        $this->createCeldaConTexto(0, 30, 1, 'ORGANISMO', true);
        $this->createCeldaConTexto(30, 100, 1, $this->cambioEstado->cargo->efector->dependencia);

        //Reemplazante
        $this->newRow();
        $this->createCeldaConTexto(0, 60, 0.5, 'DATOS REEMPLAZANTE', true, false, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'APELLIDO Y NOMBRE', true);
        $this->createCeldaConTexto(30, 50, 1, 'DNI', true);
        $this->createCeldaConTexto(50, 70, 1, 'FECHA NAC', true);
        $this->createCeldaConTexto(70, 100, 1, 'FUNCIÓN', true);
        $this->createCeldaConTexto(100, 130, 1, 'TÍTULO', true);
        $this->createCeldaConTexto(130, 170, 1, 'ESPECIALIDAD', true);
        $this->createCeldaConTexto(170, 180, 1, 'NIVEL', true);
        $this->createCeldaConTexto(180, 200, 1, 'AGRUP.', true);
        $this->createCeldaConTexto(200, 220, 1, 'MODALIDAD', true);
        $this->createCeldaConTexto(220, 250, 1, 'FECHA INICIO PRESTACIÓN', true);
        $this->createCeldaConTexto(250, 277, 1, 'FECHA FINALIZACIÓN PRESTACIÓN', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, $this->cambioEstado->cargo->agentePropuesto->apellido_nombre);
        $this->createCeldaConTexto(30, 50, 1, $this->cambioEstado->cargo->agentePropuesto->documento);
        $this->createCeldaConTexto(50, 70, 1, $this->cambioEstado->cargo->agentePropuesto->fnacimiento->format('d/m/Y'));
        $this->createCeldaConTexto(70, 100, 1, $this->cambioEstado->cargo->tipoFuncion->tipofuncion);
        $this->createCeldaConTexto(100, 130, 1, $this->cambioEstado->cargo->titulo->titulo);
        $this->createCeldaConTexto(130, 170, 1, $this->cambioEstado->cargo->tipoEspecialidad->tipoespecialidad);
        $this->createCeldaConTexto(170, 180, 1, $this->cambioEstado->cargo->tipoNivel->tiponivel);
        $this->createCeldaConTexto(180, 200, 1, $this->cambioEstado->cargo->tipoAgrupamiento->tipoagrupamiento);
        $this->createCeldaConTexto(200, 220, 1, $this->cambioEstado->cargoTipoFormulario->cargotipo_formulario);
        $this->createCeldaConTexto(220, 250, 1, $this->cambioEstado->fecha_desde_formateada);
        $this->createCeldaConTexto(250, 277, 1, $this->cambioEstado->fecha_hasta_formateada);
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'EDAD', true, true, 'L');
        $this->createCeldaConTexto(30, 50, 1, $this->cambioEstado->cargo->agentePropuesto->edad, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'CARGO VACANTE', true, true, 'L');
        $this->createCeldaConTexto(30, 50, 1, $this->cambioEstado->cargo->cobertura_provisoria ? 'NO' : 'SI', false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'DOTACIÓN EFECTOR', true, true, 'L');
        $this->createCeldaConTexto(30, 277, 1, $this->dotacionEfector ?? '0', false, true, 'L');

        //Reemplazado
        $this->newRow();
        $this->createCeldaConTexto(0, 60, 0.5, 'DATOS REEMPLAZADO', true, false, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'APELLIDO Y NOMBRE', true);
        $this->createCeldaConTexto(30, 50, 1, 'DNI', true);
        $this->createCeldaConTexto(50, 70, 1, 'NIVEL', true);
        $this->createCeldaConTexto(70, 100, 1, 'FUNCIÓN', true);
        $this->createCeldaConTexto(100, 130, 1, 'AGRUPAMIENTO', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, $this->cambioEstado->cargo->cargoReemplazado->apellido_nombre ?? '');
        $this->createCeldaConTexto(30, 50, 1, $this->cambioEstado->cargo->cargoReemplazado->documento ?? '');
        $this->createCeldaConTexto(50, 70, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoNivel->tiponivel ?? '');
        $this->createCeldaConTexto(70, 100, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoFuncion->tipofuncion ?? '');
        $this->createCeldaConTexto(100, 130, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoAgrupamiento->tipoagrupamiento ?? '');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'CAUSA DE CESE LABORAL', true);
        $this->createCeldaConTexto(30, 130, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoCese->tipocese ?? '');
        $this->newRow();
        $this->createCeldaConTexto(0, 1, 0.5, ' ', true, false);

        //Detalle
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'DETALLE', true);
        $this->createCeldaConTexto(30, 277, 1, 'INFORMACIÓN ADICIONAL INDISPENSABLE PARA LA AUTORIZACIÓN/RECHAZO DE LA SOLICITUD', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 2, '1. DETALLE DEL SERVICIO DE EFECTIVO CUMPLIMIENTO', true, true, 'L');
        $this->createCeldaConTexto(30, 60, 2, 'UNIDAD ORGANIZATIVA', true, true, 'L');
        $this->createCeldaConTexto(60, 110, 2, $this->horasEfectores, false, true, 'L');
        $this->createCeldaConTexto(110, 140, 2, 'DOTACIÓN DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(140, 277, 2, $this->dotacionServicio, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 2, '2. DIAGRAMACIÓN HABITUAL DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(30, 277, 2, $this->diagramacionHabitual, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 2, '3. DIAGRAMACIÓN DE LA COBERTURA SOLICITUADA', true, true, 'L');
        $this->createCeldaConTexto(30, 277, 2, $this->diagramacionCobertura, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, '4. TAREAS A REALIZAR Y PRODUCCIÓN ESPERADA', true, true, 'L');
        $this->createCeldaConTexto(30, 277, 1, $this->cambioEstado->cargo->produccion_esperada, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, '5. RAZONES DE LA BRECHA EN LA PRESTACIÓN DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(30, 277, 1, $this->cambioEstado->cargo->razones_brecha, false, true, 'L');

        //Firmas
        $this->newRow();
        $this->createCeldaConTexto(0, 1, 0.5, ' ', true, false);
        $this->newRow();
        $this->createCeldaConTexto(0, 39, 1, '');
        $this->createCeldaConTexto(39, 78, 1, '');
        $this->createCeldaConTexto(78, 117, 1, '');
        $this->createCeldaConTexto(117, 156, 1, '');
        $this->createCeldaConTexto(156, 195, 1, '');
        $this->createCeldaConTexto(195, 234, 1, '');
        $this->createCeldaConTexto(234, 277, 1, '');
        $this->newRow();
        $this->createCeldaConTexto(0, 39, 1, 'JEFE DE PERSONAL', false, false, 'C');
        $this->createCeldaConTexto(39, 78, 1, 'REFERENTE DE RRHH', false, false, 'C');
        $this->createCeldaConTexto(78, 117, 1, 'DIRECTOR', false, false, 'C');
        $this->createCeldaConTexto(117, 156, 1, 'DIRECTOR D.P. RRHH', false, false, 'C');
        $this->createCeldaConTexto(156, 195, 1, 'DIRECTOR DE RRHH', false, false, 'C');
        $this->createCeldaConTexto(195, 234, 1, 'SECRETARIO DE ESTADO', false, false, 'C');
        $this->createCeldaConTexto(234, 277, 1, 'MINISTRO DE SALUD', false, false, 'C');
    }

    /**
     * @param $medidas
     * @param $medida
     * @param $texto
     */
    private function createCeldaConTexto($comienzo, $total, $cantidadLineas = 1, $texto, $isTitulo = false, $bordeMarcado = true, $alineacionTexto = 'C')
    {
        if ($isTitulo) {
            $this->SetFillColor(255, 245, 156);
            $this->SetFont('Arial', 'B', 5);
        } else {
            $this->SetFillColor(255, 255, 255);
            $this->SetFont('Arial', '', 5);
        }

        $cantlineas = $this->contarLineas($total - $comienzo, (7 * $cantidadLineas), utf8_decode($texto), 0, $alineacionTexto);
        $alto = 6 / $cantlineas;
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $comienzo);
        $this->MultiCell($total - $comienzo, ($alto * $cantidadLineas), utf8_decode($texto), ($bordeMarcado ? 1 : 0), $alineacionTexto);
    }

    /**
     * @param $texto
     */
    private function createTituloPagina($texto)
    {
        $this->setX($this->getX());
        $this->MultiCell(277, 5, utf8_decode($texto), null, 'C');
    }
}
