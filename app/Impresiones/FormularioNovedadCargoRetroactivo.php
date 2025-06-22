<?php
namespace App\Impresiones;

use App\Impresiones\PDF;

class FormularioNovedadCargoRetroactivo extends PDF
{
    private $data;
    private array $formularios;

    public function __construct($data=null,array $formularios)
    {
        parent::__construct('L','mm','Legal');
        $this->data = $data;
        $this->formularios = $formularios;
        $this->inicioTabla = 10;
        $this->setMargins(10,5);
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
        $this->createCeldaConTexto(0,340,1,'EFECTOR: '.$this->data['efector'].' - CARGA LOCAL DE DATOS',false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,$this->data['tipo_formulario'],false,true);
        $this->newRow();
        $this->createCeldaConTexto(0,340,1,'MES DE LIQUIDACIÓN: '.$this->data['periodo_liq'],false,true);
        $this->drawLogo();
        $this->newRow();
        $this->createCeldaConTexto(0,20,2,'DNI',true);
        $this->createCeldaConTexto(18,38,2,'FECHA DE NACIMIENTO',true);
        $this->createCeldaConTexto(38,55,2,'EP DESDE',true);
        $this->createCeldaConTexto(55,72,2,'EP HASTA',true);
        $this->createCeldaConTexto(72,115,2,'EFECTOR',true);
        $this->createCeldaConTexto(115,150,2,'APELLIDO Y NOMBRE',true);
        $this->createCeldaConTexto(150,165,2,'NIVEL',true);
        $this->createCeldaConTexto(165,200,2,'CARGO',true);
        $this->createCeldaConTexto(200,230,2,'ASISTENCIAL / NO ASISTENCIAL',true);
        $this->createCeldaConTexto(230,290,2,'TIPO DE REEMPLAZO IMPREVISIBLE / PREVISIBLE / CARGO VACANTE / COBERTURA PROVISORIA',true);
        $this->createCeldaConTexto(290,330,2,'NOMBRE TITULAR DEL CARGO',true);
        $this->createCeldaConTexto(330,340,2,'N°',true);
    }

    public function Table() {
        $this->SetFont('Arial','', 7);

        foreach ($this->formularios as $key => $formulario) {
            $this->inicioTabla = $this->GetY();
            $this->createCeldaConTexto(0,18,1.3,$formulario['documento']);
            $this->createCeldaConTexto(18,38,1.3,$formulario['fnacimiento']);
            $this->createCeldaConTexto(38,55,1.3,$formulario['ep_desde']);
            $this->createCeldaConTexto(55,72,1.3,$formulario['ep_hasta']);
            $this->createCeldaConTexto(72,115,1.3,$this->data['efector']);
            $this->createCeldaConTexto(115,150,1.3,$formulario['apellido_nombre']);
            $this->createCeldaConTexto(150,165,1.3,$formulario['nivel']);
            $this->createCeldaConTexto(165,200,1.3,$formulario['funcion']);
            $this->createCeldaConTexto(200,230,1.3,$formulario['tipo_agrupamiento']);
            $this->createCeldaConTexto(230,290,1.3,$formulario['tipo_cargo']);
            $this->createCeldaConTexto(290,330,1.3,$formulario['titular']);
            $this->createCeldaConTexto(330,340,1.3,$key+1);

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

    public static function imprimir($data,array $formularios)
    {
        $pdf = new self($data, $formularios);
        $pdf->Output('D', 'novedades_retroactivas_LIQ_'.$data['efector'].'_'.time().'.pdf');
        exit();
    }

}
