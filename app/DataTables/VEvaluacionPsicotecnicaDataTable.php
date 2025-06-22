<?php

namespace App\DataTables;

use App\Models\RoleUser;
use App\Models\VEvaluacionPsicotecnica;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Services\DataTable;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTableAbstract;


class VEvaluacionPsicotecnicaDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, bool $export=false)
    {
        $user = auth()->user();
        $tiene_rol = false;
        $permisos = RoleUser::where([['user_id','=',$user['idusuario']],['role_id','=',20]])->first();
        if (isset($permisos))
            {$tiene_rol = true;};

        if ($tiene_rol)
           { $model = VEvaluacionPsicotecnica::where([['referido_1','=','Ministerio Salud Pública']]);}
           else
           {
           if (!$user->isRRHH())
              {
              $model = VEvaluacionPsicotecnica::where('nombre','<>','');
    //            orderBy('nombre');
              }
              else
              {
              $model = VEvaluacionPsicotecnica::where('nombre','<>','');
    //            orderBy('nombre');
              };
           };
        $data = datatables()
            ->eloquent($model)
            ->blacklist(['actions'])
            ->addColumn('tipo_ingreso_sin_dependencia', function (VEvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $tipoIngreso = $evaluacionPsicotecnica->tipoIngreso();
                if (isset($tipoIngreso['planta'])) {
                    return 'Planta: ' . $tipoIngreso['planta'];
                }
                return 'Reemplazos: ' . $tipoIngreso['reemplazos']['count'] . ' Libres: ' . $tipoIngreso['libres']['count'] . ' Guardias: ' . $tipoIngreso['guardias']['count'];
            })
            ->addColumn('lugar', function (VEvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $tipoIngreso = $evaluacionPsicotecnica->tipoIngreso();
                if (isset($tipoIngreso['planta'])) {
                    return '';
                }
                return 'Reemplazos: ' . $tipoIngreso['reemplazos']['dependencias'] . ' Libres: ' . $tipoIngreso['libres']['dependencias'] . ' Guardias: ' . $tipoIngreso['guardias']['dependencias'];
            })
            ->addColumn('ingreso', function (VEvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return $evaluacionPsicotecnica->ingreso();
            })
            ->filterColumn('departamento', function ($query, $keyword) {
                $query->whereRaw("UPPER(departamento) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('nombre', function ($query, $keyword) {
                $query->whereRaw("UPPER(nombre) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('documento', function ($query, $keyword) {
                $query->whereRaw("documento = ?", intval($keyword));
            })
            ->filterColumn('tipofuncion', function ($query, $keyword) {
                $query->whereRaw("UPPER(tipofuncion) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('titulo', function ($query, $keyword) {
                $query->whereRaw("UPPER(titulo) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('tiponivel', function ($query, $keyword) {
                $query->whereRaw("UPPER(tiponivel) LIKE ?", "%".strtoupper($keyword)."%");
            })
            ->filterColumn('tiporecomendacion', function ($query, $keyword) {
                $query->whereRaw("UPPER(tiporecomendacion) LIKE ?", "%".strtoupper($keyword)."%");
            })
            
            ->orderColumn('departamento', function ($query, $order) {
                $query->orderBy("departamento", $order);
            })
            ->orderColumn('nombre', function ($query, $order) {
                $query->orderBy("nombre", $order);
            })
            ->orderColumn('tipofuncion', function ($query, $order) {
                $query->orderBy("tipofuncion", $order);
            })
            ->orderColumn('titulo', function ($query, $order) {
                $query->orderBy("titulo", $order);
            })
            ->orderColumn('tiponivel', function ($query, $order) {
                $query->orderBy("tiponivel", $order);
            })
            ->orderColumn('tiporecomendacion', function ($query, $order) {
                $query->orderBy("tiporecomendacion", $order);
            })
            ->rawColumns(['observaciones','tipo_ingreso'])
            ->setRowId(function ($model) {
                return $model->idevaluacion_psicotecnica;
            });
        if (!$export)
           {$data = $this->noExport($data);};
        return $data;
    }
    

    /**
     * @param QueryDataTable $datatable
     * @return QueryDataTable
     */
    public function noExport(QueryDataTable $datatable): QueryDataTable
    {
        return $datatable
            ->addColumn('observaciones', function (VEvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $inicio = $this->cortarFrase($evaluacionPsicotecnica->observaciones);
                $fin = substr($evaluacionPsicotecnica->observaciones, strlen($inicio), strlen($evaluacionPsicotecnica->observaciones) - strlen($inicio));
                return '<div class="accordion" id="observacionesCollapsable' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '">
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '" aria-expanded="false" aria-controls="collapse' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '">
                                    ' . ($inicio) . '
                                    </button>
                                </div>
                                <div id="collapse' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '" class="collapse" aria-labelledby="headingTwo" data-parent="#observacionesCollapsable' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '">
                                    <div class="card-body text-white bg-secondary">
                                        <p class="card-text">' . ($fin) . '</div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            })
            ->addColumn('tipo_ingreso', function (VEvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $tipoIngreso = $evaluacionPsicotecnica->tipoIngreso();
                if (isset($tipoIngreso['planta'])) {
                    return 'Planta: ' . $tipoIngreso['planta'];
                }
                return '<div style="width: 200px">
                            <p>Reemplazos: ' . $tipoIngreso['reemplazos']['count'] . ' Libres: ' . $tipoIngreso['libres']['count'] . ' Guardias: ' . $tipoIngreso['guardias']['count'] . ' Cargos: ' . $tipoIngreso['coberturas']['count'] . '</p>
                            <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseTipoIngreso' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '" aria-expanded="false" aria-controls="collapseTipoIngreso' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '">
                                VER MÁS
                            </button>
                            <div class="collapse" id="collapseTipoIngreso' . $evaluacionPsicotecnica->idevaluacion_psicotecnica . '">
                                <div class="card text-white bg-secondary">
                                    <div class="card-body">
                                    <p class="card-text">Reemplazos: ' . ($tipoIngreso['reemplazos']['dependencias'] . ' Libres: ' . $tipoIngreso['libres']['dependencias'] . ' Guardias: ' . $tipoIngreso['guardias']['dependencias'] . ' Cargos: ' . $tipoIngreso['coberturas']['dependencias']) . '</p>
                                    </div>
                                 </div>
                            </div>
                        </div>';
            });
    }
    
    public function cortarFrase($frase, $maxPalabras = 3, $noTerminales = ["de"])
    {
        $palabras = explode(" ", $frase);
        $numPalabras = count($palabras);
        if ($numPalabras > $maxPalabras) {
            $offset = $maxPalabras - 1;
            while (in_array($palabras[$offset], $noTerminales) && $offset < $numPalabras) {
                $offset++;
            }
            return implode(" ", array_slice($palabras, 0, $offset + 1));
        }
        return $frase;
    }
    
}
