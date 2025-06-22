<?php
namespace App\Impresiones;

use App\Impresiones\PDF;

class FormularioCargoCampaniaPDF extends PDF
{
    /**
     * @var
     */
    private $campania;
    /**
     * @var
     */
    private $periodo;
    /**
     * @var
     */
    private $efector;
    /**
     * @var
     */
    private $servicio;
    /**
     * @var
     */
    private $cargos;
    /**
     * Títulos de Tabla
     * @var array
     */
    private $titulosPagina;
    /**
     * Medidas en milímetros
     * @var array
     */
    private $medidasTabla;
    /**
     * Títulos de Tabla
     * @var array
     */
    private $titulosTabla;
    /**
     * Milímetros a Píxeles
     * @var
     */

    /**
     * Impresión de Efectiva Prestación de Guardias Comunes y Críticas constructor.
     * @param $campania
     * @param $periodo
     * @param $efector
     * @param $cargos
     */
    public function __construct($cargos)
    {
        parent::__construct();
        $this->cargos = $cargos;
        $this->efector = $this->cargos[0]['cargo']['efector']['codigo_nombre'] ?? null;
        $this->campania = $this->cargos[0]['cargo']['tipo_campania']['tipocampania'] ?? null;
        $this->periodo = $this->cargos[0]['periodo_desde']['periodo'] ?? null;

        if ($cargos[0]['cargo_tipo_formulario_excel']!="BAJA")
           {$this->titulosPagina = ['FORMULARIO 3.1 - SOLICITUD DE COBERTURA DE CARGO - CAMPAÑA: ' . ($this->campania ?? 'NORMAL')];}
           else
           {$this->titulosPagina = ['FORMULARIO 3.3 - SOLICITUD DE BAJA DE COBERTURA DE CARGO - CAMPAÑA: ' . ($this->campania ?? 'NORMAL')];};

        //Medidas en mm de cada columna
        $this->medidasTabla = [
            0,
            12,
            52,
            20, //12,
            34,
            12,
            42,
            21, //14,
            50,
            40,
            17, //15,
            17, //15,
            15, //37,
        ];

        //Realizo sumatoria de medidas de Tabla
        foreach ($this->medidasTabla as $key => $medida) {
            if ($key !== 0) {
                $this->medidasTabla[$key] += $this->medidasTabla[$key - 1];
            }
        }

        //Títulos de la tabla
        $this->titulosTabla = [
            'N°',
            'APELLIDO Y NOMBRE',
            'DNI',
            'FUNCIÓN',
            'NIVEL',
            'HORARIO',
            'AGRUPAMIENTO',
            'UNIDAD ORGANIZATIVA',
            'AREA OPERATIVA',
            'FECHA INICIO',
            'FECHA FIN',
            'VISADO',
        ];

        //Transformo a utf8_decode los valores de los títulos de cada columna
        $this->titulosTabla = array_map(function ($value) {
            return utf8_decode((string) $value);
        }, $this->titulosTabla);

        //Transformo a utf8_decode los valores del contenido
        $this->cargos = array_map(function ($value) {
            if (is_string($value)) {
                return utf8_decode($value);
            }
            if (is_array($value)) {
                foreach ($value as $key => $v) {
                    if (is_string($v)) {
                        $value[$key] = utf8_decode($v);
                    }
                }
            }
            return $value;
        }, $this->cargos);

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
            $this->createTitulo($titulo);
        }

        $this->SetFont('Arial', 'B', 7);
        $this->inicioTabla = $this->GetY();
        $this->SetFillColor(255, 245, 156);

        $this->showInfo(date('d/m/Y H:i'), 'F-3');

        //Encabezado tabla
        $this->setY($this->GetY()+5);
        $this->newRow();
        $this->SetFont('Arial', 'B', 8);
        foreach ($this->titulosTabla as $key => $value) {
            $this->createCeldaConTexto($this->medidasTabla, $key, $this->titulosTabla[$key]);
        }
    }

    /**
     * Contenido de la Página
     */
    public function Table()
    {
        $this->SetFillColor(255, 255, 255);
        $this->SetFont('arial', '', 8);
        for ($key = 0; $key < count($this->cargos); $key++) {
            $this->inicioTabla = $this->GetY();

            $this->createCeldaConTexto($this->medidasTabla, 0, $key + 1);
            $this->createCeldaConTexto($this->medidasTabla, 1, $this->cargos[$key]['cargo']['agente_propuesto']['apellido_nombre']);
            $this->createCeldaConTexto($this->medidasTabla, 2, $this->cargos[$key]['cargo']['agente_propuesto']['documento']);
            $this->createCeldaConTexto($this->medidasTabla, 3, $this->cargos[$key]['cargo']['tipo_funcion']['tipofuncion']);
            $this->createCeldaConTexto($this->medidasTabla, 4, $this->cargos[$key]['cargo']['tipo_nivel']['tiponivel']);
            $this->createCeldaConTexto($this->medidasTabla, 5, $this->cargos[$key]['cargo']['horarios'][0]['horario_cargo']);
            $this->createCeldaConTexto($this->medidasTabla, 6, $this->cargos[$key]['cargo']['tipo_agrupamiento']['tipoagrupamiento']);
//  cambio para mostrar la u. organizativa          $this->createCeldaConTexto($this->medidasTabla, 7, $this->cargos[$key]['cargo']['efector']['dependencia']);
            $this->createCeldaConTexto($this->medidasTabla, 7, $this->cargos[$key]['cargo']['servicio']['dependencia']);
            $this->createCeldaConTexto($this->medidasTabla, 8, $this->cargos[$key]['cargo']['efector']['area_operativa']);
            $this->createCeldaConTexto($this->medidasTabla, 9, $this->cargos[$key]['fecha_desde_formateada']);
            $this->createCeldaConTexto($this->medidasTabla, 10, $this->cargos[$key]['fecha_hasta_formateada']);
            $texto = $this->cargos[$key]['cargo_tipo_visado']['cargotipo_visado'];
            switch ($texto)
                   {
                   case "PENDIENTE" : $texto = ""; break;
                   case "RECIBIDO" : $texto = ""; break;
                   case "VISADO I APROBADO" : $texto = ""; break;
                   case "VISADO I DESAPROBADO" : $texto = ""; break;
                   case "VISADO II APROBADO" : $texto = ""; break;
                   case "VISADO II DESAPROBADO" : $texto = ""; break;
                   case "VISADO III APROBADO" : $texto = "SI"; break;
                   };
            $this->createCeldaConTexto($this->medidasTabla, 11, $texto);
//Cambio valor 530 x 175 para que imprima la hoja muestre las firmas y haga el salto de página
            if ($this->getY() > 170 && $key + 1 < count($this->cargos))
               {
               $this->addFirmas();
               $this->inicioTabla = 27.21;
               $this->AddPage('L', 'Legal');
               $this->inicioTabla = $this->inicioTabla+20;
               }
        }
    //Firmas
    $this->addFirmas();
    }

    private function addFirmas()
    {
    $this->newRow();
    $this->createCeldaParaFirma(0, 1, 1, ' ', true, false);
    $this->newRow();
    $this->createCeldaParaFirma(0, 1, 1, ' ', true, false);
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
    $this->createCeldaParaFirma(0, 46, 1, 'JEFE DE PERSONAL', false, false, 'C');
    $this->createCeldaParaFirma(47, 93, 1, 'REFERENTE DE RRHH', false, false, 'C');
    $this->createCeldaParaFirma(94, 148, 1, 'DIRECTOR', false, false, 'C');
    $this->createCeldaParaFirma(149, 187, 1, 'DIRECTOR D.P. RRHH', false, false, 'C');
    $this->createCeldaParaFirma(188, 234, 1, 'DIRECTOR DE RRHH', false, false, 'C');
    $this->createCeldaParaFirma(235, 281, 1, 'SECRETARIO DE ESTADO', false, false, 'C');
    $this->createCeldaParaFirma(282, 334, 1, 'MINISTRO DE SALUD', false, false, 'C'); 
    }
    
    /**
     * @param $medidas
     * @param $key
     * @param $texto
     */
    private function createCeldaConTexto($medidas, $key, $texto)
    {
        $cantlineas = $this->contarLineas($medidas[$key + 1] - $medidas[$key], 10, $texto, 0, 'C');
        $alto = 8 / $cantlineas;
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $medidas[$key]);
        $this->MultiCell($medidas[$key + 1] - $medidas[$key], $alto, $texto, 1, 'C');
    }

    /**
     * @param $texto
     */
    private function createTitulo($texto)
    {
        $this->setX($this->getX() + $this->medidasTabla[0]);
        $this->MultiCell($this->medidasTabla[count($this->medidasTabla) - 1] - $this->medidasTabla[0], 5, utf8_decode($texto), null, 'C');
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
        $alto = $isTitulo ? (3 / $cantlineas) : (5 / $cantlineas);
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $comienzo);
        $this->MultiCell($total - $comienzo, ($alto * $cantidadLineas), utf8_decode($texto), ($bordeMarcado ? 'B' : 0), $alineacionTexto);
    }
}
