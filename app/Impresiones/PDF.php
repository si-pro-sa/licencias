<?php
namespace App\Impresiones;

use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;

class PDF extends Fpdf
{
    public function __construct($orientacion = 'P', $medida = 'mm', $tamanio = 'Legal')
    {
        parent::__construct($orientacion, $medida, $tamanio);
    }
    /**
     * @var
     */
    public $inicioTabla;

    /**
     * @param $medidas
     * @param $key
     * @param $texto
     */
    private function createCeldaConTexto($medidas, $key, $texto)
    {
        $cantlineas = $this->contarLineas($medidas[$key + 1] - $medidas[$key], 10, $texto, 0, 'C');
        $alto = 20 / $cantlineas;
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
        $this->MultiCell($this->medidasTabla[count($this->medidasTabla) - 1] - $this->medidasTabla[0], 15, utf8_decode($texto), "LTB", 'C');
    }


    public function contarLineas($w, $h, $txt, $border=0, $align='J', $fill=false)
    {
        // Output text with automatic or explicit line breaks
        if (!isset($this->CurrentFont)) {
            $this->Error('No font has been set');
        }
        $cw = &$this->CurrentFont['cw'];
        if ($w==0) {
            $w = $this->w-$this->rMargin-$this->x;
        }
        $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb>0 && $s[$nb-1]=="\n") {
            $nb--;
        }
        $b = 0;
        if ($border) {
            if ($border==1) {
                $border = 'LTRB';
                $b = 'LRT';
                $b2 = 'LR';
            } else {
                $b2 = '';
                if (strpos($border, 'L')!==false) {
                    $b2 .= 'L';
                }
                if (strpos($border, 'R')!==false) {
                    $b2 .= 'R';
                }
                $b = (strpos($border, 'T')!==false) ? $b2.'T' : $b2;
            }
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $ns = 0;
        $nl = 1;
        while ($i<$nb) {
            // Get next character
            $c = $s[$i];
            if ($c=="\n") {
                // Explicit line break
                if ($this->ws>0) {
                    $this->ws = 0;
                    $this->_out('0 Tw');
                }
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl==2) {
                    $b = $b2;
                }
                continue;
            }
            if ($c==' ') {
                $sep = $i;
                $ls = $l;
                $ns++;
            }
            $l += $cw[$c];
            if ($l>$wmax) {
                // Automatic line break
                if ($sep==-1) {
                    if ($i==$j) {
                        $i++;
                    }
                    if ($this->ws>0) {
                        $this->ws = 0;
                        $this->_out('0 Tw');
                    }
                } else {
                    if ($align=='J') {
                        $this->ws = ($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                        $this->_out(sprintf('%.3F Tw', $this->ws*$this->k));
                    }
                    $i = $sep+1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $ns = 0;
                $nl++;
                if ($border && $nl==2) {
                    $b = $b2;
                }
            } else {
                $i++;
            }
        }
        // Last chunk
        if ($this->ws>0) {
            $this->ws = 0;
            $this->_out('0 Tw');
        }
        if ($border && strpos($border, 'B')!==false) {
            $b .= 'B';
        }
        $this->x = $this->lMargin;
        return $nl;
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
        $this->Cell(0, 10, 'Usuario: ' . Auth::user()->nombreusuario, 0, 0, 'R');
    }

    public function newRow()
    {
        $this->inicioTabla = $this->GetY();
    }

    public function drawLogo()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Image(public_path('/images/pdf/logo_rrhh.png'), $this->GetX(), $this->GetY()-10, 50, 17);
        $this->Cell(52, 30, "", 0, 0, "C");
    }

    public function showInfo($created_at, $codigo)
    {
        $this->SetXY($this->getX() + 300, $this->getY() - 15);
        $this->SetFont('Arial', '', 7);
        $this->MultiCell(65, 3, utf8_decode('
        Código: ' . $codigo . '
        Fecha: ' . date("Y-m-d") . '
        Hora: ' . date("H:m:s")  .'
        Página: ' . $this->PageNo()), 0, 'L');
    }
}
