<?php

namespace App\Http\Controllers\API;

use App\Models\NovedadCargo;
use App\Models\EfectivaPrestacionCargo;
use App\Models\Periodo;
use App\Models\Dependencia;
use App\Http\Resources\NovedadCargoResource;
use App\Http\Resources\NovedadCargoExportResource;
use App\Http\Requests\CreateEfectivaPrestacionCargoAPIRequest;
use App\Exports\NovedadCargoExport;
use App\Models\PeriodoEPCargo;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Maatwebsite\Excel\Facades\Excel;
use App\Impresiones\FormularioNovedadCargoAprobado;
use App\Impresiones\FormularioNovedadCargoObservado;



class NovedadCargoAPIController extends AppBaseController
{
    public function __construct()
    {

    }

    public function getFormularios($idefector, $idperiodo_liq,$idtipo_visado_novedad=null)
    {

        $formularios = NovedadCargoResource::
                            collection($this->getNovedades($idefector,$idperiodo_liq,$idtipo_visado_novedad));

        if ($formularios) {
            return response()->json([
                'success' => true,
                'message' => 'Formularios cargados correctamente',
                'formularios' => $formularios,
                    ], 200);
        }
        return $this->sendError('Ocurrio un error al cargar los formularios');
    }

    public function visar(Request $request, $idnovedad_cargo)
    {
        $this->validate($request, [
            'idtipo_visado_novedad' => 'required|integer',
        ]);
        $novedad = NovedadCargo::findOrFail($idnovedad_cargo);
        $novedad->idtipo_visado_novedad = $request->idtipo_visado_novedad;

        if ($novedad->save()) {
        return $this->sendResponse(NovedadCargoResource::make($novedad), 'Novedad visada correctamente');
        }
        return $this->sendError('Ocurrió un error al visar la novedad');

    }

    public function comentar(Request $request, $idnovedad_cargo)
    {
        $this->validate($request, [
            'comentarios' => 'required|string',
        ]);

        $novedad = NovedadCargo::find($idnovedad_cargo);
        $novedad->comentarios = $request->comentarios;
        if ($novedad->save()) {
            return $this->sendResponse($novedad, 'Comentario agregado correctamente');
        }
        return $this->sendError('Ocurrió un error al comentar');
    }

    public function exportarXLS($idefector=null, $idperiodo_liq)
    {
        if (!is_numeric($idefector)) {
            $idefector = null;
        }
        $data = $this->formatFormularioPDF($this->getNovedades($idefector,$idperiodo_liq));


        return Excel::download(new NovedadCargoExport($data),'novedad_cargos_'.now()->format('d_m_Y_H_i').'.xlsx');

    }

    public function exportarFormularioPDF($idefector, $idperiodo_liq,$tipo_formulario)
    {
        $periodo_liq = Periodo::find($idperiodo_liq);
        $periodo_ep = Periodo::find($idperiodo_liq-1);
        $efector = Dependencia::find($idefector);
        $tipo_visado = match ($tipo_formulario) {
            'mensuales','retroactivas' => 2,
            'desaprobadas' => 3,
            default => 2,
        };
        $formularios = $this->formatFormularioPDF($this->getNovedades($idefector,$idperiodo_liq,$tipo_visado, $tipo_formulario==="retroactivas" ? true:false,true));
        $data = [
            'periodo_ep' => $periodo_ep->periodo,
            'periodo_liq' => $periodo_liq->periodo,
            'efector' => $efector->dependencia,
            'tipo_formulario' => $tipo_formulario === 'retroactivas' ? 'LIQUIDACIÓN RETROACTIVA CON EFECTIVA PRESTACIÓN DE SERVICIOS- REEMPLAZOS':'LIQUIDACIÓN MENSUAL CON EFECTIVA PRESTACIÓN DE SERVICIOS- REEMPLAZOS',
        ];

        match ($tipo_formulario) {
            'mensuales' => FormularioNovedadCargoAprobado::imprimir($data, $formularios),
            'desaprobadas' => FormularioNovedadCargoObservado::imprimir($data, $formularios),
            'retroactivas' => FormularioNovedadCargoAprobado::imprimir($data, $formularios),
            default => FormularioNovedadCargoAprobado::imprimir($data, $formularios),
        };
        exit();
    }

    private function getNovedades($idefector=null, $idperiodo_liq, $idtipo_visado_novedad=null,$retroactivo=null,$pdf=null)
    {
        $efector = null;
        if (!is_null($idefector)) {
            $efector = Dependencia::find($idefector);
        }
        $periodo_liq = Periodo::find($idperiodo_liq);

        return NovedadCargo
                ::efector($efector)
                ->periodoLiq($periodo_liq)
                ->tipoVisadoNovedad($idtipo_visado_novedad)
                ->pdf($pdf)
                ->retroactivo($retroactivo)
                ->with(['efectivaPrestacion','efectivaPrestacion.periodosDesdoblados','efectivaPrestacion.cargo','efectivaPrestacion.cargo.agentePropuesto','efectivaPrestacion.cargo.cargoReemplazado'])
                ->get()
                ->sortBy(['efectivaPrestacion.cargo.efector.dependencia','efectivaPrestacion.cargo.agentePropuesto.apellido','efectivaPrestacion.cargo.agentePropuesto.nombre']);
    }

    private function formatFormularioPDF($formularios) : array
    {
        $data = [];
        foreach ($formularios as $formulario) {
            $estado_vigente = $formulario->efectivaPrestacion->cargo->getEstadoVigente($formulario->efectivaPrestacion->periodoEP);
            if ($formulario->efectivaPrestacion->periodosDesdoblados()->exists()) {
                foreach ($formulario->efectivaPrestacion->periodosDesdoblados as $periodo) {
                    $data[] = [
                        'idnovedad_cargo' => $formulario->idnovedad_cargo,
                        'idefectiva_prestacion_cargo' => $formulario->efectivaPrestacion->idefectiva_prestacion_cargo,
                        'idcargo' => $formulario->efectivaPrestacion->cargo->idcargo,
                        'campania' => $formulario->efectivaPrestacion->cargo->tipoCampania->tipocampania,
                        'documento' => $formulario->efectivaPrestacion->cargo->agentePropuesto->documento,
                        'apellido_nombre' => $formulario->efectivaPrestacion->cargo->agentePropuesto->apellido_nombre,
                        'fnacimiento' => $formulario->efectivaPrestacion->cargo->agentePropuesto->fnacimiento->format('d/m/Y'),
                        'dias' => $formulario->efectivaPrestacion->dias,
                        'ep_desde' => $periodo->fdesde->format('d/m/Y'),
                        'ep_hasta' => $periodo->fhasta->format('d/m/Y'),
                        'efector' => $formulario->efectivaPrestacion->cargo->efector->dependencia,
                        'servicio' => $formulario->efectivaPrestacion->cargo->servicio->dependencia,
                        'nivel' => $formulario->efectivaPrestacion->cargo->tipoNivel->tiponivel,
                        'funcion' => $formulario->efectivaPrestacion->cargo->tipoFuncion->tipofuncion,
                        'tipo_cargo' => $formulario->efectivaPrestacion->cargo->tipoCargo->tipocargo_corto,
                        'tipo_agrupamiento' => $formulario->efectivaPrestacion->cargo->tipoAgrupamiento->tipoagrupamiento,
                        'primera_vez' => $formulario->efectivaPrestacion->primera_vez ? "SI":"NO",
                        'estado_vigente' => $estado_vigente ? $estado_vigente->cargoTipoFormulario->cargotipo_formulario:null,
                        'periodo_ep' => $formulario->efectivaPrestacion->periodoEP->periodo,
                        'periodo_liq' => $formulario->efectivaPrestacion->periodoLiquidacion->periodo,
                        'titular' => $formulario->efectivaPrestacion->cargo->cargoReemplazado()->exists() ? $formulario->efectivaPrestacion->cargo->cargoReemplazado->apellido_nombre:' - ',
                        'observaciones' => $formulario->comentarios,
                        'visado' => $formulario->tipoVisado->tipovisado,
                        'horario' => $formulario->efectivaPrestacion->cargo->horarios->first()->horario_cargo,
                    ];
                }
            } else {

                $limites_periodo_ep = PeriodoEPCargo::limitPeriodo(
                                                $formulario->efectivaPrestacion->cargo->fecha_inicio_alta,
                                                $formulario->efectivaPrestacion->cargo->fecha_vencimiento_real,
                                                $formulario->efectivaPrestacion->periodoEP);
                $data[] = [
                        'idnovedad_cargo' => $formulario->idnovedad_cargo,
                        'idefectiva_prestacion_cargo' => $formulario->efectivaPrestacion->idefectiva_prestacion_cargo,
                        'idcargo' => $formulario->efectivaPrestacion->cargo->idcargo,
                        'campania' => $formulario->efectivaPrestacion->cargo->tipoCampania->tipocampania,
                        'documento' => $formulario->efectivaPrestacion->cargo->agentePropuesto->documento,
                        'apellido_nombre' => $formulario->efectivaPrestacion->cargo->agentePropuesto->apellido_nombre,
                        'fnacimiento' => $formulario->efectivaPrestacion->cargo->agentePropuesto->fnacimiento->format('d/m/Y'),
                        'dias' => $formulario->efectivaPrestacion->dias,
                        'ep_desde' => $limites_periodo_ep['fdesde']->format('d/m/Y'),
                        'ep_hasta' => $limites_periodo_ep['fhasta']->format('d/m/Y'),
                        'efector' => $formulario->efectivaPrestacion->cargo->efector->dependencia,
                        'servicio' => $formulario->efectivaPrestacion->cargo->servicio->dependencia,
                        'nivel' => $formulario->efectivaPrestacion->cargo->tipoNivel->tiponivel,
                        'funcion' => $formulario->efectivaPrestacion->cargo->tipoFuncion->tipofuncion,
                        'tipo_cargo' => $formulario->efectivaPrestacion->cargo->tipoCargo->tipocargo_corto,
                        'tipo_agrupamiento' => $formulario->efectivaPrestacion->cargo->tipoAgrupamiento->tipoagrupamiento,
                        'primera_vez' => $formulario->efectivaPrestacion->primera_vez ? "SI":"NO",
                        'estado_vigente' => $estado_vigente ? $estado_vigente->cargoTipoFormulario->cargotipo_formulario:null,
                        'periodo_ep' => $formulario->efectivaPrestacion->periodoEP->periodo,
                        'periodo_liq' => $formulario->efectivaPrestacion->periodoLiquidacion->periodo,
                        'titular' => $formulario->efectivaPrestacion->cargo->cargoReemplazado()->exists() ? $formulario->efectivaPrestacion->cargo->cargoReemplazado->apellido_nombre:' - ',
                        'observaciones' => $formulario->comentarios,
                        'visado' => $formulario->tipoVisado->tipovisado,
                        'horario' => $formulario->efectivaPrestacion->cargo->horarios->first()->horario_cargo,
                ];
            }
        }
        return $data;
    }
}
