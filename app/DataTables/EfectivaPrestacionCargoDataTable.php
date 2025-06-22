<?php

namespace App\DataTables;

use App\Models\Cargo;
use Yajra\DataTables\Services\DataTable;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\Dependencia;
use App\Models\Periodo;
use App\Models\GrupoFuncion;
use Carbon\Carbon;

class EfectivaPrestacionCargoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $model = Cargo::with('cargoCambioEstados')
        ->with('cargoCambioEstados.cargoTipoFormulario')
        // ->with('cargoCambioEstados.cargoTipoVisado')
        // ->with('cargoCambioEstados.cargoTipoFirma')
        ->with('cargoCambioEstados.cargoCambioEstadoObs')
        ->with('cargoCambioEstados.periodoDesde')
        ->with('cargoCambioEstados.periodoHasta')
        ->with('efector')
        ->with('servicio')
        ->with('tipoFuncion')
        ->with('tipoEspecialidad')
        ->with('tipoNivel')
        ->with('tipoAgrupamiento')
        ->with('tipoCampania')
        ->with('titulo')
        ->with('tipoEspecialidad')
        ->with('agentePropuesto.tipoSexo')
        ->with('agentePropuesto')
        ->with('cargoReemplazado')
        ->with('cargoReemplazado.puesto')
        ->with('cargoReemplazado.puesto.agente')
        ->with('efectivaPrestacionCargo')
        ->with('efectivaPrestacionCargo.cargoTipoVisadoEp')
        ->with('efectivaPrestacionCargo.periodo')
        ->with('efectivaPrestacionCargo.efectivaPrestacionObsCargo')
        ->with('efectivaPrestacionCargo.efectivaPrestacionCargoPeriodos');

        $data = datatables()
            ->eloquent($model)
            ->addColumn('actions', function ($model) {
                $buttons = '<button class="btn btn-xs" onclick="vm.mostrarFormulario(' . $model->idcargo_cambio_estado . ')"><i class="fas fa-eye"></i></button>
                <button class="btn btn-xs" onclick="vm.exportarPDFSolicitud(' . $model->idcargo_cambio_estado . ')"><i class="fas fa-file-pdf"></i></button>';
                $buttons .= $model->idcargo_tipo_visado === 1 ?
                '<button class="btn btn-xs" onclick="vm.borrarFormulario(' . $model->idcargo_cambio_estado . ')"><i class="fas fa-trash"></i></button>' :
                '';
                return $buttons;
            })
            ->blacklist(['actions'])
            ->filterColumn('tipo_campania.tipocampania', function ($query, $keyword) {
                $query->whereHas('tipoCampania', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tipocampania) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->orderColumn('cargo.tipo_cargo_formateado', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHas('tipoCargo', function ($q) use ($order) {
                        $q->orderBy("tipocargo", $order);
                    });
                });
            })
            ->filterColumn('agente_propuesto.documento', function ($query, $keyword) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("documento = ?", intval($keyword));
                    }
                );
            })
            ->filterColumn('agente_propuesto.apellido', function ($query, $keyword) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("UPPER(apellido) LIKE ?", "%".strtoupper($keyword)."%");
                    }
                );
            })
            ->filterColumn('agente_propuesto.nombre', function ($query, $keyword) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
                    }
                );
            })
            ->filterColumn('agente_propuesto.sexo_formateado', function ($query, $keyword) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($qu, $type) use ($keyword) {
                        $qu->whereHas('tipoSexo', function ($q) use ($keyword) {
                            $q->whereRaw("UPPER(abreviatura) = ?", strtoupper($keyword));
                        });
                    }
                );
            })
            ->filterColumn('tipo_funcion.tipofuncion', function ($query, $keyword) {
                $funciones = GrupoFuncion::funcionesGrupo($keyword);
                if (isset($funciones) && !empty($funciones)) {
                    $query->whereIn('idtipo_funcion', $funciones);
                } else {
                    $query->whereHas('tipoFuncion', function ($q) use ($keyword) {
                        $q->whereRaw("UPPER(tipofuncion) LIKE ?", "%".strtoupper($keyword)."%");
                    });
                }
            })
            ->filterColumn('efector.dependencia', function ($query, $keyword) {
                $efector = Dependencia::efector($keyword);
                if (isset($efector, $efector->iddependencia)) {
                    $query->whereIn("idefector", $efector->getIdsDescendencia());
                }
            })
            ->filterColumn('servicio.dependencia', function ($query, $keyword) {
                $query->whereHas('servicio', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(dependencia) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('periodo_desde', function ($query, $keyword) {
                if (preg_match_all('!\d+!', $keyword)) {
                    $periodo = Periodo::whereRaw("UPPER(periodo) LIKE ?", "%".strtoupper($keyword)."%")->first();
                } else {
                    $periodo = Periodo::whereRaw("UPPER(periodo) LIKE ?", "%".strtoupper($keyword)."%")
                    ->whereRaw("anio = ?", date('Y'))
                    ->first();
                }
                if (isset($periodo)) {
                    $fechaDesde = Carbon::parse($periodo->fdesde)->format('Y-m-d');
                    $fechaHasta = Carbon::parse($periodo->fhasta)->format('Y-m-d');
                    $query->whereRaw(
                        "(fecha_desde <= ? AND fecha_hasta >= ?) OR
                        (fecha_desde >= ? AND (fecha_hasta > ? OR fecha_hasta IS NULL)) OR  
                        (fecha_desde >= ? AND fecha_hasta <= ?) OR 
                        (fecha_desde <= ? AND fecha_hasta <= ?)",
                        [$fechaDesde, $fechaHasta, $fechaDesde, $fechaHasta, $fechaDesde, $fechaHasta, $fechaDesde, $fechaHasta]
                    );
                }
            })
            ->filterColumn('fecha_desde_calculada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw("fecha_desde IS NULL");
                } else {
                    $fecha = (bool)strtotime($keyword) ? (\Carbon\Carbon::parse($keyword)) : null;
                    if (isset($fecha)) {
                        $query->whereRaw("fecha_desde = ?", $fecha->format('Y-m-d'));
                    }
                }
            })
            ->filterColumn('fecha_hasta_calculada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw("fecha_hasta IS NULL");
                } else {
                    $fecha = (bool)strtotime($keyword) ? (\Carbon\Carbon::parse($keyword)) : null;
                    if (isset($fecha)) {
                        $query->whereRaw("fecha_hasta = ?", $fecha->format('Y-m-d'));
                    }
                }
            })
            ->orderColumn('tipo_campania.tipocampania', function ($query, $order) {
                $query->whereHas('tipoCampania', function ($q) use ($order) {
                    $q->orderBy("tipocampania", $order);
                });
            })
            ->orderColumn('tipo_cargo_formateado', function ($query, $order) {
                $query->whereHas('tipoCargo', function ($q) use ($order) {
                    $q->orderBy("tipocargo", $order);
                });
            })
            ->orderColumn('agente_propuesto.documento', function ($query, $order) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($order) {
                        $q->orderBy("documento", $order);
                    }
                );
            })
            ->orderColumn('agente_propuesto.apellido', function ($query, $order) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q) use ($order) {
                        $q->orderBy("apellido", $order);
                    }
                );
            })
            ->orderColumn('agente_propuesto.nombre', function ($query, $order) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($q) use ($order) {
                        $q->orderBy("nombre", $order);
                    }
                );
            })
            ->orderColumn('agente_propuesto.sexo_formateado', function ($query, $order) {
                $query->whereHasMorph(
                    'agentePropuesto',
                    [Agente::class, Candidato::class],
                    function ($qu) use ($order) {
                        $qu->whereHas('tipoSexo', function ($q) use ($order) {
                            $q->orderBy("tiposexo", $order);
                        });
                    }
                );
            })
            ->orderColumn('tipo_funcion.tipofuncion', function ($query, $order) {
                $query->whereHas('tipoFuncion', function ($q) use ($order) {
                    $q->orderBy("tipofuncion", $order);
                });
            })
            ->orderColumn('efector.dependencia', function ($query, $order) {
                $query->whereHas('efector', function ($q) use ($order) {
                    $q->orderBy("dependencia", $order);
                });
            })
            ->orderColumn('servicio.dependencia', function ($query, $order) {
                $query->whereHas('servicio', function ($q) use ($order) {
                    $q->orderBy("dependencia", $order);
                });
            })
            ->orderColumn('periodo_desde', function ($query, $order) {
                $query->whereHas('periodoDesde', function ($q) use ($order) {
                    $q->orderBy("periodo", $order);
                });
            })
            ->orderColumn('fecha_desde_calculado', function ($query, $order) {
                $query->orderBy("fecha_desde", $order);
            })
            ->orderColumn('periodo_hasta_formateado', function ($query, $order) {
                $query->whereHas('periodoHasta', function ($q) use ($order) {
                    $q->orderBy("periodo", $order);
                });
            })
            ->orderColumn('fecha_hasta_calculado', function ($query, $order) {
                $query->orderBy("fecha_hasta", $order);
            })
            ->rawColumns(['actions'])
            ->setRowId(function ($model) {
                return $model->idcargo;
            });
        // ->setRowClass(function ($model) {
        //     switch ($model->idcargo_tipo_visado) {
        //         case 1: return '';
        //         case 2: return 'table-warning';
        //         case 3: return 'table-danger';
        //         case 4: return 'table-warning';
        //         case 5: return 'table-danger';
        //         case 6: return 'table-warning';
        //         case 7: return 'table-danger';
        //         case 8: return 'table-success';
        //         case 9: return 'table-info';
        //         default: return '';
        //     }
        // });

        return $data;
    }
}
