<?php

namespace App\DataTables;

use App\Models\Agente;
use App\Models\Puesto;
use App\Models\Dependencia;
use App\Models\GrupoFuncion;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class AgentesPlantaDataTable extends DataTable
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
        if (!$user->isRRHH()) {
            $model = Puesto::with([
                'agente',
                'horarios',
                'horarios.tipoDia',
                'horario_historico.tipoHorario',
                'horario_historico',
                'dependencia',
                'tipoFuncion',
                'tipoPlanta',
            ])
            ->whereNull('fhasta')
            ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
            ->whereIn('iddependencia', $user->getDependenciasVisibles())
            ->orderBy(
                Agente::select('apellido')
                    ->whereColumn('agente.idagente', 'puesto.idagente')
            )->orderBy(
                Agente::select('nombre')
                    ->whereColumn('agente.idagente', 'puesto.idagente')
            );
        } else {
            $model = Puesto::with([
                'agente',
                'horarios',
                'horarios.tipoDia',
                'horario_historico.tipoHorario',
                'horario_historico',
                'dependencia',
                'tipoFuncion',
                'tipoPlanta',
            ])
            ->whereNull('fhasta')
            ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
            ->orderBy(
                Agente::select('apellido')
                    ->whereColumn('agente.idagente', 'puesto.idagente')
            )->orderBy(
                Agente::select('nombre')
                    ->whereColumn('agente.idagente', 'puesto.idagente')
            );
        }

        $data = datatables()
            ->eloquent($model)
            ->addColumn('actions', function ($model) {
                $buttons = "<button class=\"btn btn-xs\" onclick=\"vm.mostrarHorario({$model->idpuesto})\"><i class=\"fas fa-clock\"></i></button>
                <a class=\"btn btn-xs\" href=\"index.php?r=agente/view&id={$model->idagente}\"><i class=\"fas fa-eye\"></i></a>
                <a class=\"btn btn-xs\" href=\"index.php?r=agente/update&id={$model->idagente}\"><i class=\"fas fa-edit\"></i></a>
                <a class=\"btn btn-xs\" href=\"index.php?r=puesto/updatepuesto&id={$model->idpuesto}\"><i class=\"fas fa-user-edit\"></i></a>";
                return $buttons;
            })
            ->blacklist(['actions'])
            ->filterColumn('agente.apellido', function ($query, $keyword) {
                $query->whereHas('agente', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(apellido) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente.nombre', function ($query, $keyword) {
                $query->whereHas('agente', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('agente.documento', function ($query, $keyword) {
                $query->whereHas('agente', function ($q) use ($keyword) {
                    $q->whereRaw("documento = ?", intval($keyword));
                });
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
            ->filterColumn('tipo_planta.tipoplanta', function ($query, $keyword) {
                $query->whereHas('tipoPlanta', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(tipoplanta) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('efector', function ($query, $keyword) {
                $efector = Dependencia::nombreDep($keyword);
                $descendencia = array();
                if ($efector->count()>0)
                   {
                   foreach ($efector as $reg)
                           {
                           $descendencia = array_merge($descendencia, array($reg->iddependencia));
                           $descendencia = array_merge($descendencia, $reg->getIdsDescendencia());
                           };
                   }
                   else
                   {$descendencia = array_merge($descendencia, array(1999991));};
                   $query->whereIn("iddependencia", $descendencia)
                         ->orWhereHas('horarios', function ($q) use ($descendencia) {
                             $q->whereIn("iddependencia", $descendencia);
                            });
            })
 /*
            ->filterColumn('efector', function ($query, $keyword) {
                $efector = Dependencia::efector($keyword, true);
                if (isset($efector, $efector->iddependencia)) {
                    $descendencia = $efector->getIdsDescendencia();
                    $query->whereIn("iddependencia", $descendencia)
                            ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                $q->whereIn("iddependencia", $descendencia);
                            });
                } else {
                    $efector = Dependencia::efector($keyword);
                    if (isset($efector, $efector->iddependencia)) {
                        $descendencia = $efector->getIdsDescendencia();
                        $query->whereIn("iddependencia", $descendencia)
                            ->orWhereHas('horarios', function ($q) use ($descendencia) {
                                $q->whereIn("iddependencia", $descendencia);
                            });
                    }
                }
            })
*/
            ->filterColumn('dependencia.dependencia', function ($query, $keyword) {
                $query->whereHas('dependencia', function ($q) use ($keyword) {
                    $q->whereRaw("UPPER(dependencia) LIKE ?", "%".strtoupper($keyword)."%");
                });
            })
            ->filterColumn('hora_desde_formateada', function ($query, $keyword) {
                $query->whereHas('horarios', function ($q) use ($keyword) {
                    $q->whereRaw("hora_desde = ?", date('H:i:s', strtotime($keyword)));
                })->orWhereHas('horario_historico', function ($q) use ($keyword) {
                    $q->whereRaw("hora_desde = ?", date('H:i:s', strtotime($keyword)));
                });
            })
            ->filterColumn('hora_hasta_formateada', function ($query, $keyword) {
                $query->whereHas('horarios', function ($q) use ($keyword) {
                    $q->whereRaw("hora_hasta = ?", date('H:i:s', strtotime($keyword)));
                })->orWhereHas('horario_historico', function ($q) use ($keyword) {
                    $q->whereRaw("hora_hasta = ?", date('H:i:s', strtotime($keyword)));
                });
            })
            ->filterColumn('tipo_dia_formateado', function ($query, $keyword) {
                $query->whereHas('horarios', function ($que) use ($keyword) {
                    $que->whereHas('tipoDia', function ($q) use ($keyword) {
                        $q->whereRaw("UPPER(tipodia) LIKE ?", "%".strtoupper($keyword)."%");
                    });
                })->orWhereHas('horario_historico', function ($que) use ($keyword) {
                    $que->whereHas('tipoHorario', function ($q) use ($keyword) {
                        $q->whereRaw("UPPER(tipohorario) LIKE ?", "%".strtoupper($keyword)."%");
                    });
                });
            })
            ->filterColumn('cantidad_horas_formateado', function ($query, $keyword) {
                $query->whereHas('horarios', function ($q) use ($keyword) {
                    $q->whereRaw("cantidad_horas = ?", intval($keyword));
                });
            })
            ->orderColumn('agente.apellido', function ($query, $order) {
                $query->whereHas('agente', function ($q) use ($order) {
                    $q->orderBy("apellido", $order);
                });
            })
            ->orderColumn('agente.nombre', function ($query, $order) {
                $query->whereHas('agente', function ($q) use ($order) {
                    $q->orderBy("nombre", $order);
                });
            })
            ->orderColumn('tipo_funcion.tipofuncion', function ($query, $order) {
                $query->whereHas('tipoFuncion', function ($q) use ($order) {
                    $q->orderBy("tipofuncion", $order);
                });
            })
            ->orderColumn('tipo_planta.tipoplanta', function ($query, $order) {
                $query->whereHas('tipoPlanta', function ($q) use ($order) {
                    $q->orderBy("tipoplanta", $order);
                });
            })
            ->orderColumn('efector', function ($query, $order) {
                $query->whereHas('dependencia', function ($q) use ($order) {
                    $q->orderBy("dependencia", $order);
                });
            })
            ->orderColumn('dependencia.dependencia', function ($query, $order) {
                $query->whereHas('dependencia', function ($q) use ($order) {
                    $q->orderBy("dependencia", $order);
                });
            })
            ->orderColumn('horarios.tipo_dia.tipodia', function ($query, $order) {
                $query->whereHas('horarios', function ($qu) use ($order) {
                    $qu->whereHas('tipoDia', function ($q) use ($order) {
                        $q->orderBy("tipodia", $order);
                    });
                })
                ->orWhereHas('horario_historico', function ($qu) use ($order) {
                    $qu->whereHas('tipoHorario', function ($q) use ($order) {
                        $q->orderBy("tipohorario", $order);
                    });
                });
            })
            ->rawColumns(['actions'])
            ->setRowId(function ($model) {
                return $model->idpuesto;
            })
            ->setRowClass(function ($model) {
                if ($model->horarios()->count() < 1) {
                    return 'table-secondary';
                }
            });
        return $data;
    }
}
