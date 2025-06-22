<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\ReemplazoCupo;
use App\Models\ReemplazoCupoPuesto;
use App\DataTables\ReemplazoCupoDataTable;
use App\Exports\ReemplazoCupoExport;
use App\Models\Dependencia;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ReemplazoCupoAPIController extends AppBaseController
{
  public function show(int $id)
  {
    $detail_cupo = ReemplazoCupoPuesto::getDetailPuesto($id);
    return $this->sendResponse($detail_cupo, "Detalle de cupo cargado correctamente.", 200);
  }

  public function store(Request $request)
  {

    $reemplazo_cupo = ReemplazoCupo::where('idefector', $request->idefector)->first();
    if (!$reemplazo_cupo) {
      $reemplazo_cupo = new ReemplazoCupo();
      $reemplazo_cupo->createReemplazoCupo($reemplazo_cupo, $request);
    }

    if ($reemplazo_cupo) {
      $cuposPuestos = new ReemplazoCupoPuesto();
      // $cuposPuestos->createCupoPuesto($request->cupos_servicio, $reemplazo_cupo->idreemplazo_cupo, $request->observaciones);
      $cuposPuestos->createCupoPuesto($request->cupos_servicio, $reemplazo_cupo, $request->observaciones);
      if ($cuposPuestos) {
        ReemplazoCupo::actualizarCuposRestantesMismoEfector($request["idefector"], $request["cuposRestantes"]);
        return $this->sendResponse(true, 'Cupo creado correctamente', 200);
      } else {
        return $this->sendError("Error al intentar crear cupos");
      }
    } else {
      return $this->sendError("Error al intentar crear cupos");
    }
  }

  public function getFuncionesPuesto(int $idservicio, int $idefector)
  {
    $grupoFuncionPuesto = $this->obtenerGrupoFuncionPuesto($idservicio, $idefector);
    $datosCupos = [];
    $totalEfectores = 0;
    $totalServicio = 0;
    $bandera = 0;

    foreach ($grupoFuncionPuesto as $gf) {
      $totalServicio = $this->obtenerTotalAgentesServicio($idservicio, $gf->idtipo_puesto);
      $totalEfectores = $this->obtenerTotalAgentesEfector($idservicio, $gf->idtipo_puesto);
      // si ya existe el puesto en el arreglo 
      // sumo el total de agentes
      if ($bandera === $gf->idtipo_puesto) {
        $bandera = $gf->idtipo_puesto;
        foreach ($datosCupos as $key => $value) {
          if ($datosCupos[$key]["idtipo_puesto"] === $gf->idtipo_puesto) {
            $datosCupos[$key]["totalAgentesEfector"] = intval($datosCupos[$key]["totalAgentesEfector"]) + intval($totalEfectores);
            $datosCupos[$key]["totalAgentesServicio"] = intval($datosCupos[$key]["totalAgentesServicio"]) + intval($totalServicio);
            // $datosCupos[$key]["totalAgentesEfector"] = intval($totalEfectores);
            // $datosCupos[$key]["totalAgentesServicio"] = intval($totalServicio);
          }
        }
      }
      // sino agrego al arreglo
      else {
        $bandera = $gf->idtipo_puesto;
        $data = [
          "idservicio" => $gf->iddependencia,
          "dependencia" => $gf->dependencia,
          "idtipo_puesto" => $gf->idtipo_puesto,
          "puesto" => $gf->tipo_puesto,
          "cupo" => $gf->cupo,
          "eventual" => $gf->eventual,
          "observaciones" => $gf->observaciones,
          "totalAgentesEfector" => $totalEfectores,
          "totalAgentesServicio" => $totalServicio,
        ];
        array_push($datosCupos, $data);
      }
    }
    // dd($datosCupos);
    return $this->sendResponse($datosCupos, "Funciones por puestos", 200);
  }

  public function obtenerGrupoFuncionPuesto(int $idservicio, int $idefector): Collection
  {
    $funciones = ReemplazoCupo::obtenerPuestosPorFunciones($idservicio);
    // dd($funciones);
    return $funciones;
  }

  public function obtenerTotalAgentesEfector(int $idefector, int $idGrupofuncion): int
  {
    $cantidadAgentesEfector = ReemplazoCupo::obtenerTotalAgentesEfector($idefector, $idGrupofuncion);
    return $cantidadAgentesEfector;
  }

  public function obtenerTotalAgentesServicio(int $idservicio, int $idtipo_puesto): int
  {
    $cantidadAgentesServicio = ReemplazoCupo::obtenerTotalAgentesServicio($idservicio, $idtipo_puesto);
    return $cantidadAgentesServicio;
  }

  public function getInfoCupoDependencia(int $id)
  {
    // $infoCupoDependencia = ReemplazoCupoPuesto::getInfoCupoDependencia($id);
    $infoCupoDependencia = ReemplazoCupo::getInfoCupoDependencia($id);
    return $infoCupoDependencia;
  }
  public function getInfoCupoServicio(int $id)
  {
    // $infoCupoDependencia = ReemplazoCupoPuesto::getInfoCupoDependencia($id);
    $infoCupoServicio = ReemplazoCupoPuesto::getInfoCupoServicio($id);
    return $infoCupoServicio;
  }

  public function createDetalleCupoOrUpdate(array $cuposPuestos): bool
  {
    foreach ($cuposPuestos as $puesto) {
      $idservicio = $puesto["idservicio"];
      $idtipo_puesto = $puesto["idtipo_puesto"];
      $reemplazoCupo = ReemplazoCupoPuesto::where("idservicio", $idservicio)->where("idtipo_puesto", $idtipo_puesto)->first();
      if (isset($reemplazoCupo) && isset($reemplazoCupo->idreemplazo_cupo)) {
        $reemplazoCupo->updateCupoPuesto($cuposPuestos, $reemplazoCupo->idreemplazo_cupo);
        return true;
      } else {
        $reemplazo_cupo = ReemplazoCupoPuesto::where("idservicio", $idservicio)->first();
        $cuposReemplazoPuestos = new ReemplazoCupoPuesto();
        if (isset($cuposReemplazoPuestos) && isset($cuposReemplazoPuestos->idreemplazo_cupo)) {
          $cuposReemplazoPuestos->addCupoEfectorExistente($cuposPuestos, $reemplazo_cupo->idreemplazo_cupo);
          return true;
        } else {
          return false;
        }
      }
      return false;
    }
  }

  /**
   * Saber si la dependencia es una Areas Operativas.
   * @return \Illuminate\Http\JsonResponse
   */
  public function isAO(int $idefector): JsonResponse
  {
    $listAO = [];
    $areasOperativas = Dependencia::getIdsAO();

    foreach ($areasOperativas as $key => $value) {
      array_push($listAO, $areasOperativas[$key]->id_area_operativa);
    }

    if (in_array($idefector, $listAO)) {
      return $this->sendResponse(true, "Es una AO", 200);
    }
    return $this->sendError("No es una AO");
  }

  /**
   * Obtener puestos de un Areas Operativas.
   * @return \Illuminate\Http\JsonResponse
   */
  public function obtenerPuestoAO(int $idefector): JsonResponse
  {
    $puestosAO = Dependencia::getPuestosAO($idefector);
    $cuposAO = ReemplazoCupoPuesto::where('idservicio', $idefector)->select('idtipo_puesto', 'cupo')->get();

    if (isset($puestosAO)) {
      foreach ($puestosAO as $puesto) {
        foreach ($cuposAO as $cupo) {
          if ($puesto->idtipo_puesto === $cupo->idtipo_puesto) {
            $puesto->cupo = $cupo->cupo;
            $puesto->observaciones = $cupo->observaciones;
          }
        }
      }
      return $this->sendResponse($puestosAO, "Listado de puestos de la AO", 200);
    }
    return $this->sendError("No se encontraron puestos para esta AO");
  }

  /**
   * Obtener dependencias de una Areas Programatica.
   * @return \Illuminate\Http\JsonResponse
   */
  public function obtenerDependenciasAP(int $idefector, string $cod): JsonResponse
  {
    $dependenciasAreaProgramatica = Dependencia::obtenerDependenciasAP($idefector, $cod);

    if (isset($dependenciasAreaProgramatica[0]->iddependencia)) {
      return $this->sendResponse($dependenciasAreaProgramatica, "Lista de dependencias de la AP", 200);
    }
    return $this->sendError("No se encontraron dependencias para esa AP");
  }

  /**
   * Saber si la dependencia es una Areas Programaticas.
   * @return \Illuminate\Http\JsonResponse
   */
  public function isAP(int $idefector): JsonResponse
  {
    $listAP = [];
    $areasProgramaticas = Dependencia::getIdsAP();

    foreach ($areasProgramaticas as $key => $value) {
      array_push($listAP, $areasProgramaticas[$key]->id_area_programatica);
    }

    if (in_array($idefector, $listAP)) {
      return $this->sendResponse(true, "Es una AP", 200);
    }
    return $this->sendError("No es una AP");
  }

  /**
   * Saber si la dependencia es la red de servicios
   * @return JsonResponse 
   */
  public function isRedServicio(int $iddependencia): JsonResponse
  {
    // ids red de servicios
    $idsServicio = array(1114, 191);
    $redServicio = Dependencia::getIdsRedServicio($iddependencia);

    if (in_array($redServicio[0]->iddependencia, $idsServicio)) {
      return $this->sendResponse(true, "Es la red de servicios", 200);
    }
    return $this->sendError("No es la red de servicio");
  }

  /**
   * listado de dependencia de la red de servicios
   * @return JsonResponse 
   */
  public function dependenciasRedServicio(int $iddependencia, string $cod): JsonResponse
  {
    $redServicio = Dependencia::getDependenciasRedServicio($iddependencia, $cod);

    if ($redServicio) {
      return $this->sendResponse($redServicio, "Listado de dependencias de la red de servicios", 200);
    }
    return $this->sendError("No se encontraron dependencias para la red de servicio");
  }

  /**
   * get data para vueselect red servicio
   * @param int $iddependencia
   * @param string $cod
   * @return JsonResponse 
   */
  public function obtenerDependenciasSoloRedServicio(int $iddependencia, string $cod): JsonResponse
  {
    $redServicioDependencias = Dependencia::getDependenciasRedServicioSelect($iddependencia, $cod);
    if ($redServicioDependencias) {
      return $this->sendResponse($redServicioDependencias, 'Listado de dependencias red servicio sin AP', 200);
    }
    return $this->sendError('No se encontraron dependencias red servicio');
  }
  /**
   * Obtener select de Areas Programaticas para vueselect.
   * @return \Illuminate\Http\JsonResponse
   */
  public function getDependenciasAPSelect(int $idefector, string $cod): JsonResponse
  {
    $areasProgramaticas = Dependencia::obtenerDependenciasAPSelect($idefector, $cod);

    if (isset($areasProgramaticas) && !empty($areasProgramaticas)) {
      return response()->json([
        'data' => $areasProgramaticas,
        'message' => "Listado de dependencias de la AP",
        'success' => true
      ], 200);
      // return $this->sendResponse($areasProgramaticas, "Listado de dependencias de la AP", 200);
    }

    return response()->json([
      'data' => [],
      'message' => "No se encontro ninguna dependencia para la AP seleccionada",
      'success' => false
    ], 200);
    // return $this->sendError("No se encontro ninguna dependencia para la AP seleccionada");
  }

  /**
   * Process datatables ajax request.
   * @return \Illuminate\Http\JsonResponse
   */
  public function getReemplazosDataTable(ReemplazoCupoDataTable $dataTable)
  {
    return $dataTable->dataTable()->toJson();
  }

  /**
   * Control para la asignacion de cupos que no supere la mitad + 1
   * y que no supere los cupos max del efector
   * @param int $idefector
   * @param int $totalAgentesServicio
   * @param int $cuposXAsignar
   * @return JsonResponse
   */
  public function controlCupoReemplazo(int $cupoEfector, int $totalAgentes, int $cuposXAsignar): JsonResponse
  {
    // if (($cupoEfector->cupo_max < $cuposXAsignar) || ($cupoEfector->cupo_restantes < $cuposXAsignar)) {
    if ($totalAgentes <= 0) {
      return $this->sendError("No se pueden asignar cupos, no hay agentes en el puesto seleccionado.");
    }

    if (($cupoEfector < $cuposXAsignar)) {
      return $this->sendError("La cantidad de cupos por asignar supera el cupo max del efector.");
    }

    // condicion que no supere la mitad + 1
    $cumpleCondicion = ReemplazoCupoPuesto::controlCupoReemplazo($totalAgentes, $cuposXAsignar);
    if ($cumpleCondicion) {
      return $this->sendResponse(true, "Cumple con la condición de cupo en días", 200);
    } else {
      return $this->sendError("No cumple con la condición de cupo en días", 201);
    }
  }

  public function actualizarCupoEfector($idefector, $cupo)
  {
    $cupos = ReemplazoCupo::where('idefector', $idefector)->orderBy('idreemplazo_cupo', 'desc')->first();

    if (isset($cupos->cupo_restantes)) {
      // control para no cargar cupo al efector que sea menor a los cupos ya registrados en sus servicios
      $controlCupoEfectorConRestantes = $cupos->cupo_max - $cupos->cupo_restantes;
      if ($cupo < $controlCupoEfectorConRestantes) {
        return $this->sendError("La cantidad de cupos a asignar al efector es menor a los cupos ya cargados para el mismo", 201);
      }
      // control cupos a asignar a los servicios no sean mayor a los cupos restantes 
      $controlCuposYaCargados = $cupo - ($cupos->cupo_max - $cupos->cupo_restantes);
      if ($controlCuposYaCargados < 0) {
        return $this->sendError("La cantidad de cupos a asignar es menor a los cupos ya cargados para el efector", 201);
      } else {
        $cupoActualizado = ReemplazoCupo::actualizarCupoEfector($cupo, $cupos->idreemplazo_cupo);
        if ($cupoActualizado) {
          return $this->sendResponse($cupoActualizado, "Se actualizó el cupo general del efector correctamente.", 200);
        } else {
          return $this->sendError("Error al actualizar el cupo general del efector", 201);
        }
      }
    }
  }

  /**
   * Actualizar el cupo de un puesto
   * @param int $idreemplazo_cupo_puesto
   * @param int $cupo_puesto
   * @return JsonResponse
   */
  public function actualizarCupoPuesto(int $idreemplazo_cupo_puesto, $cupo_puesto): JsonResponse
  {
    //  $reemplazo_puesto = ReemplazoCupoPuesto::actualizarCupoPuesto(idreemplazo_cupo_puesto: $idreemplazo_cupo_puesto, cupo_puesto: $cupo_puesto);
    $reemplazo_puesto = ReemplazoCupoPuesto::where('idreemplazo_cupo_puesto', $idreemplazo_cupo_puesto)->first();
    $reemplazo_cupo = ReemplazoCupo::where('idreemplazo_cupo', $reemplazo_puesto->idreemplazo_cupo)->first();

    $cuposRestantesSinRestarNuevoCupo = (intval($reemplazo_cupo->cupo_restantes) + intval($reemplazo_puesto->cupo));
    $cupoRestantes = (intval($reemplazo_cupo->cupo_restantes) + intval($reemplazo_puesto->cupo)) - intval($cupo_puesto);

    if ($reemplazo_cupo->cupo_totales < $cupoRestantes) {
      return $this->sendError("Error, no se puede asignar un cupo que exceda los cupos totales de efector.");
    }

    // if ($reemplazo_cupo->cupo_restantes < $cupo_puesto) {
    if ($cuposRestantesSinRestarNuevoCupo < $cupo_puesto) {
      return $this->sendError("Error, no se puede asignar un cupo que sea menor que los cupos restantes.");
    } else {
      $cupoActualizado = ReemplazoCupoPuesto::actualizarCupoPuesto($idreemplazo_cupo_puesto, $cupo_puesto);
      if ($cupoActualizado) {
        return $this->sendResponse($cupoActualizado, "Se actualizó el cupo del puesto correctamente.", 200);
      } else {
        return $this->sendError("Error al actualizar el cupo del puesto.", 201);
      }
    }
  }

  public function actualizarEventualPuesto(int $idreemplazo)
  {
    $reemplazo_puesto = ReemplazoCupoPuesto::actualizarEventualPuesto($idreemplazo);
    //  dd($reemplazo_puesto);
    if ($reemplazo_puesto) {
      return $this->sendResponse(true, "Eventual actualizado correctamente.", 200);
    } else {
      return $this->sendError("Error al actualizar eventual");
    }
  }

  /**
   * Modificar observacion de un efector
   * @param int $idreemplazo_cupo
   * @return JsonResponse
   */
  public function modificarObservacion(Request $request, int $idreemplazo_cupo): JsonResponse
  {
    $request->validate([
      "observaciones" => "required",
      "idreemplazo_puesto" => "required"
    ]);
    $observacion = $request->observaciones;
    $idreeCupo = $request->idreemplazo_puesto;

    $obs = ReemplazoCupo::modificarObservacion($observacion, $idreeCupo);

    if ($obs) {
      return $this->sendResponse($obs, "Se actualizo observación interna correctamente.");
    } else {
      return $this->sendError("Error al actualizar observación interna");
    }
  }

  public function exportarExcel(Request $request)
  {
    $request = $this->formatDataTableRequest($request);
    $dataTable = new ReemplazoCupoDataTable();
    ob_end_clean();
    ob_start();
    return (new ReemplazoCupoExport($dataTable->dataTable($request)))
      ->download('ReemplazoCupo_' . date('d-m-Y_H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
  }

  /**
   * Generar replicas de cupos reemplazo
   */
  public function getReplicaCupos(Request $request) {
    $request->validate(["idperiodo" => "required"]);

    $reemplazo_cupo = ReemplazoCupo::getReemplazosCupoSinEventual(); 
    $reemplazo_cupo_puesto = ReemplazoCupoPuesto::getReemplazosCupoPuestoSinEventual(); 

    // dd($reemplazo_cupo, $reemplazo_cupo_puesto);
    if (isset($reemplazo_cupo) && isset($request->idperiodo)) {
      // foreach ($reemplazo_cupo as $ree) {
      //   $ree->estado = false;
      //   $ree->update();
      // }
      ReemplazoCupo::createReemplazoCupoReplica($reemplazo_cupo, $request->idperiodo);
    }

    if (isset($reemplazo_cupo_puesto)) {
      // foreach ($reemplazo_cupo_puesto as $ree) {
      //   $ree->estado = false;
      //   $ree->update();
      // }
      ReemplazoCupoPuesto::createCupoPuestoReplica($reemplazo_cupo_puesto, $reemplazo_cupo);
    }

    if (isset($reemplazo_cupo) && isset($request->idperiodo) && isset($reemplazo_cupo_puesto)) {
      return $this->sendResponse(true, 'Replica de cupos realizada correctamente.');
    }
    else {
      return $this->sendError('Error al crear replica de cupos.');
    }
  }

  /**
   * Obtener reemplazo cupos
   */
  function getReemplazoCupo(): JsonResponse {
    $reemplazo_cupo = ReemplazoCupo::getReemplazoCupo();
    $reemplazo_cupo_puesto = ReemplazoCupoPuesto::getReemplazoCupo();
    if (count($reemplazo_cupo) > 0 || count($reemplazo_cupo_puesto) > 0) {
      return $this->sendResponse($reemplazo_cupo, "Datos reemplazo cupo");
    }
    return $this->sendError("Error. No hay cupos registrados.");
  }
}
