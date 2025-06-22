<?php

namespace App\DataTables;

use App\Models\Fecha;
use App\Models\Dependencia;
use App\Models\GrupoFuncion;
use App\Models\Reemplazo;
use Yajra\DataTables\Services\DataTable;

class ReemplazoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, $tipoReemplazo = 'f1')
    {
        $relations = [
            'tipoNovedad',
            'agenteReemplazado',
            'agenteReemplazante',
            'puestoReemplazado',
            'puestoReemplazado.agente',
            'puestoReemplazante',
            'puestoReemplazante.agente',
            'puestoReemplazado.tipoNivel',
            'puestoReemplazante.tipoNivel',
            'puestoReemplazado.tipoFuncion',
            'puestoReemplazante.tipoFuncion',
            'dependencia',
            'tipoNivelReemplazado',
            'tipoNivelReemplazante',
            'tipoSolicitud',
            'tipoHorario',
            'puestoReemplazado.horarios',
            'puestoReemplazado.horario_historico'
        ];
        
        if ($tipoReemplazo === 'f1') {
            $idtipo_formulario = 4;
        } else {
            $idtipo_formulario = 3;
        }

        $dependencias = Dependencia::soloEfectores()->pluck('iddependencia');
        if (!isset($query['columns'][25]['search']['value'])) {
            $model = Reemplazo::with($relations)
                                ->whereNull('aprobado')
                                ->whereNull('desaprobado')
                                ->where('idformulario', $idtipo_formulario)
                                ->whereIn('iddependencia', $dependencias);
        } else {
            $model = Reemplazo::with($relations)
                                ->where('idformulario', $idtipo_formulario)
                                ->whereIn('iddependencia', $dependencias);
        }

        $data = datatables()
            ->eloquent($model)
            ->addColumn('actions', function ($model) {
                $buttons = $model->aprobado === null && $model->desaprobado === null ? "<button type=\"button\" id=\"aprobar_{$model->idreemplazo}\" class=\"btn btn-sm btn-success\" onclick=\"vm.visar({$model->idreemplazo}, 'aprobar')\"><i class=\"fas fa-check\"></i></button>
                                                                                        <button type=\"button\" id=\"desaprobar_{$model->idreemplazo}\" class=\"btn btn-sm btn-danger\" onclick=\"vm.visar({$model->idreemplazo}, 'desaprobar')\"><i class=\"fas fa-times\"></i></button>&nbsp;" : "";
                $buttons .= ($model->aprobado === true || $model->desaprobado === true) && auth()->user()->isGerencia() ? "<button type=\"button\" id=\"desvisar_{$model->idreemplazo}\" class=\"btn btn-sm btn-warning\" onclick=\"vm.visar({$model->idreemplazo}, 'desvisar')\"><i class=\"fas fa-minus\"></i></button>&nbsp;" : "";
                return $buttons;
            })
            ->blacklist([
                'actions',
                'puesto_reemplazado.horario_trabajo_formateado',
                'puesto_reemplazante.horario_trabajo_formateado',
                'dias_trabajados_reemplazado',
                'dias_trabajados_reemplazante',
                'costo_laboral'
                ])
            ->filterColumn('dependencia.dependencia', function ($query, $keyword) {
                $efector = Dependencia::efector($keyword, true);
                if (isset($efector, $efector->iddependencia)) {
                    $descendencia = $efector->getIdsDescendencia();
                    $query->whereHas('dependencia', function ($que) use ($descendencia) {
                        $que->whereIn("iddependencia", $descendencia)
                            ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                $q->whereIn("iddependencia", $descendencia);
                            });
                    });
                } else {
                    $efector = Dependencia::efector($keyword);
                    if (isset($efector, $efector->iddependencia)) {
                        $descendencia = $efector->getIdsDescendencia();
                        $query->whereHas('dependencia', function ($que) use ($descendencia) {
                            $que->whereIn("iddependencia", $descendencia)
                                ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                    $q->whereIn("iddependencia", $descendencia);
                                });
                        });
                    }
                }
            })
            ->filterColumn('tipo_solicitud.tiposolicitud', function ($query, $keyword) {
                $query->whereHas('tipoSolicitud', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tiposolicitud) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente_reemplazado.apellido', function ($query, $keyword) {
                $query->whereHas('agenteReemplazado', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(apellido) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente_reemplazado.nombre', function ($query, $keyword) {
                $query->whereHas('agenteReemplazado', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente_reemplazado.documento', function ($query, $keyword) {
                $query->whereHas('agenteReemplazado', function ($q) use ($keyword) {
                    $q->where("documento", $keyword);
                });
            })
            ->filterColumn('puesto_reemplazado.tipo_funcion.tipofuncion', function ($query, $keyword) {
                $funciones = GrupoFuncion::funcionesGrupo($keyword);
                if (isset($funciones) && !empty($funciones)) {
                    $query->whereHas('puestoReemplazado', function ($q) use ($keyword, $funciones) {
                        $q->whereIn('idtipo_funcion', $funciones);
                    });
                } else {
                    $query->whereHas('puestoReemplazado', function ($qu) use ($keyword) {
                        $qu->whereHas('tipoFuncion', function ($q) use ($keyword) {
                            $q->whereRaw("UPPER(tipofuncion) LIKE ?", "%".strtoupper($keyword)."%");
                        });
                    });
                }
            })
            ->filterColumn('tipo_nivel_reemplazado.tiponivel', function ($query, $keyword) {
                $query->whereHas('tipoNivelReemplazado', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tiponivel) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('tipo_novedad.abreviatura', function ($query, $keyword) {
                $query->whereHas('tipoNovedad', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tiponovedad) LIKE ?", "%".strtoupper($keyword)."%")
                        ->orWhereRaw("UPPER(abreviatura) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('puesto_reemplazado.horario_trabajo_formateado', function ($query, $keyword) {
                $query->whereHas('puestoReemplazado', function ($que) use ($keyword) {
                    $que->whereHas('horarios', function ($q) use ($keyword) {
                        $q->whereRaw("hora_desde::text LIKE ?", "%".strtoupper($keyword)."%")
                            ->orWhereRaw("hora_hasta::text LIKE ?", "%".strtoupper($keyword)."%");
                    })
                    ->whereHas('horario_historico', function ($q) use ($keyword) {
                        $q->whereRaw("hora_desde::text LIKE ?", "%".strtoupper($keyword)."%")
                            ->orWhereRaw("hora_hasta::text LIKE ?", "%".strtoupper($keyword)."%");
                    });
                });
            })
            ->filterColumn('agente_reemplazante.apellido', function ($query, $keyword) {
                $query->whereHas('agenteReemplazante', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(apellido) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente_reemplazante.nombre', function ($query, $keyword) {
                $query->whereHas('agenteReemplazante', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente_reemplazante.documento', function ($query, $keyword) {
                $query->whereHas('agenteReemplazante', function ($q) use ($keyword) {
                    $q->where("documento", $keyword);
                });
            })
            ->filterColumn('puesto_reemplazante.tipo_funcion.tipofuncion', function ($query, $keyword) {
                $funciones = GrupoFuncion::funcionesGrupo($keyword);
                if (isset($funciones) && !empty($funciones)) {
                    $query->whereHas('puestoReemplazante', function ($q) use ($keyword, $funciones) {
                        $q->whereIn('idtipo_funcion', $funciones);
                    });
                } else {
                    $query->whereHas('puestoReemplazante', function ($qu) use ($keyword) {
                        $qu->whereHas('tipoFuncion', function ($q) use ($keyword) {
                            $q->whereRaw("UPPER(tipofuncion) LIKE ?", "%".strtoupper($keyword)."%");
                        });
                    });
                }
            })
            ->filterColumn('tipo_nivel_reemplazante.tiponivel', function ($query, $keyword) {
                $query->whereHas('tipoNivelReemplazante', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tiponivel) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('puesto_reemplazante.horario_trabajo_formateado', function ($query, $keyword) {
                $query->whereHas('puestoReemplazante', function ($que) use ($keyword) {
                    $que->whereHas('horarios', function ($q) use ($keyword) {
                        $q->whereRaw("hora_desde::text LIKE ?", "%".strtoupper($keyword)."%")
                            ->orWhereRaw("hora_hasta::text LIKE ?", "%".strtoupper($keyword)."%");
                    })
                    ->whereHas('horario_historico', function ($q) use ($keyword) {
                        $q->whereRaw("hora_desde::text LIKE ?", "%".strtoupper($keyword)."%")
                            ->orWhereRaw("hora_hasta::text LIKE ?", "%".strtoupper($keyword)."%");
                    });
                });
            })
            ->filterColumn('periodo_formateado', function ($query, $keyword) {
                $query->whereHas('periodo', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(periodo) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('tipoHorario.tipohorario', function ($query, $keyword) {
                $query->whereHas('tipoHorario', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tipohorario) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('fdesde', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw("fdesde IS NULL");
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaDesde = Fecha::parse($fechas[0]);
                    $fechaHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaDesde, $fechaHasta)) {
                        $query->whereBetween(
                            "fdesde",
                            [$fechaDesde, $fechaHasta]
                        );
                    }
                } else {
                    $fecha = isset($keyword) ? date('Y-m-d', strtotime(str_replace('/', '-', $keyword))) : null;
                    if (isset($fecha)) {
                        $query->whereRaw("fdesde = ?", $fecha);
                    }
                }
            })
            ->filterColumn('fhasta', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw("fhasta IS NULL");
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaDesde = Fecha::parse($fechas[0]);
                    $fechaHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaDesde, $fechaHasta)) {
                        $query->whereBetween(
                            "fhasta",
                            [$fechaDesde, $fechaHasta]
                        );
                    }
                } else {
                    $fecha = isset($keyword) ? date('Y-m-d', strtotime(str_replace('/', '-', $keyword))) : null;
                    if (isset($fecha)) {
                        $query->whereRaw("fhasta = ?", $fecha);
                    }
                }
            })
            ->filterColumn('visado_formateado', function ($query, $keyword) {
                if (strtoupper($keyword) === 'DESVISADO' || strtoupper($keyword) === 'DES' || strtoupper($keyword) === 'SIN VISAR' || is_null($keyword) || empty($keyword)) {
                    $query->whereNull("aprobado")
                        ->whereNull("desaprobado");
                }
                
                if (strtoupper($keyword) === 'APROBADOS' || strtoupper($keyword) === 'APROBADO' || strtoupper($keyword) === 'AP') {
                    $query->where("aprobado", true)
                            ->where("desaprobado", false);
                }

                if (strtoupper($keyword) === 'DESAPROBADOS' || strtoupper($keyword) === 'DESAPROBADO' || strtoupper($keyword) === 'DESAP') {
                    $query->where("aprobado", false)
                            ->where("desaprobado", true);
                }

                if (strtoupper($keyword) === 'ERROR') {
                    $query->where("aprobado", true)
                            ->where("desaprobado", true);
                }
            })
            ->orderColumn('dependencia.dependencia', function ($query, $order) {
                $query->whereHas('dependencia', function ($q) use ($order) {
                    $q->orderBy("dependencia", $order);
                });
            })
            ->orderColumn('tipo_solicitud.tiposolicitud', function ($query, $order) {
                $query->whereHas('tipoSolicitud', function ($q) use ($order) {
                    $q->orderBy("tiposolicitud", $order);
                });
            })
            ->orderColumn('agente_reemplazado.apellido', function ($query, $order) {
                $query->whereHas('agenteReemplazado', function ($q) use ($order) {
                    $q->orderBy("apellido", $order);
                });
            })
            ->orderColumn('agente_reemplazado.nombre', function ($query, $order) {
                $query->whereHas('agenteReemplazado', function ($q) use ($order) {
                    $q->orderBy("nombre", $order);
                });
            })
            ->orderColumn('agente_reemplazado.documento', function ($query, $order) {
                $query->whereHas('agenteReemplazado', function ($q) use ($order) {
                    $q->orderBy("documento", $order);
                });
            })
            ->orderColumn('puesto_reemplazado.tipo_funcion.tipofuncion', function ($query, $order) {
                $query->whereHas('puestoReemplazado', function ($qu) use ($order) {
                    $qu->whereHas('tipoFuncion', function ($q) use ($order) {
                        $q->orderBy("tipofuncion", $order);
                    });
                });
            })
            ->orderColumn('tipo_nivel_reemplazado.tiponivel', function ($query, $order) {
                $query->whereHas('tipoNivelReemplazado', function ($q) use ($order) {
                    $q->orderBy("tiponivel", $order);
                });
            })
            ->orderColumn('tipo_novedad.abreviatura', function ($query, $order) {
                $query->whereHas('tipoNovedad', function ($q) use ($order) {
                    $q->orderBy("abreviatura", $order);
                });
            })

            ->orderColumn('agente_reemplazante.apellido', function ($query, $order) {
                $query->whereHas('agenteReemplazante', function ($q) use ($order) {
                    $q->orderBy("apellido", $order);
                });
            })
            ->orderColumn('agente_reemplazante.nombre', function ($query, $order) {
                $query->whereHas('agenteReemplazante', function ($q) use ($order) {
                    $q->orderBy("nombre", $order);
                });
            })
            ->orderColumn('agente_reemplazante.documento', function ($query, $order) {
                $query->whereHas('agenteReemplazante', function ($q) use ($order) {
                    $q->orderBy("documento", $order);
                });
            })
            ->orderColumn('puesto_reemplazante.tipo_funcion.tipofuncion', function ($query, $order) {
                $query->whereHas('puestoReemplazante', function ($qu) use ($order) {
                    $qu->whereHas('tipoFuncion', function ($q) use ($order) {
                        $q->orderBy("tipofuncion", $order);
                    });
                });
            })
            ->orderColumn('tipo_nivel_reemplazante.tiponivel', function ($query, $order) {
                $query->whereHas('tipoNivelReemplazante', function ($q) use ($order) {
                    $q->orderBy("tiponivel", $order);
                });
            })
            ->orderColumn('periodo_formateado', function ($query, $order) {
                $query->orderBy("idperiodo", $order);
            })
            ->orderColumn('fdesde', function ($query, $order) {
                $query->orderBy("fdesde", $order);
            })
            ->orderColumn('fhasta', function ($query, $order) {
                $query->orderBy("fhasta", $order);
            })
            ->orderColumn('visado_formateado', function ($query, $order) {
                $query->orderBy("aprobado", $order);
            })
            ->rawColumns(['actions', 'dias_trabajados_reemplazado', 'dias_trabajados_reemplazante', 'puesto_reemplazado.horario_trabajo_formateado', 'puesto_reemplazante.horario_trabajo_formateado'])
            ->setRowId(function ($model) {
                return $model->idreemplazo;
            })
            ->setRowClass(function ($model) {
                return ($model->aprobado ? 'table-success' : ($model->desaprobado ? ('table-danger') : ''));
            });

        return $data;
    }
}
