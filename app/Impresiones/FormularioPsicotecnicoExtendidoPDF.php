<?php
namespace App\Impresiones;

use App\Models\TipoRecomendacion;
use DateTime;

class FormularioPsicotecnicoExtendidoPDF extends PDF
{
    private $escalaPerfilLaboral = array(
        'aspectos_cognitivos' => [3,6,9,12,15],
        'aspectos_psicoafectivos' => [3,6,9,12,15],
        'desempeno' => [1.5,3,4.5,6,7.5],
        'motivacion' => [1.5,3,4.5,6,7.5]
    );
    private $escalaCompetenciasEvaluadas = array(
        'atencion_usuario' => [2.2,4.4,6.6,8.8,11],
        'trabajo_en_equipo' => [2.2,4.4,6.6,8.8,11],
        'adaptabilidad' => [2.2,4.4,6.6,8.8,11],
        'tolerancia_presion' => [2.2,4.4,6.6,8.8,11],
        'organizacion' => [2.2,4.4,6.6,8.8,11]
    );
    private $idtipo_entrevista;
    public function __construct($orientacion,$medida,$tamanio,$idtipo_entrevista)
    {
        parent::__construct($orientacion,$medida,$tamanio);
        $this->idtipo_entrevista = $idtipo_entrevista;
    }

    public function Header()
    {
        if ($this->idtipo_entrevista === 1){
            $registro_txt = utf8_decode('INFORME DE EVALUACIÓN');
        }else{
            $registro_txt = utf8_decode('REGISTRO RESUMEN DE EVALUACIÓN GRUPAL');
        }
        $altura_recuadro = 40;
        $rrhh_txt = utf8_decode('Recursos Humanos');
        $this->SetFont('Arial','B',12);
        $this->Image(public_path('/images/pdf/logo_rrhh.png'),$this->GetX(),$this->GetY()+12.5,50,17);
        $this->Cell(52,$altura_recuadro, '',1,0, 'C');
        $x = $this->GetX();
        $y = $this->GetY();
        $this->MultiCell(72,$altura_recuadro,utf8_decode('INFORME DE EVALUACIÓN'),1, 'C');
        $this->SetXY($x+72,$y);
        $this->SetFont('Arial', '', 9);
        $this->MultiCell(65, $altura_recuadro/5, utf8_decode('Código: F-5.2
Fecha: 01/02/2019
Revisión: 02
Aprobado por: Lic. Andina Fabio
Página ' . $this->PageNo()), 1, 'L');

        $this->SetFont('Arial','B',12);
        $this->Ln(2);
        $this->Cell(185,12,$registro_txt,0,0, 'C');
        $this->Ln(6);
        $this->SetFont('Arial','BI',10);
        $this->Cell(185,8,"",0,5, 'C');
//        $this->Cell(185,8,$rrhh_txt,0,5, 'C');
    }

    public function DatosPostulante($postulante, $firma) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, 'Datos personales', 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,50, '',0,1);
    $y = $this->GetY()-50;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    $this->Cell(95,4,'Apellido y Nombre: '.$postulante['apynom'],0,0, 'L');
    $this->ln(7);

    $bday = DateTime::createFromFormat('d/m/Y', $postulante['fnacimiento']);
    $today = DateTime::createFromFormat('d/m/Y', $postulante['fecha_evaluacion']);
    $interval = $today->diff($bday);

    $this->Cell(95,4,'Edad: '.$interval->y,0,0, 'L');
    $this->Cell(95,4,'Fecha de Nacimiento: '.$postulante['fnacimiento'],0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,'DNI: : '.utf8_decode($postulante['dni']),0,0, 'L');
    $this->ln(4);
    if (isset($postulante['domicilio']) && !empty($postulante['domicilio']))
       {$this->MultiCell(95,4, utf8_decode('Domicilio: '. $postulante['domicilio']['calle'] . $postulante['domicilio']['edificio'] . $postulante['domicilio']['localidad']));}
       else
       {$this->MultiCell(95,4, 'Domicilio: ');};
    $this->ln(0);
    $this->Cell(95,4,utf8_decode('Teléfono: '.$postulante['telefono']),0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,utf8_decode('Estudios Cursados: '.$postulante['formacion']),0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,utf8_decode('Nivel:').$postulante['nivel'],0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,utf8_decode('Fecha de Evaluación: '.$postulante['fecha_evaluacion']),0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,utf8_decode('Evaluador: '.$firma['nombre']),0,0, 'L');
    $this->ln(4);
    $this->Cell(95,4,utf8_decode('Técnicas empleadas: '),0,0, 'L');

    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }
    
    public function Presentacion($extendido) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, utf8_decode('Presentación'), 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,30, '',0,1);
    $y = $this->GetY()-20;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    
    $texto = $extendido['presentacion'];
    $this->MultiCell(190,4, utf8_decode($texto));

    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }
    
    public function AspectosCognitivos($extendido) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, utf8_decode('Aspectos cognitivos'), 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,30, '',0,1);
    $y = $this->GetY()-20;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    
    $texto = $extendido['aspectos_cognitivos'];
    $this->MultiCell(190,4, utf8_decode($texto));
    
    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }
    
    public function ModalidadRelacional($extendido) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, utf8_decode('Modalidad Relacional'), 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,30, '',0,1);
    $y = $this->GetY()-20;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    
    $texto = $extendido['modalidad_relacional'];
    $this->MultiCell(190,4, utf8_decode($texto));
    
    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }
    
    public function Motivacion($extendido) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, utf8_decode('Motivación'), 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,30, '',0,1);
    $y = $this->GetY()-20;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    
    $texto = $extendido['motivacion'];
    $this->MultiCell(190,4, utf8_decode($texto));
    
    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }
    
    public function Conclusion($extendido) : void
    {
    $this->SetFont('Arial', 'B', 10);
    $this->SetFillColor(141,179,226);
    $this->SetTextColor(0,0,0);
    $this->Cell(190, 5, utf8_decode('Conclusión'), 0, 1, 'C',true);
    $this->SetTextColor(0,0,0);
    $this->Cell(190,30, '',0,1);
    $y = $this->GetY()-20;
    $this->SetY($y);
    $this->SetFont('Arial','',8);
    
    $texto = $extendido['conclusion'];
    $this->MultiCell(190,4, utf8_decode($texto));
    
    $this->SetFont('Arial','B',8);
    $this->ln(5);
    }

    public function TablaCompetencias($competencias): void
    {
     //   $this->SetXY(40,124);
        $this->SetX(110);
        $alto = 7;
        $y = 124;
        $this->SetY($y);
        $this->SetFont('Arial', 'BI', 10);
        $this->SetFillColor(79,129,189);
        $this->Cell(95, 6, utf8_decode('COMPETENCIAS EVALUADAS'), 1, 1, 'C',true);
        $this->SetFont('Arial', '', 9);
        $this->Cell(50,$alto,utf8_decode('Orientación al usuario'),1,1, 'L');
        $this->Cell(50,$alto,utf8_decode('Trabajo en equipo'),1,1, 'L');
        $this->Cell(50,$alto,utf8_decode('Adaptabilidad/Flexibilidad'),1,1, 'L');
        $this->Cell(50,$alto,utf8_decode('Tolerancia a la presión'),1,2, 'L');
        $this->Cell(50,$alto,utf8_decode('Organización y planificación'),1,2, 'L');
        $y += 6;
        $this->SetXY($this->GetX()+50,$y);
        $this->dibujarEscalas($competencias,$alto,$this->escalaCompetenciasEvaluadas);
    }
    
    private function dibujarEscalas($items,$altura,$arrayEscalas): void
    {
        $x = $this->GetX();
        foreach ($arrayEscalas as $key => $escala){
            $calificacion = $items[$key];
            foreach ($escala as $e){
                if ((float)($calificacion) === (float)($e)){
                    $this->SetFillColor(79,129,189);
                    $this->Cell(9,$altura,$e,1,2, 'L',true);
                    $this->SetFillColor(255,255,255);
                }else{
                    $this->Cell(9,$altura,$e,1,2, 'L');
                }
                $this->SetXY($this->GetX()+9,$this->GetY()-$altura);
            }
            $this->SetXY($x,$this->GetY()+$altura);
        }
    }

    public function Firma($firma): void
    {
        $this->SetXY(155,202);
        $this->Image(public_path('images/firmas/' .$firma['firma']),75,190,63,36);
        $this->SetFont('Arial','B',12);
        $this->SetXY(10,217);
        $this->Cell(0,4,utf8_decode($firma['nombre']),0,1, 'C');
        $this->SetX(10);
        $this->Cell(0,4,utf8_decode($firma['matricula']),0,1, 'C');
    }

    public function Recomendacion($tipo_recomendacion,$recomendado_para,$tipo_entrevista): void
    {
        $y = 198;
        /** @var TipoRecomendacion $tipo_recomendacion */
        $this->SetY($y);
        $this->Cell(190,40, '',1,1);

        $this->SetY($y);
        $this->AgregarRadioButton($this->GetX()+10,$y+2.5,($tipo_recomendacion->idtipo_recomendacion === 1));
        $this->Cell(25,10, '',1,1);
        $x = $this->GetX()+60;
        if (($tipo_recomendacion->idtipo_recomendacion === 1)){
            $this->SetXY($x,$y);
            $this->Cell(120,10,
                utf8_decode((!empty($recomendado_para[0]) ? $recomendado_para[0] : '') .
                    (!empty($recomendado_para[1]) ? ', ' . $recomendado_para[1] : '') .
                    (!empty($recomendado_para[2]) ? ', ' . $recomendado_para[2] : '')),
                0,0);
        }
        $this->SetXY($x-60,$y+10);

        $y += 10;
        $this->AgregarRadioButton($this->GetX()+10,$y+2.5,($tipo_recomendacion->idtipo_recomendacion === 2));
        $this->Cell(25,10, '',1,1);

        $y += 10;
        $this->AgregarRadioButton($this->GetX()+10,$y+2.5,($tipo_recomendacion->idtipo_recomendacion === 3));
        $this->Cell(25,10, '',1,1);

        $y +=10;
        if ($tipo_entrevista === 2){
            $this->AgregarRadioButton($this->GetX()+10,$y+2.5,($tipo_recomendacion->idtipo_recomendacion === 4));
            $this->Cell(25,10, '',1,1);
        }

        $y = 198;
        $this->SetXY($this->GetX()+25,$y);
        $this->Cell(165,10,utf8_decode('Se recomienda para: '),1,2);
        $this->Cell(165,10,utf8_decode('Se recomienda con reserva'),1,2);
        $this->Cell(165,10,utf8_decode('No se recomienda ingreso al Sistema Provincial de Salud'),1,2);
        if ($tipo_entrevista === 2){
            $this->Cell(165,10,utf8_decode('Se recomienda evaluación individual'),1,2);
        }else{
            $this->Cell(165,10, '',1,2);
        }

        $this->SetY(203);
    }
    private function AgregarRadioButton($x,$y,$b): void
    {
        if ($b){
            $this->Image(public_path('/images/components/radio-button.png'),$x,$y,5,5);
        }else{
            $this->Image(public_path('/images/components/radio-button-vacio.png'),$x,$y,5,5);
        }
    }
}
