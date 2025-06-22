<?php

namespace App\DataTables;

use App\Models\VAgentePlanta;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class VAgentesPlantaDataTable extends DataTable
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
            $model = VAgentePlanta::
            whereIn('iddependencia', $user->getDependenciasVisibles());
        } else {
            $model = VAgentePlanta::
                    where('apellido','<>','');;
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
            ->filterColumn('documento', function ($query, $keyword) {
                $query->whereRaw("documento = ?", intval($keyword));
            })
            ->filterColumn('apellido', function ($query, $keyword) {
                $query->whereRaw("UPPER(apellido) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('nombre', function ($query, $keyword) {
                $query->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('grupo_funcion', function ($query, $keyword) {
                $query->whereRaw("UPPER(grupo_funcion) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('funcion', function ($query, $keyword) {
                $query->whereRaw("UPPER(funcion) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('planta', function ($query, $keyword) {
                $query->whereRaw("UPPER(planta) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('area_operativa', function ($query, $keyword) {
                $query->whereRaw("UPPER(area_operativa) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('efector', function ($query, $keyword) {
                $query->whereRaw("UPPER(efector) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('servicio', function ($query, $keyword) {
                $query->whereRaw("UPPER(servicio) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('hora_desde', function ($query, $keyword) {
                $query->whereRaw("hora_desde = ?", date('H:i:s', strtotime($keyword)));
            })
            ->filterColumn('hora_hasta', function ($query, $keyword) {
                $query->whereRaw("hora_hasta = ?", date('H:i:s', strtotime($keyword)));
            })
            ->filterColumn('tipodia', function ($query, $keyword) {
                $query->whereRaw("UPPER(tipodia) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('cantidad_horas', function ($query, $keyword) {
                $query->whereRaw("cantidad_horas = ?", intval($keyword));
            })
            ->filterColumn('horario_nuevo', function ($query, $keyword) {
                $query->whereRaw("UPPER(horario_nuevo) LIKE ?", "%".strtoupper($keyword)."%");
            })
            
            ->orderColumn('documento', function ($query, $order) {
                $query->orderBy("documento", $order);
            })
            ->orderColumn('apellido', function ($query, $order) {
                $query->orderBy("apellido", $order);
            })
            ->orderColumn('nombre', function ($query, $order) {
                $query->orderBy("nombre", $order);
            })
            ->orderColumn('grupo_funcion', function ($query, $order) {
                $query->orderBy("grupo_funcion", $order);
            })
            ->orderColumn('funcion', function ($query, $order) {
                $query->orderBy("funcion", $order);
            })
            ->orderColumn('planta', function ($query, $order) {
                $query->orderBy("planta", $order);
            })
            ->orderColumn('area_operativa', function ($query, $order) {
                $query->orderBy("area_operativa", $order);
            })
            ->orderColumn('efector', function ($query, $order) {
                $query->orderBy("efector", $order);
            })
            ->orderColumn('servicio', function ($query, $order) {
                $query->orderBy("servicio", $order);
            })
            ->orderColumn('hora_desde', function ($query, $order) {
                $query->orderBy("hora_desde", $order);
            })
            ->orderColumn('hora_hasta', function ($query, $order) {
                $query->orderBy("hora_hasta", $order);
            })
            ->orderColumn('tipodia', function ($query, $order) {
                $query->orderBy("tipodia", $order);
            })
            ->orderColumn('cantidad_horas', function ($query, $order) {
                $query->orderBy("cantidad_horas", $order);
            })
            ->orderColumn('horario_nuevo', function ($query, $order) {
                $query->orderBy("horario_nuevo", $order);
            })
            
            ->rawColumns(['actions'])
            ->setRowId(function ($model) {
                return $model->idpuesto;
            });
        return $data;
    }
}
