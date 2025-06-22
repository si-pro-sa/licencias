<?php
namespace App\Impresiones;

use App\Impresiones\PDF;
use App\Models\Agente;
use App\Models\Candidato;

class FormularioCargoPDF extends PDF
{
    private $campania;

    private $cambioEstado;

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
        $this->campania = $this->cambioEstado->cargo->tipoCampania->tipocampania ?? null;
        $this->dotacionEfector = $this->cambioEstado->cargo->dotacionToString();
        $this->dotacionServicio = $this->cambioEstado->cargo->dotacionToString(false);
        $this->horasEfectores = $this->cambioEstado->cargo->getCantidadHorasEfectores();
        $this->diagramacionHabitual = $this->cambioEstado->cargo->diagramacionToString();
        $this->diagramacionCobertura = $this->cambioEstado->cargo->diagramacionCoberturaToString();

        $this->titulosPagina[] = $this->cambioEstado->idcargo_tipo_formulario === 1 ?
        ('F-3.1 - SOLIC. DE COBERTURA DE CARGO - CAMPAÑA: ' . ($this->campania ?? 'NORMAL')) :
        ('F-3.1 - SOLIC. DE CONTINUIDAD COBERTURA DE CARGO - CAMPAÑA: ' . ($this->campania ?? 'NORMAL'));

        $this->inicioTabla = 10;
        $this->SetFont('arial', 'B', 10);
        $this->SetMargins(10, 5);
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
        $this->createCeldaConTexto(0, 62, 1, 'ORGANISMO', true);
        $this->createCeldaConTexto(62, 158, 1, $this->cambioEstado->cargo->efector->dependencia);
        
        //Reemplazante
        $this->newRow();
        $this->createCeldaConTexto(0, 60, 0.5, 'DATOS REEMPLAZANTE', true, false, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, 'APELLIDO Y NOMBRE', true);
        $this->createCeldaConTexto(62, 79, 1, 'DNI', true);
        $this->createCeldaConTexto(79, 99, 1, 'FECHA NAC', true);
        $this->createCeldaConTexto(99, 129, 1, 'FUNCIÓN', true);
        $this->createCeldaConTexto(129, 152, 1, 'TÍTULO', true);
        $this->createCeldaConTexto(152, 199, 1, 'ESPECIALIDAD', true);
        $this->createCeldaConTexto(199, 209, 1, 'NIVEL', true);
        $this->createCeldaConTexto(209, 239, 1, 'AGRUP.', true);
        $this->createCeldaConTexto(239, 278, 1, 'MODALIDAD', true);
        $this->createCeldaConTexto(278, 308, 1, 'FECHA INICIO PRESTACIÓN', true);
        $this->createCeldaConTexto(308, 335, 1, 'FECHA FINALIZACIÓN PRESTACIÓN', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, $this->cambioEstado->cargo->agentePropuesto->apellido_nombre);
        $this->createCeldaConTexto(62, 79, 1, $this->cambioEstado->cargo->agentePropuesto->documento);
        $this->createCeldaConTexto(79, 99, 1, $this->cambioEstado->cargo->agentePropuesto->fnacimiento->format('d/m/Y'));
        $this->createCeldaConTexto(99, 129, 1, $this->cambioEstado->cargo->tipoFuncion->tipofuncion);

//  Código que reemplaza lo comentado más abajo
        $agente = Agente::with('puestos')->documento($this->cambioEstado->cargo->agentePropuesto->documento)->first();
/* Modificación para que tome el título desde tabla recomendacion_candidato */
        $titulo_desc = "";
        if (isset($agente))
           {
           $titulo_desc = $agente->getNombreTituloCandidato();
           if (!isset($titulo_desc))
              {$titulo_desc = $agente->getUltimoTituloNombre();};
           }
           else
           {
           $candidato = Candidato::documento($this->cambioEstado->cargo->agentePropuesto->documento)->first();
           if (isset($candidato))
              {
              $titulo_desc = $candidato->getNombreTituloCandidato();
              if (!isset($titulo_desc))
                 {$titulo_desc = $agente->getUltimoTituloNombre();};
              };
           };
/* FIN Modificación para que tome el título desde tabla recomendacion_candidato */
        if (strlen($titulo_desc)>20)
           {$titulo_desc = mb_substr($titulo_desc, 0, 20, 'utf8');};
        $this->createCeldaConTexto(129, 152, 1, $titulo_desc);
/*
        $titulo = $this->cambioEstado->cargo->titulo->titulo;
        if (strlen($titulo) > 20) {
            $titulo = mb_substr($titulo, 0, 20, 'utf8');
        }
        $this->createCeldaConTexto(129, 152, 1, $titulo);
*/
        
        $this->createCeldaConTexto(129, 152, 1, $titulo_desc);
        $this->createCeldaConTexto(152, 199, 1, $this->cambioEstado->cargo->tipoEspecialidad->tipoespecialidad);
        $this->createCeldaConTexto(199, 209, 1, $this->cambioEstado->cargo->tipoNivel->tiponivel);
        $this->createCeldaConTexto(209, 239, 1, strtoupper($this->cambioEstado->cargo->tipoAgrupamiento->tipoagrupamiento));
        $this->createCeldaConTexto(239, 278, 1, $this->cambioEstado->cargoTipoFormulario->cargotipo_formulario);
        $this->createCeldaConTexto(278, 308, 1, $this->cambioEstado->fecha_desde_formateada);
        $this->createCeldaConTexto(308, 335, 1, $this->cambioEstado->fecha_hasta_formateada);
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'EDAD', true, true, 'L');
        $this->createCeldaConTexto(30, 62, 1, $this->cambioEstado->cargo->agentePropuesto->edad, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'CARGO VACANTE', true, true, 'L');
        $this->createCeldaConTexto(30, 62, 1, $this->cambioEstado->cargo->tipoCargo->tipocargo_corto === 'CV' ? 'SI' : 'NO', false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 30, 1, 'DOTACIÓN EFECTOR', true, true, 'L');
        $this->createCeldaConTexto(30, 335, 1, $this->dotacionEfector ?? '0', false, true, 'L');

        //Reemplazado
        $this->newRow();
        $this->createCeldaConTexto(0, 60, 0.5, 'DATOS REEMPLAZADO', true, false, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, 'APELLIDO Y NOMBRE', true);
        $this->createCeldaConTexto(62, 79, 1, 'DNI', true);
        $this->createCeldaConTexto(79, 98, 1, 'NIVEL', true);
        $this->createCeldaConTexto(98, 128, 1, 'FUNCIÓN', true);
        $this->createCeldaConTexto(128, 158, 1, 'AGRUPAMIENTO', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, $this->cambioEstado->cargo->cargoReemplazado->apellido_nombre ?? '');
        $this->createCeldaConTexto(62, 79, 1, $this->cambioEstado->cargo->cargoReemplazado->documento ?? '');
        $this->createCeldaConTexto(79, 98, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoNivel->tiponivel ?? '');
        $this->createCeldaConTexto(98, 128, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoFuncion->tipofuncion ?? '');
        $this->createCeldaConTexto(128, 158, 1, $this->cambioEstado->cargo->cargoReemplazado->tipoAgrupamiento->tipoagrupamiento ?? '');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, 'CAUSAL', true);
        $this->createCeldaConTexto(62, 158, 1, mb_strtoupper($this->cambioEstado->cargo->tipoCese->tipocese ?? ''), false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 1, 0.5, ' ', true, false);
        
        //Detalle
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 1, 'DETALLE', true);
        $this->createCeldaConTexto(62, 335, 1, 'INFORMACIÓN ADICIONAL INDISPENSABLE PARA LA AUTORIZACIÓN/RECHAZO DE LA SOLICITUD', true);
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 2, '1. DETALLE DEL SERVICIO DE EFECTIVO CUMPLIMIENTO', true, true, 'L');
        $this->createCeldaConTexto(62, 82, 2, 'UNIDAD ORG.', true, true, 'L');
        $this->createCeldaConTexto(82, 178, 2, $this->horasEfectores, false, true, 'L');
        $this->createCeldaConTexto(178, 198, 2, 'DOTACIÓN DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(198, 335, 2, $this->dotacionServicio, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 2, '2. DIAGRAMACIÓN HABITUAL DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(62, 335, 2, $this->diagramacionHabitual, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 2, '3. DIAGRAMACIÓN DE LA COBERTURA SOLICITUADA', true, true, 'L');
        $this->createCeldaConTexto(62, 335, 2, $this->diagramacionCobertura, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 2, '4. TAREAS A REALIZAR Y PRODUCCIÓN ESPERADA', true, true, 'L');
        $this->createCeldaConTexto(62, 335, 2, $this->cambioEstado->cargo->produccion_esperada, false, true, 'L');
        $this->newRow();
        $this->createCeldaConTexto(0, 62, 2, '5. RAZONES DE LA BRECHA EN LA PRESTACIÓN DEL SERVICIO', true, true, 'L');
        $this->createCeldaConTexto(62, 335, 2, $this->cambioEstado->cargo->razones_brecha, false, true, 'L');

        //Firmas
        $this->newRow();
        $this->createCeldaParaFirma(0, 1, 1, ' ', true, false);
        $this->newRow();
        $this->createCeldaParaFirma(0, 46, 1, '');
        $this->createCeldaParaFirma(47, 93, 1, '');
        $this->createCeldaParaFirma(94, 148, 1, '');
        $this->createCeldaParaFirma(149, 187, 1, '');
        $this->createCeldaParaFirma(188, 234, 1, '');
        $this->createCeldaParaFirma(235, 281, 1, '');
        $this->createCeldaParaFirma(282, 334, 1, '');
        $this->newRow();
        //$this->createCeldaParaFirma(0, 46, 1, 'JEFE DE PERSONAL', false, false, 'C');
        $this->createCeldaParaFirma(0, 46, 1, 'REFERENTE DE RRHH', false, false, 'C');
        $this->createCeldaParaFirma(47, 93, 1, 'DIRECTOR', false, false, 'C');
        $this->createCeldaParaFirma(94, 148, 1, 'JEFE D. S. e I.', false, false, 'C');
        $this->createCeldaParaFirma(149, 187, 1, 'DIRECTOR P.D. RRHH', false, false, 'C');
        $this->createCeldaParaFirma(188, 234, 1, 'DIRECTOR DE RRHH', false, false, 'C');
        $this->createCeldaParaFirma(235, 281, 1, 'SECRETARIO DE ESTADO', false, false, 'C');
        $this->createCeldaParaFirma(282, 334, 1, 'MINISTRO DE SALUD', false, false, 'C');
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
            $this->SetFont('Arial', 'B', 7);
        } else {
            $this->SetFillColor(255, 255, 255);
            $this->SetFont('Arial', '', 8);
        }

        $cantlineas = $this->contarLineas($total - $comienzo, (7 * $cantidadLineas), utf8_decode($texto), 0, $alineacionTexto);
        $alto = 7 / $cantlineas;
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $comienzo);
        $this->MultiCell($total - $comienzo, ($alto * $cantidadLineas), utf8_decode($texto), ($bordeMarcado ? 1 : 0), $alineacionTexto);
    }

    /**
     * @param $medidas
     * @param $medida
     * @param $texto
     */
    private function createCeldaParaFirma($comienzo, $total, $cantidadLineas = 1, $texto, $isTitulo = false, $bordeMarcado = true, $alineacionTexto = 'C')
    {
        if ($isTitulo) {
            $this->SetFillColor(255, 245, 156);
            $this->SetFont('Arial', 'B', 7);
        } else {
            $this->SetFillColor(255, 255, 255);
            $this->SetFont('Arial', '', 7);
        }

        $cantlineas = $this->contarLineas($total - $comienzo, (7 * $cantidadLineas), utf8_decode($texto), 0, $alineacionTexto);
        $alto = $isTitulo ? (12 / $cantlineas) : (4 / $cantlineas);
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $comienzo);
        $this->MultiCell($total - $comienzo, ($alto * $cantidadLineas), utf8_decode($texto), ($bordeMarcado ? 'B' : 0), $alineacionTexto);
    }

    /**
     * @param $texto
     */
    private function createTituloPagina($texto)
    {
        $this->setX($this->getX());
        $this->MultiCell(335, 5, utf8_decode($texto), null, 'C');
    }
}
