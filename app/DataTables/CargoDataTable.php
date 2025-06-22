<?php
namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Fecha;
use App\Models\Agente;
use App\Models\Periodo;
use App\Models\Candidato;
use App\Models\Dependencia;
use App\Models\GrupoFuncion;
use App\Models\CargoCambioEstado;
use Yajra\DataTables\Services\DataTable;

class CargoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $user = auth()->user();
        $ids = $user->getEfectoresVisibles();

        $model = CargoCambioEstado::with([
            'cargo',
            'cargoTipoFormulario',
            'cargoTipoVisado',
            'cargoCambioEstadoObs',
            'periodoDesde',
            'periodoHasta',
            'cargo.efector',
            'cargo.servicio',
            'cargo.tipoFuncion',
            'cargo.tipoEspecialidad',
            'cargo.tipoNivel',
            'cargo.tipoAgrupamiento',
            'cargo.tipoCampania',
            'cargo.tipoCargo',
            'cargo.tipoCese',
            'cargo.tipoReferido',
            'cargo.titulo',
            'cargo.agentePropuesto.tipoSexo',
            'cargo.agentePropuesto',
            'cargo.cargoReemplazado',
            'cargo.cargoReemplazado.puesto',
            'cargo.cargoReemplazado.puesto.agente',
            // 'cargo.cargoReemplazado.tipoFuncion.tipofuncion',
            // 'cargo.cargoReemplazado.tipoNivel.tiponivel'
        ])->when(!$user->isRRHH(), function ($query) use ($ids) {
            return $query->whereHas('cargo', function ($q) use ($ids) {
                $q->whereIn('idefector', $ids);
            });
        });

        $data = datatables()
            ->eloquent($model)
            ->order(function ($query) {
                $query->orderBy('idcargo_cambio_estado', 'desc');
            })
            ->addColumn('actions', function ($model) {
                $buttons = "<button class=\"btn btn-xs\" onclick=\"vm.mostrarFormulario({$model->idcargo_cambio_estado})\"><i class=\"fas fa-eye\"></i></button>
                <button class=\"btn btn-xs\" onclick=\"vm.exportarPDFSolicitud({$model->idcargo_cambio_estado})\"><i class=\"fas fa-file-pdf\"></i></button>";
                $buttons .= $model->idcargo_tipo_visado === 1 ?
                "<button class=\"btn btn-xs\" onclick=\"vm.borrarFormulario({$model->idcargo_cambio_estado})\"><i class=\"fas fa-trash\"></i></button>" :
                '';
                return $buttons;
            })
            ->blacklist(['actions'])
            ->filterColumn('cargo_tipo_formulario.cargotipo_formulario', function ($query, $keyword) {
                if (strtoupper($keyword) === 'NC' || strtoupper($keyword) === 'CN') {
                    $query->whereIn('idcargo_tipo_formulario', [1, 2]);
                } elseif (strtoupper($keyword) === 'BAJA') {
                    $query->where('idcargo_tipo_formulario', '>', 2);
                } else {
                    $query->whereHas('cargoTipoFormulario', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(cargotipo_formulario) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                }
            })
            ->filterColumn('cargo.tipo_campania.tipocampania', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHas('tipoCampania', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(tipocampania) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->filterColumn('cargo.tipo_cargo_formateado', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHas('tipoCargo', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(tipocargo_corto) LIKE ?', '%' . strtoupper($keyword) . '%')
                            ->orWhereRaw('UPPER(tipocargo) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->filterColumn('cargo.agente_propuesto.documento', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q, $type) use ($keyword) {
                            $q->whereRaw('documento = ?', intval($keyword));
                        }
                    );
                });
            })
            ->filterColumn('cargo.agente_propuesto.apellido', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q, $type) use ($keyword) {
                            $q->whereRaw('UPPER(apellido) LIKE ?', '%' . strtoupper($keyword) . '%');
                        }
                    );
                });
            })
            ->filterColumn('cargo.agente_propuesto.nombre', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q, $type) use ($keyword) {
                            $q->whereRaw('UPPER(nombre) LIKE ?', '%' . strtoupper($keyword) . '%');
                        }
                    );
                });
            })
            ->filterColumn('cargo.tipo_funcion.tipofuncion', function ($query, $keyword) {
                $funciones = GrupoFuncion::funcionesGrupo($keyword);
                if (isset($funciones) && !empty($funciones)) {
                    $query->whereHas('cargo', function ($q) use ($keyword, $funciones) {
                        $q->whereIn('idtipo_funcion', $funciones);
                    });
                } else {
                    $query->whereHas('cargo', function ($qu) use ($keyword) {
                        $qu->whereHas('tipoFuncion', function ($q) use ($keyword) {
                            $q->whereRaw('UPPER(tipofuncion) LIKE ?', '%' . strtoupper($keyword) . '%');
                        });
                    });
                }
            })
            ->filterColumn('cargo.tipo_nivel.tiponivel', function ($query, $keyword) {
                $query->whereHas('cargo', function ($qu) use ($keyword) {
                    $qu->whereHas('tiponivel', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(tiponivel) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->filterColumn('cargo.efector.dependencia', function ($query, $keyword) {
                $efector = Dependencia::efector($keyword, true);
                if (isset($efector, $efector->iddependencia)) {
                    $descendencia = $efector->getIdsDescendencia();
                    $query->whereHas('cargo', function ($que) use ($descendencia) {
                        $que->whereIn('idefector', $descendencia)
                            ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                $q->whereIn('idefector', $descendencia);
                            });
                    });
                } else {
                    $efector = Dependencia::efector($keyword);
                    if (isset($efector, $efector->iddependencia)) {
                        $descendencia = $efector->getIdsDescendencia();
                        $query->whereHas('cargo', function ($que) use ($descendencia) {
                            $que->whereIn('idefector', $descendencia)
                                ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                    $q->whereIn('idefector', $descendencia);
                                });
                        });
                    }
                }
            })
//            ->filterColumn('cargo.servicio.dependencia', function ($query, $keyword) {
            ->filterColumn('cargo.servicio_calculado', function ($query, $keyword) {
                $query->whereHas('cargo', function ($qu) use ($keyword) {
                    $qu->whereHas('servicio', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(dependencia) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->filterColumn('cargo_tipo_visado.cargotipo_visado', function ($query, $keyword) {
                if (strtoupper($keyword) === 'APROBADOS' || strtoupper($keyword) === 'APROBADO') {
                    $query->whereIn('idcargo_tipo_visado', [8, 9]);
                } else {
                    $query->whereHas('cargoTipoVisado', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(cargotipo_visado) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                }
            })
            ->filterColumn('fecha_recibido_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_recibido IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_recibido',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = isset($keyword) ? date('Y-m-d', strtotime(str_replace('/', '-', $keyword))) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_recibido = ?', $fecha);
                    }
                }
            })
            ->filterColumn('fecha_envio_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_envio IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_envio',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = (bool)strtotime($keyword) ? (Carbon::parse($keyword)) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_envio = ?', $fecha->format('Y-m-d'));
                    }
                }
            })
            ->filterColumn('fecha_retorno_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_retorno IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_retorno',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = isset($keyword) ? date('Y-m-d', strtotime(str_replace('/', '-', $keyword))) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_retorno = ?', $fecha);
                    }
                }
            })
            ->filterColumn('periodo_desde_formateado', function ($query, $keyword) {
                if (preg_match_all('!\d+!', $keyword)) {
                    $periodo = Periodo::whereRaw('UPPER(periodo) LIKE ?', '%' . strtoupper($keyword) . '%')->first();
                } else {
                    $periodo = Periodo::whereRaw('UPPER(periodo) LIKE ?', '%' . strtoupper($keyword) . '%')
                    ->whereRaw('anio = ?', date('Y'))
                    ->first();
                }
                if (isset($periodo)) {
                    $fechaDesdeP = $periodo->fdesde;
                    $fechaHastaP = $periodo->fhasta;

                    $query->whereRaw(
                        '((fecha_desde >= ? AND fecha_desde <= ? OR fecha_desde <= ? AND fecha_desde <= ?) AND fecha_hasta >= ? AND fecha_hasta <= ?) OR
                        ((fecha_desde >= ? AND fecha_desde <= ? OR fecha_desde <= ? AND fecha_desde <= ?) AND fecha_hasta >= ? AND fecha_hasta >= ?) OR
                        ((fecha_desde >= ? AND fecha_desde <= ? OR fecha_desde <= ? AND fecha_desde <= ?) AND fecha_hasta IS NULL)',
                        [$fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP, $fechaDesdeP, $fechaHastaP]
                    );
                }
            })
            ->filterColumn('fecha_desde_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_desde IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_desde',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = (bool)strtotime($keyword) ? (Carbon::parse($keyword)) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_desde = ?', $fecha->format('Y-m-d'));
                    }
                }
            })
            ->filterColumn('periodo_hasta_formateado', function ($query, $keyword) {
                if (preg_match_all('!\d+!', $keyword)) {
                    $periodo = Periodo::whereRaw('UPPER(periodo) LIKE ?', '%' . strtoupper($keyword) . '%')->first();
                } else {
                    $periodo = Periodo::whereRaw('UPPER(periodo) LIKE ?', '%' . strtoupper($keyword) . '%')
                    ->whereRaw('anio = ?', date('Y'))
                    ->first();
                }
                if (isset($periodo)) {
                    $fechaDesdeP = Carbon::parse($periodo->fdesde)->format('Y-m-d');
                    $fechaHastaP = Carbon::parse($periodo->fhasta)->format('Y-m-d');

                    $query->whereRaw(
                        'fecha_hasta >= ? AND fecha_hasta <= ?',
                        [$fechaDesdeP, $fechaHastaP]
                    );
                }
            })
            ->filterColumn('fecha_hasta_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_hasta IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_hasta',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = (bool)strtotime($keyword) ? (Carbon::parse($keyword)) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_hasta = ?', $fecha->format('Y-m-d'));
                    }
                }
            })
            ->filterColumn('fecha_designacion_transitorio_formateada', function ($query, $keyword) {
                if (strtolower($keyword) === 'sin') {
                    $query->whereRaw('fecha_designacion_transitorio IS NULL');
                } elseif (strpos($keyword, '-') !== false) {
                    $fechas = explode('-', $keyword);
                    $fechaRecibidoDesde = Fecha::parse($fechas[0]);
                    $fechaRecibidoHasta = Fecha::parse($fechas[1], true);
                    if (isset($fechaRecibidoDesde, $fechaRecibidoHasta)) {
                        $query->whereBetween(
                            'fecha_designacion_transitorio',
                            [$fechaRecibidoDesde, $fechaRecibidoHasta]
                        );
                    }
                } else {
                    $fecha = isset($keyword) ? date('Y-m-d', strtotime(str_replace('/', '-', $keyword))) : null;
                    if (isset($fecha)) {
                        $query->whereRaw('fecha_designacion_transitorio = ?', $fecha);
                    }
                }
            })
            ->filterColumn('cargo.tipo_cargo.tipocargo', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHas('tipoCargo', function ($q) use ($keyword) {
                        $q->whereRaw(
                            'UPPER(tipocargo_corto) LIKE ? OR UPPER(tipocargo) LIKE ?',
                            ['%' . strtoupper($keyword) . '%', '%' . strtoupper($keyword) . '%']
                        );
                    });
                });
            })
            ->filterColumn('cargo.tipo_referido_formateado', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHas('tipoReferido', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(nombre) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->filterColumn('cargo_tipo_firma_formateado', function ($query, $keyword) {
                $query->whereHas('cargoTipoFirma', function ($q) use ($keyword) {
                    $q->whereRaw('UPPER(cargotipo_firma) LIKE ?', '%' . strtoupper($keyword) . '%');
                });
            })
            ->filterColumn('cargo.tipo_cese.tipocese', function ($query, $keyword) {
                $query->whereHas('cargo', function ($que) use ($keyword) {
                    $que->whereHas('tipoCese', function ($q) use ($keyword) {
                        $q->whereRaw('UPPER(tipocese) LIKE ?', '%' . strtoupper($keyword) . '%');
                    });
                });
            })
            ->orderColumn('cargo_tipo_formulario.cargotipo_formulario', function ($query, $order) {
                $query->whereHas('cargoTipoFormulario', function ($q) use ($order) {
                    $q->orderBy('cargotipo_formulario', $order);
                });
            })
            ->orderColumn('cargo.tipo_campania.tipocampania', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHas('tipoCampania', function ($q) use ($order) {
                        $q->orderBy('tipocampania', $order);
                    });
                });
            })
            ->orderColumn('cargo.tipo_cargo_formateado', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHas('tipoCargo', function ($q) use ($order) {
                        $q->orderBy('tipocargo', $order);
                    });
                });
            })
            ->orderColumn('cargo.tipo_referido_formateado', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHas('tipoReferido', function ($q) use ($order) {
                        $q->orderBy('tiporeferido', $order);
                    });
                });
            })
            ->orderColumn('cargo.agente_propuesto.documento', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q, $type) use ($order) {
                            $q->orderBy('documento', $order);
                        }
                    );
                });
            })
            ->orderColumn('cargo.agente_propuesto.apellido', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q) use ($order) {
                            $q->orderBy('apellido', $order);
                        }
                    );
                });
            })
            ->orderColumn('cargo.agente_propuesto.nombre', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHasMorph(
                        'agentePropuesto',
                        [Agente::class, Candidato::class],
                        function ($q) use ($order) {
                            $q->orderBy('nombre', $order);
                        }
                    );
                });
            })
            ->orderColumn('cargo.tipo_funcion.tipofuncion', function ($query, $order) {
                $query->whereHas('cargo', function ($qu) use ($order) {
                    $qu->whereHas('tipoFuncion', function ($q) use ($order) {
                        $q->orderBy('tipofuncion', $order);
                    });
                });
            })
            ->orderColumn('cargo.tipo_nivel.tiponivel', function ($query, $order) {
                $query->whereHas('cargo', function ($qu) use ($order) {
                    $qu->whereHas('tiponivel', function ($q) use ($order) {
                        $q->orderBy('tiponivel', $order);
                    });
                });
            })
            ->orderColumn('cargo.efector.dependencia', function ($query, $order) {
                $query->whereHas('cargo', function ($qu) use ($order) {
                    $qu->whereHas('efector', function ($q) use ($order) {
                        $q->orderBy('dependencia', $order);
                    });
                });
            })
 //           ->orderColumn('cargo.servicio.dependencia', function ($query, $order) {
            ->orderColumn('cargo.servicio_calculado', function ($query, $order) {
                $query->whereHas('cargo', function ($qu) use ($order) {
                    $qu->whereHas('servicio', function ($q) use ($order) {
                        $q->orderBy('dependencia', $order);
                    });
                });
            })
            ->orderColumn('cargo_tipo_visado.cargotipo_visado', function ($query, $order) {
                $query->whereHas('cargoTipoVisado', function ($q) use ($order) {
                    $q->orderBy('cargotipo_visado', $order);
                });
            })
            ->orderColumn('fecha_recibido_formateada', function ($query, $order) {
                $query->orderBy('fecha_recibido', $order);
            })
            ->orderColumn('fecha_envio_formateada', function ($query, $order) {
                $query->orderBy('fecha_envio', $order);
            })
            ->orderColumn('fecha_retorno_formateada', function ($query, $order) {
                $query->orderBy('fecha_retorno', $order);
            })
            ->orderColumn('periodo_desde_formateado', function ($query, $order) {
                $query->whereHas('periodoDesde', function ($q) use ($order) {
                    $q->orderBy('periodo', $order);
                });
            })
            ->orderColumn('fecha_desde_formateada', function ($query, $order) {
                $query->orderBy('fecha_desde', $order);
            })
            ->orderColumn('periodo_hasta_formateado', function ($query, $order) {
                $query->whereHas('periodoHasta', function ($q) use ($order) {
                    $q->orderBy('periodo', $order);
                });
            })
            ->orderColumn('fecha_hasta_formateada', function ($query, $order) {
                $query->orderBy('fecha_hasta', $order);
            })
            ->orderColumn('fecha_designacion_transitorio_formateada', function ($query, $order) {
                $query->orderBy('fecha_designacion_transitorio', $order);
            })
            ->orderColumn('cargo.tipo_referido_formateado', function ($query, $order) {
                $query->whereHas('cargo', function ($que) use ($order) {
                    $que->whereHas('tipoReferido', function ($q) use ($order) {
                        $q->orderBy('organismo', $order);
                    });
                });
            })
            ->orderColumn('cargo_tipo_firma_formateado', function ($query, $order) {
                $query->whereHas('cargoTipoFirma', function ($q) use ($order) {
                    $q->orderBy('cargotipo_firma', $order);
                });
            })
            ->orderColumn('cargo.tipo_cese.tipocese', function ($query, $order) {
                $query->whereHas('cargo', function ($qu) use ($order) {
                    $qu->whereHas('tipoCese', function ($q) use ($order) {
                        $q->orderBy('tipocese', $order);
                    });
                });
            })
            ->rawColumns(['actions'])
            ->setRowId(function ($model) {
                return $model->idcargo_cambio_estado;
            })
            ->setRowClass(function ($model) {
                switch ($model->idcargo_tipo_visado) {
                    case 1: return '';
                    case 2: return 'table-warning';
                    case 3: return 'table-danger';
                    case 4: return 'table-warning';
                    case 5: return 'table-danger';
                    case 6: return 'table-warning';
                    case 7: return 'table-danger';
                    case 8: return 'table-success';
                    case 9: return 'table-info';
                    default: return '';
                }
            });

        return $data;
    }
}
