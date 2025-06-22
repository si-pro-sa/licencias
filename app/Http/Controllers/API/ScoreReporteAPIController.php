<?php

namespace App\Http\Controllers\API;
//require 'vendor/autoload.php';

use App\Http\Controllers\AppBaseController;
use App\Models\Dependencia;
use App\Models\Periodo;
use Doctrine\DBAL\Schema\Index;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Exeption;
use PhpParser\Node\Stmt\Foreach_;

class ScoreReporteAPIController extends AppBaseController
{

    public function getEfectores(){
        $Efectores = Dependencia::select('iddependencia','dependencia', 'codigorrhh')->ObtDependenciasSegunUsuario([6, 7]);
        $EfectoresServicios = [];
        foreach($Efectores as $efector){
            $idservicios = $efector->getIdsDescendencia();
            $servicios = Dependencia::select('iddependencia', 'dependencia', 'codigorrhh')->where('idtipo_dependencia', 4)->whereIn('iddependencia', $idservicios)->get();
            $EfectorServicio = [
                'iddependencia' => $efector->iddependencia,
                'codigo_nombre' => $efector->codigo_nombre,
                'servicios' => $servicios
            ];
            $EfectoresServicios[] = $EfectorServicio; 
        }
        return $EfectoresServicios;
    }

    public function getPeriodos(){
        $Periodos = Periodo::select('idperiodo', 'periodo', 'idtipo_mes', 'anio','fdesde', 'fhasta')->orderBy('idperiodo', 'desc')->get()->toArray();
        return $Periodos; 
    }

    public function getDiagramasReportes($iddependencia, $mes, $anio){
        $Dependencia = Dependencia::select('iddependencia')->where('iddependencia', $iddependencia)->first();
        $iddependenciashijas = $Dependencia->getIdsDescendencia();
        
        $diagramas = DB::table('v_diagramas')->
                            whereIn('iddependencia', $iddependenciashijas)-> 
                            //orWhere('iddependencia', 1654)->
                            whereMonth('fecha', $mes)->
                            whereYear('fecha', $anio)->
                            orderBy('idagente', 'asc')->
                            orderBy('fecha', 'asc')->
                            get();

        return $diagramas;
    }

    public function AgruparPorAgente($diagramas){
        $nombre=$diagramas[0]->nombre;
        $aux=[];
        $reorden = [];

        foreach($diagramas as $diagrama){
            if($diagrama->nombre == $nombre){
                $aux[] = $diagrama;
            }else{
                $reorden[] = [
                    'nombre' => $nombre,
                    'documento' => $aux[0]->documento,
                    'dependencia' => $aux[0]->dependencia, 
                    'diagramas' => $aux
                ];
                $nombre = $diagrama->nombre;
                $aux=[];
                $aux[] = $diagrama;
            }
        }
        $reorden[] = [
            'nombre' => $nombre,
            'documento' => $aux[0]->documento,
            'dependencia' => $aux[0]->dependencia,
            'diagramas' => $aux
        ];
        return $reorden;
    }

    public function generarExcel($iddependencia, $mes, $anio){

        $diagramas = $this->getDiagramasReportes($iddependencia, $mes, $anio);

        $diagramasOrg = $this->AgruparPorAgente($diagramas);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('Siarhu')->setTitle('Diagramas de Guardia');
        $spreadsheet->setActiveSheetIndex(0); //establezco la hoja que utilizaré
        $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma'); //fuente
        $spreadsheet->getDefaultStyle()->getFont()->setSize(15); //tamaño

        $hojaActiva = $spreadsheet->getActiveSheet()->setTitle("Diagramas de guardia"); //saco la hoja activa
        
        $semanas = $this->CreateHeader($mes, $anio, $hojaActiva);

        $this->CargarDatos($diagramasOrg, $semanas, $hojaActiva);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.date('Y-m-d H:i').'.xlsx');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

        //prueba

        /* header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.date('Y-m-d').'.xls');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output'); */

        /* $writer = new Xlsx($spreadsheet);
        $writer->save('Prueba.xlsx');  */ 

        /* $ExcelDiagrama = new DiagramasExport($diagramas);

        return Excel::download($ExcelDiagrama, 'prueba.xlsx', \Maatwebsite\Excel\Excel::XLSX); */
    }

    function CargarDatos($diagramas, $semanas, &$HojaActiva){
        foreach($diagramas as $index => $diagrama){
            $fila = $index + 2;
            $HojaActiva->setCellValue("A".$fila, $diagrama["nombre"])->setCellValue("B".$fila, $diagrama["documento"])->setCellValue("C".$fila, $diagrama["dependencia"]);

            foreach($diagrama["diagramas"] as $diagramaExcel){
                foreach($semanas as $semana){
                    if($semana["fecha"] == $diagramaExcel->fecha){
                        $horario = date('H:i', strtotime($diagramaExcel->fecha." ".$diagramaExcel->hora_desde));
                        $HojaActiva->setCellValue($semana["columna"].$fila, $horario." - ".$diagramaExcel->cantHoras."Hs");
                        break;
                    }
                }
            }

        }
    }

    function CreateHeader($mes, $anio, &$HojaActiva){
        $Cant_day_month = date("t", mktime(0, 0, 0, $mes, 1, $anio));
        $dia=1;
        $semanas = [];

        $HojaActiva->getColumnDimension('A')->setWidth(30);
        $HojaActiva->getColumnDimension('B')->setWidth(30);
        $HojaActiva->getColumnDimension('C')->setWidth(30);

        $HojaActiva->setCellValue('A1', 'Nombre')->setCellValue('B1','Documento')->setCellValue('C1','Dependencia');

        for($i=3; $i<$Cant_day_month+3; $i++){

            $fecha = date("Y-m-d", mktime(0,0,0, $mes, $dia, $anio));
            $column=$this->getExcelCol($i);

            $HojaActiva->getColumnDimension($column)->setWidth(30);
            $HojaActiva->setCellValue($column."1", $fecha);

            $semanas[]=['columna'=>$column,'fecha' =>$fecha]; 

            $dia++;
        } 

        return $semanas;
    }

    function getExcelCol($num) {
        $numero = $num % 26;
        $letra = chr(65 + $numero);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getExcelCol($num2 - 1) . $letra;
        } else {
            return $letra;
        }
    }
}
