<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;

class NovedadAPIController extends AppBaseController
{
    public function __construct() {}

    public function ia_GenNovLiq_ReeCob(Request $request, $fecha, $expediente, $codAuto)
    {
        $result = DB::select('CALL ia_GenNovLiq_ReeCob(?,?,?)', [$fecha, $expediente, $codAuto]);

        return $this->sendResponse($result, "CALL ia_GenNovLiq_ReeCob({$fecha}, {$expediente}, {$codAuto}) successfully");
    }

    public function ia_GenNovLiq_GuaLD(Request $request, $fecha, $expediente, $codAuto)
    {
        $result = DB::select('CALL ia_GenNovLiq_GuaLD(?,?,?)', [$fecha, $expediente, $codAuto]);

        return $this->sendResponse($result, "CALL ia_GenNovLiq_GuaLD({$fecha}, {$expediente}, {$codAuto}) successfully");
    }

    public function listarNovedades(Request $request, $fecha, $expediente, $codAuto)
    {
        $result = DB::select('SELECT * FROM ia_novliquidacion WHERE perliq=? AND expediente=? AND codAuto=?)', [$fecha, $expediente, $codAuto]);

        return $this->sendResponse($result, "SELECT * FROM ia_novliquidacion WHERE perliq={$fecha} AND expediente={$expediente} AND codAuto={$codAuto})");
    }

    public function borrarNovedades(Request $request, $fecha, $expediente, $codAuto)
    {
        $result = DB::select('DELETE FROM ia_novliquidacion WHERE perliq=? AND expediente=? AND codAuto=?)', [$fecha, $expediente, $codAuto]);

        return $this->sendResponse($result, "DELETE FROM ia_novliquidacion WHERE perliq={$fecha} AND expediente={$expediente} AND codAuto={$codAuto})");
    }
}
