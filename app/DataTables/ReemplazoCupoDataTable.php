<?php

namespace App\DataTables;

use App\Models\ReemplazoCupo;
use App\Models\ReemplazoCupoPuesto;
use Yajra\DataTables\Services\DataTable;

class ReemplazoCupoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query = null)
    {
        $model = ReemplazoCupoPuesto::with([
            'servicio',
            'puestos',
            'reemplazocupo',
            'reemplazocupo.periodo',
            'reemplazocupo.dependencia',
            'reemplazocupo.createdBy',
            'reemplazocupo.updatedBy',
            'createdBy',
            'updatedBy',
        ])->whereHas('reemplazocupo', function($query) {
            return $query->where('estado', '!=', false);
        })->where('reemplazo_cupo_puesto.estado', '!=', false);

        $data = datatables()
            ->eloquent($model)
            ->order(function ($query) {
                $query->orderBy('idreemplazo_cupo_puesto', 'desc');
                // $query->orderBy('idreemplazo_cupo', 'desc');
            })
            ->addColumn('actions', function ($model) {
                $buttons = "<button class=\"btn btn-xs text-primary\" onclick=\"vm.mostrarFormulario({$model->idreemplazo_cupo_puesto})\"><i class=\"fas fa-eye\"></i></button>";
                return $buttons;
            })
            ->addColumn('eventual', function ($model) {
               $checkInput = $model->eventual === true ? 'EVENTUAL' : '-';
                return $checkInput;
            })
            ->blacklist(['actions', 'checkbox'])
            ->filterColumn('reemplazocupo.dependencia', function ($query, $keyword) {
                $query->whereHas('dependencia', function ($que) use ($keyword) {
                    $que->whereRaw('UPPER(dependencia) LIKE ?', '%' . strtoupper($keyword) . '%');
                });
            })
            ->filterColumn('servicio.dependencia', function ($query, $keyword) {
                $query->whereHas('servicio', function ($que) use ($keyword) {
                    $que->whereRaw('UPPER(dependencia) LIKE ?', '%' . strtoupper($keyword) . '%');
                });
            })
            ->filterColumn('puestos.tipo_puesto', function ($query, $keyword) {
                $query->whereHas('tipo_puesto', function ($q) use ($keyword) {
                    $q->whereRaw('UPPER(tipo_puesto) LIKE ?', '%' . strtoupper($keyword) . '%');
                });
            })
            ->filterColumn('cupo_max', function ($query, $keyword) {
                $query->whereHas('cupo_max', function ($que) use ($keyword) {
                    $que->whereRaw('cupo_max LIKE ?', '%' . $keyword . '%');
                });
            })
            ->filterColumn('cupo_restantes', function ($query, $keyword) {
                $query->whereHas('cupo_restantes', function ($que) use ($keyword) {
                    $que->whereRaw('cupo_restantes LIKE ?', '%' . $keyword . '%');
                });
            })
            ->filterColumn('observaciones', function ($query, $keyword) {
                $query->whereHas('observaciones', function ($que) use ($keyword) {
                    $que->whereRaw('UPPER(observaciones) LIKE ?', '%' . strtoupper($keyword) . '%');
                });
            })
            ->filterColumn('reemplazocupo.periodo', function ($query, $keyword) {
                $query->whereHas('periodo', function ($que) use ($keyword) {
                    $que->whereRaw('periodo LIKE ?', '%' . $keyword . '%');
                });
            })
            ->filterColumn('eventual', function($query, $keyword) { 
                // $query->whereRaw("IF(eventual = true, 1, 0) like ?", ["%{$keyword}%"]); 
                if ($keyword == "-") {
                    return $query->where("eventual", "!=", true); 
                }

                if ($keyword != "") {
                    // $query->whereRaw("eventual = ?", 1); 
                    return $query->where("eventual", true); 
                }
                
            })
            // ->setRowClass(function ($model) {
            //     return $model->reemplazocupo->cupo_max > 0 ? 'text-success' : '';
            // })
            // ->addRowAttr("class", "text-success")
            // ->rawColumns(['actions', 'checkbox'])
            ->rawColumns(['eventual', 'actions'])
            ->only([
                "idreemplazo_cupo_puesto",
                "eventual",
                "totalagentesefector",
                "totalagentesservicio",
                "puesto",
                "puestos.tipo_puesto",
                "cupo",
                "old_idreemplazo_cupo",
                "servicio.iddependencia",
                "servicio.dependencia",
                "reemplazocupo.observaciones",
                "reemplazocupo.cupo_max",
                "reemplazocupo.usuario",
                "reemplazocupo.estado",
                "reemplazocupo.created_at",
                "reemplazocupo.created_by.nombreusuario",
                "reemplazocupo.updated_at",
                "reemplazocupo.updated_by.nombreusuario",
                "reemplazocupo.idreemplazo_cupo",
                "reemplazocupo.totalagentesefector",
                "reemplazocupo.totalagentesservicio",
                "puestos.tipo_puesto",
                "reemplazocupo.isredservicio",
                "reemplazocupo.isareaoperativa",
                "reemplazocupo.isareaprogramatica",
                "reemplazocupo.periodo.periodo",
                "reemplazocupo.dependencia.iddependencia",
                "reemplazocupo.dependencia.dependencia",
                "eventual",
                "created_at",
                "created_by.nombreusuario",
                "updated_by.nombreusuario",
                "updated_at",
                "actions"
            ])
            // ->make(true)
            ->setRowId(function ($model) {
                // return $model->idreemplazo_cupo_puesto;
                return $model->idreemplazo_cupo;
            });

        return $data;
    }
}
