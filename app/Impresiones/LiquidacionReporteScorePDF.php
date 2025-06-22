<?php
namespace App\Impresiones;

use Codedge\Fpdf\Fpdf\Fpdf;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Style\Border;

class LiquidacionReporteScorePDF extends Fpdf
{
    /**
     * Títulos de Tabla
     * @var array
     */
    private $width;

    public function __construct()
    {
        parent::__construct();
        $this->SetFont('Arial', 'B', 9);
        $this->AddPage();
        $this->setX(1);
        $this->SetAutoPageBreak(false);
        $this->SetTopMargin(10);
    }

    function createHeader($header)
    {   
        //la suma de $w tiene que dar 160 para que no haya desborde
        $posicionX = 1;
        $posicionY = $this->GetY();
        for($i=0;$i<count($header);$i++){
            $this->SetXY($posicionX, $posicionY);
            $this->MultiCell(
                $this->width != null ? $this->width[$i] : 26,
                7,
                $header[$i], align: 'C');
            $posicionX += $this->width != null ? $this->width[$i] : 26;
        }
        $this->Ln();
        $this->SetFont('');
        $posicionY = $this->GetY();
        $this->Line(1, $posicionY, 207, $posicionY);
    }

    function addData($data)
    {
        $posicionY = $this->GetY();

        foreach($data as $row)
        {
            //Establezco el salto de pagina
            if($this->haveToBreak()){
                $this->AddPage();
                $posicionY = $this->GetY();
            }

            $posicionX = 1;
            $this->SetXY($posicionX, $posicionY);
            $W = 0;
            
            $this->Line(1, $posicionY, 207, $posicionY);

            foreach($row as $nombre => $valor)
            {
                $this->MultiCell(
                    $this->width != null ? $this->width[$W] : 26,
                    6,
                    $valor);  

                $posicionX += $this->width != null ? $this->width[$W] : 26;
                $this->SetXY($posicionX, $posicionY);
                $W += 1;
            }
            $this->Ln();
            $posicionY += 18;
        }
    }

    function haveToBreak(){
        return $this->GetY() + 21 >= $this->GetPageHeight();
    }

    function setWidth($width) 
    {
        $this->width = $width;
    }

}