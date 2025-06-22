<?php
namespace App\Impresiones;

use App\Impresiones\PDF;
use App\Models\Agente;
use App\Models\Candidato;

class FormularioEPCargoPDF extends PDF
{
    private $data;
    private array $formularios;
    private $medidasTabla;

    public function __construct($data=null,array $formularios)
    {
        parent::__construct('L','mm','Legal');
        $this->data = $data;
        $this->formularios = $formularios;
        $this->inicioTabla = 10;
        $this->setMargins(10,5);
        $this->medidasTabla = [
            0 => 10,
            1 => 30,
            2 => 124.724409,
            3 => 207.874015,
            4 => 275.905511,
            5 => 343.937007,
            6 => 411.968503,
            7 => 479.999999,
            8 => 532.913385,
            9 => 600.944881,
            10 => 653.858267,
            11 => 721.889763,
            12 => 805.039369,
            13 => 857.952755,
            14 => 925.984251
        ];
        $this->SetFont('arial', 'B', 10);
        //$this->SetMargins(37.79, 10);
        $this->AddPage('L', 'Legal');
        $this->Table();
    }

    /**
     * Header de la Página
     */
    public function Header()
    {
        $this->SetFont('Arial', '', 10);
        $this->setY(10);
        $this->createCeldaConTexto(0,340,1,'DIRECCIÓN GENERAL DE RRHH EN SALUD',false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,'DIRECCIÓN DE PLANIFICACIÓN Y DESARROLLO',false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,2,'DEPARTAMENTO DE PLANIFICACIÓN',false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,'ESTABLECIMIENTO: '.$this->data['efector'],false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,'EP: '.$this->data['periodo_ep'].'   -   '. 'LIQ: '.$this->data['periodo_liq'],false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,'PLANILLA DE NOVEDADES MENSUALES DE COBERTURAS - CON EFECTIVA PRESTACIÓN',false,true);
        $this->drawLogo();
        $this->newRow();
        $this->createCeldaConTexto(0,10,1,'N°',true);
        $this->createCeldaConTexto(10,40,1,'DOCUMENTO',true);
        $this->createCeldaConTexto(40,90,1,'APELLIDO Y NOMBRE',true);
        $this->createCeldaConTexto(90,110,1,'AUT. DESDE',true);
        $this->createCeldaConTexto(110,130,1,'AUT. HASTA',true);
        $this->createCeldaConTexto(130,150,1,'EP DESDE',true);
        $this->createCeldaConTexto(150,170,1,'EP HASTA',true);
        $this->createCeldaConTexto(170,185,1,'DÍAS',true);
        $this->createCeldaConTexto(185,200,1,'CARGO',true);
        $this->createCeldaConTexto(200,215,1,'NIVEL',true);
        $this->createCeldaConTexto(215,245,1,'HORARIOS',true);
        $this->createCeldaConTexto(245,290,1,'PRIMERA VEZ / DOCUMENTACIÓN',true);
        $this->createCeldaConTexto(290,310,1,'TIPO',true);
        $this->createCeldaConTexto(310,340,1,'ASISTENCIAL / NO ASISTENCIAL',true);
    }

    public function Table() {
        $this->SetFont('Arial','', 7);

        foreach ($this->formularios as $key => $formulario) {
            $this->inicioTabla = $this->GetY();
            $this->createCeldaConTexto(0,10,1,$key+1);
            $this->createCeldaConTexto(10,40,1,$formulario['documento']);
            $this->createCeldaConTexto(40,90,1,$formulario['apellido_nombre']);
            $this->createCeldaConTexto(90,110,1,$formulario['autorizacion_desde']);
            $this->createCeldaConTexto(110,130,1,$formulario['autorizacion_hasta']);
            $this->createCeldaConTexto(130,150,1,$formulario['ep_desde']);
            $this->createCeldaConTexto(150,170,1,$formulario['ep_hasta']);
            $this->createCeldaConTexto(170,185,1,$formulario['dias']);
            $this->createCeldaConTexto(185,200,1,$formulario['cargo']);
            $this->createCeldaConTexto(200,215,1,$formulario['nivel']);
            $this->createCeldaConTexto(215,245,1,$formulario['horarios']);
            $this->createCeldaConTexto(245,290,1,$formulario['primera_vez']);
            $this->createCeldaConTexto(290,310,1,$formulario['tipo']);
            $this->createCeldaConTexto(310,340,1,$formulario['tipo_agrupamiento']);

            if ($this->getY() > 188 && $key+1 < count($this->formularios)) {
                $this->inicioTabla = 10;
                $this->AddPage('L', 'Legal');
            }
        }
    }

    public function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-10);
        // Select arial italic 8
        $this->SetFont('arial', 'I', 6);
        $this->Cell(0, 10, 'SIPROSA', 0, 0, 'L');
        $this->SetY(-10);
        $this->Cell(0, 10, 'SIARHU', 0, 0, 'C');
        $this->SetY(-10);
        //$this->Cell(0, 10, 'Usuario: ' . Auth::user()->nombreusuario, 0, 0, 'R');
    }


    /**
     * @param $medidas
     * @param $medida
     * @param $texto
     */
    private function createCeldaConTexto($comienzo, $total, $cantidadLineas = 1, $texto, $isTitulo = false, $bordeMarcado = true, $alineacionTexto = 'C')
    {
        //$this->SetFillColor(255, 245, 156);
        if ($isTitulo) {
            $this->SetFillColor(255, 245, 156);
            $this->SetFont('Arial', 'B', 8);
        } else {
            $this->SetFillColor(255, 255, 255);
            $this->SetFont('Arial', '', 8);
        }

        $cantlineas = $this->contarLineas($total - $comienzo, (7 * $cantidadLineas), utf8_decode($texto), 0, $alineacionTexto);
        $alto = 7 / $cantlineas;
        $this->setY($this->inicioTabla);
        $this->setX($this->getX() + $comienzo);
        $this->MultiCell($total - $comienzo, ($alto * $cantidadLineas), utf8_decode($texto), ($bordeMarcado ? 1 : 0), $alineacionTexto,$isTitulo);
    }

    /**
     * @param $texto
     */
    private function createTituloPagina($texto)
    {
        $this->setX($this->getX());
        $this->MultiCell(335, 5, utf8_decode($texto), null, 'C');
    }

    public function drawLogo()
    {
        $this->Image(public_path('/images/pdf/logo_rrhh.jpg'), $this->getX() + 10, 25, 28, 12);
        $this->Image(public_path('/images/pdf/minSaludLogo.png'), $this->getX() + 280, 25, 32, 12);
        //$this->Image()
        //$this->Cell(52, 30, "", 0, 0, "C");
    }

}
