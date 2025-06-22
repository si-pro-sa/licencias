<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Puesto;
use App\Models\Periodo;
use App\Models\Agente;
use App\Models\Dependencia;
use App\Models\ScoreLiquidacion;
use App\Models\ScoreDiagrama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Carbon\Carbon;
use App\Impresiones\LiquidacionReporteScorePDF;

class ScoreLiquidacionAPIController extends AppBaseController
{

    public function getLiquidaciones($iddependencia, $idperiodo){
        $dependencia = Dependencia::where('iddependencia', $iddependencia)->first();
        $liquidaciones = DB::table('score_liquidacion')
                            ->join('agente', 'agente.idagente', '=', 'score_liquidacion.idagente')
                            ->join('puesto', 'puesto.idpuesto', '=', 'score_liquidacion.idpuesto')
                            ->join('tipo_funcion', 'tipo_funcion.idtipo_funcion', '=', 'puesto.idtipo_funcion')
                            ->join('dependencia', 'dependencia.iddependencia', '=', 'score_liquidacion.iddependencia')
                            ->join('periodo', 'periodo.idperiodo', '=', 'score_liquidacion.idperiodo')
                            ->select(
                                'score_liquidacion.idscore_liquidacion',
                                'score_liquidacion.idperiodo',
                                'periodo.periodo',
                                'score_liquidacion.idagente',
                                'agente.nombre',
                                'agente.apellido',
                                'agente.documento',
                                'score_liquidacion.idpuesto',
                                'tipo_funcion.tipofuncion',
                                'score_liquidacion.idpuestoadicional',
                                'score_liquidacion.iddependencia',
                                'dependencia.dependencia',
                                'score_liquidacion.subnivel',
                                'score_liquidacion.fecha_ult_inicio',
                                'score_liquidacion.tipo_dia',
                                'score_liquidacion.cantidad_guardia_realizadas',
                                'score_liquidacion.cantidad_guardia_norealizadas',
                                'score_liquidacion.cantidad_guardia_verificadas',
                                'score_liquidacion.multiplicador',
                            )->where('score_liquidacion.idperiodo', $idperiodo)
                            ->whereIn('score_liquidacion.iddependencia', $dependencia->getIdsDescendencia())
                            ->get();
        return $liquidaciones;
    }

    public function updateFechaUltimoInicio(Request $request){
        $liquidacion= $request->get('liquidacion');
        $affected = DB::table('score_liquidacion')
                        ->where('idscore_liquidacion', $liquidacion['idscore_liquidacion'])
                        ->update(['fecha_ult_inicio' => $liquidacion['fecha_ult_inicio']]);
        return $affected;
    }

    public function updateCantidadVerificada(Request $request){
        $liquidacion= $request->get('liquidacion');
        $affected = DB::table('score_liquidacion')
                        ->where('idscore_liquidacion', $liquidacion['idscore_liquidacion'])
                        ->update(['cantidad_guardia_verificadas' => $liquidacion['cantidad_guardia_verificadas']]);
        return $affected;
    }

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

    public function generarLiquidaciones($fecha){
        $respuesta =  DB::select("call score_genliquidacion(date('$fecha'))");
        return $respuesta;  
    }

    public function permisosUsuario(){
        $iduser = auth()->user()->idusuario;

        $permisosUsuario = DB::table('sistema.usuario')
                        ->join('sistema.permission_user', 'sistema.permission_user.user_id', '=', 'sistema.usuario.idusuario')
                        ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_user.permission_id')
                        ->select('sistema.permissions.id', 'sistema.permissions.name')
                        ->where('sistema.usuario.idusuario', $iduser)
                        ->orWhere(function ($query){
                            $query->where('sistema.permissions.name', 'ScoringController-procesar')
                                    ->where('sistema.permissions.name', 'ScoringController-habilitar');
                        });

        /*
            ScoringController-procesar = 914
            ScoringController-habilitar = 915
         */

        $permisosRol = DB::table('sistema.usuario')
                    ->join('sistema.role_user', 'sistema.role_user.user_id', '=', 'sistema.usuario.idusuario')
                    ->join('sistema.permission_role', 'sistema.permission_role.role_id', '=', 'sistema.role_user.role_id')
                    ->join('sistema.permissions', 'sistema.permissions.id', '=', 'sistema.permission_role.permission_id') 
                    ->select('sistema.permissions.id', 'sistema.permissions.name')
                    ->where('sistema.usuario.idusuario', $iduser)
                    ->Where(function ($query){
                        $query->where('sistema.permissions.name', 'ScoringController-procesar')
                                ->orWhere('sistema.permissions.name', 'ScoringController-habilitar');
                    })
                    ->union($permisosUsuario)
                    ->orderBy('id', 'asc')
                    ->get();
        
        return $permisosRol;
    }

    #region Excel

    public function generarExcel($fecdesde){
        $data =  DB::select("select * from get_puntaje_liquidacion(date('$fecdesde'))");
        if(count($data) > 0){
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()->setCreator('Siarhu')->setTitle('Diagramas de Guardia');
            $spreadsheet->setActiveSheetIndex(0); //establezco la hoja que utilizaré
            $spreadsheet->getDefaultStyle()->getFont()->setName('Tahoma'); //fuente
            $spreadsheet->getDefaultStyle()->getFont()->setSize(15); //tamaño

            $hojaActiva = $spreadsheet->getActiveSheet()->setTitle("Liquidacion de diagramas"); //saco la hoja activa
            
            $header = $this->loadExcel($hojaActiva, $data);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename=ReporteLiquidacion-'.Carbon::parse($fecdesde)->format('Y-m').'.xlsx');
            header('Cache-Control: max-age=0'); 

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }else {
            throw new \Exception('El periodo seleccionado no tiene liquidaciones asociadas');
        }
        return $data; 
    }

    function loadExcel(&$hojaActiva, $data){
        
        $attributes = [];

        $this->createHeader($hojaActiva, $data[0], 0, 1, $attributes);
        $this->addData($hojaActiva, $data, 0, 2, $attributes);

        return $attributes;
    }
    
    function createHeader(&$hojaActiva, $obj, $initialColumn, $row, &$attributes){
        $atributosObjeto = get_object_vars($obj);
        
        $numeroColumna = $initialColumn;
        $rowHeader = $row;

        foreach ($atributosObjeto as $nombre => $valor) {
            $columna = $this->getExcelCol($numeroColumna); 
            $celda = "$columna$rowHeader";
            $hojaActiva->setCellValue($celda, $this->getNameColumnHeader($nombre));
            $numeroColumna += 1;
            $attributes[] = $nombre;
        }

        return $attributes;
    }    

    function addData(&$hojaActiva, $data, $initialColumn, $initialRow, $attributes) {
        $row = $initialRow;
        foreach($data as $dataRow){
            $aux = get_object_vars($dataRow);
            $column = $initialColumn;

            foreach($attributes as $attribute){

                $columnaExcel = $this->getExcelCol($column);
                $celda = "$columnaExcel$row"; 

                $hojaActiva->setCellValue($celda, $aux[$attribute]);

                $column += 1;
            }

            $row += 1;
        }   
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

    function getNameColumnHeader($nombre){
        switch ($nombre){
            case 'dependencia':
                return 'Dependencia';
            case 'documento':
                return 'Documento';
            case 'agente':
                return 'Agente';
            case 'fec_ult_inicio':
                return 'Fecha ultimo inicio';
            case 'pundiafiesta':
                return 'Puntaje dias festivos';
            case 'pundiafsd':
                return 'Puntaje fines de semana';
            case 'pundiasem':
                return 'Puntaje dias de semana';
            case 'puntaje':
                return 'Puntaje Final';
        }
    }

    #endregion

    #region PDF

    public function generarPDF($fecdesde){
        $ReportePDF = new LiquidacionReporteScorePDF();

        $data =  DB::select("select * from get_puntaje_liquidacion(date('$fecdesde'))");            
        $header = $this->getAtributes($data[0]);
        $weight = [40, 20, 40, 26, 20, 20, 20, 20];

        $ReportePDF->setWidth($weight);
        $ReportePDF->createHeader($header);

        $ReportePDF->addData($data);
        //$ReportePDF->ImprovedTable($header, $data);

        $ReportePDF->Output(
            'D',
            'Prueba.pdf'
        ); 
        return $header;
    }

    function getAtributes($obj){
        $atributosObjeto = get_object_vars($obj);
        $attributes = [];
        foreach ($atributosObjeto as $nombre => $valor) {
            switch (true){
                case $nombre == "dependencia": 
                    $attributes[] = "Dependencia";
                    break;
                case $nombre == "documento": 
                    $attributes[] = "Documento";
                    break;
                case $nombre == "agente": 
                    $attributes[] = "Agente";
                    break;
                case $nombre == "fec_ult_inicio": 
                    $attributes[] = "Fecha ult. inicio";
                    break;
                case $nombre == "pundiafiesta": 
                    $attributes[] = "Puntaje Fiesta";
                    break;
                case $nombre == "pundiafsd": 
                    $attributes[] = "Pun. Fin\nde Sem.";
                    break;
                case $nombre == "pundiasem": 
                    $attributes[] = "Pun. Sem.";
                    break;
                case $nombre == "puntaje": 
                    $attributes[] = "Puntaje";
                    break;
            }
        }
        return $attributes;
    }   
    #endregion
}
