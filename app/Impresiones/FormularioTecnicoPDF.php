<?php
namespace App\Impresiones;

use App\Impresiones\PDF;

class FormularioTecnicoPDF extends PDF
{
    private $ejeY;
    public function __construct($orientacion,$medida,$tamanio)
    {
        parent::__construct($orientacion,$medida,$tamanio);
    }

    public function Header()
    {
        $registro_txt = utf8_decode("INFORME DE EVALUACIÓN TÉCNICA");
        $this->SetFont('Arial','B',12);
        $this->Cell(52,45,$this->Image(public_path('/images/pdf/logo_psicotecnico.png'),$this->GetX(),$this->GetY()),1,0,"C");
        $x = $this->GetX();
        $y = $this->GetY();
        $this->MultiCell(72,45/2,$registro_txt,1,"C");
        $this->SetXY($x+72,$y);
        $this->MultiCell(65,45,null,1,"J");
        $this->Ln(2);
        $this->Cell(185,12,$registro_txt,0,0,"C");
        $this->Ln(6);
        $this->SetFont('Arial','BI',10);
        $this->Ln(8);
    }

    public function DatosPostulante($postulante)
    {
        $this->ejeY = $this->GetY();
        $this->SetFont('Arial', 'BI', 12);
        $this->SetFillColor(79,129,189);
        $this->SetTextColor(255,255,255);
        $datos_postulante = "Datos Personales";
        $this->Cell(190, 8, $datos_postulante, 1, 1, "C",true);
        $this->SetTextColor(0,0,0);
        $this->Cell(190,55,"",0,1);
        $this->SetY($this->ejeY+10);
        $this->SetFont('Arial','',9);
        $this->Cell(35,5,'Apellido y Nombre: ',0,0,"L");
        $this->SetFont('Arial','B',9);
        $this->Cell(45,5,utf8_decode($postulante['apynom']),0,1,"L");
        $this->SetFont('Arial','',9);
        $this->Cell(95,5,utf8_decode('Edad: '.$postulante['edad'].' años'),0,0,"L");
        $this->Cell(95,5,'Fecha de Nacimiento: '.$postulante['fnacimiento'],0,1,"L");
        $this->Cell(90,5,'DNI: '.$postulante['dni'],0,1,"L");
        $this->MultiCell(90,5,'Domicilio: '.utf8_decode((isset($postulante['domicilio']['calle']) && !empty($postulante['domicilio']['calle'])) ? $postulante['domicilio']['calle'] : ''),0,"L");
        $this->Cell(90,5,utf8_decode('Teléfono: ').$postulante['telefono'],0,1,"L");
        $this->Cell(90,5,utf8_decode('Estudio Cursados:').$postulante['estudios_cursados'],0,1,"L");
        $this->Cell(90,5,utf8_decode('Nivel:').$postulante['nivel'],0,1,"L");
        $this->Cell(90,5,utf8_decode('Fecha de Evaluación: '.$postulante['fecha_evaluacion']),0,1,"L");
        $this->Cell(90,5,'Evaluador: '.$postulante['evaluador'],0,1,"L");
    }

    public function ModoEvaluacion($modo_evaluacion)
    {
        $this->Text(12,$this->ejeY+63,utf8_decode("Modo de Evaluación:"));
        $this->SetY($this->ejeY+70);
        $this->cell(95,8,"ESCRITO",1,0);
        $this->cell(95,8,$modo_evaluacion['escrito'],1,0);
        $this->Ln();
        $this->cell(95,8,utf8_decode("TEÓRICO"),1,0);
        $this->cell(95,8,$modo_evaluacion['teorico'],1,0);
        $this->Ln();
        $this->cell(95,8,utf8_decode("PRÁCTICO"),1,0);
        $this->cell(95,8,$modo_evaluacion['practico'],1,0);
    }

    public function Conclusion($conclusion)
    {
        $resulta = "De la evaluación resulta concluyente decir que el postulante presenta un nivel de conocimiento técnico en relación al puesto requerido:";

        $this->SetY($this->ejeY+100);
        $x = $this->GetX();
        $this->SetFont('Arial', 'BI', 12);
        $this->SetFillColor(79,129,189);
        $this->SetTextColor(255,255,255);
        $this->Cell(190, 8, utf8_decode("Conclusión"), 1, 1, "C",true);
        $this->SetTextColor(0,0,0);
        $this->SetY($this->ejeY+115);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(190,4,utf8_decode($resulta));
        $this->SetY($this->ejeY+130);
        $this->cell(95,8,utf8_decode("BÁSICO"),1,0);
        $this->cell(95,8,($conclusion['rango'] == 1 ? "X":""),1,0,"C");
        $this->Ln();
        $this->cell(95,8,utf8_decode("MEDIO"),1,0);
        $this->cell(95,8,($conclusion['rango'] == 2 ? "X":""),1,0,"C");
        $this->Ln();
        $this->cell(95,8,utf8_decode("SUPERIOR"),1,0);
        $this->cell(95,8,($conclusion['rango'] == 3 ? "X":""),1,0,"C");
        $this->SetX($x);
    }

    public function Firma($firma){
        $x = $this->GetX();
        $this->Image(public_path('/images/firmas/'.$firma),118,235,70,40);
        $this->SetXY($x+95,260);
        $this->cell(95,8,'..........................',0,0,"C");
        $this->SetXY($x+95,264);
        $this->cell(95,8,'Firma',0,2,"C");
    }
}
