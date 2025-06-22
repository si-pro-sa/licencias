<?php

namespace App\DataTables;

use App\App\Models\CandidatoSinPsico;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\EvaluacionPsicotecnica;
use App\Models\PsicoEvaluador;
use App\Models\RecomendacionCandidato;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Services\DataTable;

class PsicoevaluadoresDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query)
    {
        DB::enableQueryLog();
        $model = PsicoEvaluador::with('agente')->withTrashed();

        $datatable = datatables()
            ->eloquent($model)
            ->orderByNullsLast()
            ->setRowId(function ($model) {
                return $model->idpsicoevaluador;
            });
        $datatable
            ->addColumn('actions', function (PsicoEvaluador $psicoEvaluador) {
                if ($psicoEvaluador->deleted_at == null) {
                    return '
                            <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="left" title="Editar psicoevaluador"
                            onclick="vm.setPsicoevaluadorUpdate(' . $psicoEvaluador->idpsicoevaluador .',\''. $psicoEvaluador->firma.'\',\''.$psicoEvaluador->firma_imagen.'\',\''.$psicoEvaluador->email.'\',\''.$psicoEvaluador->matricula.'\','.$psicoEvaluador->agente->documento. ')">
                            <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Eliminar Psicoevaluador"
                            onclick="vm.setPsicoevaluadorDelete(' . $psicoEvaluador->idpsicoevaluador .')">
                            <i class="fas fa-trash-alt"></i>
                            </button>
                            ';
                } else {
                    return '<button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Dar de alta psicoevaluador"
                    onclick="vm.setPsicoevaluadorEnable(' . $psicoEvaluador->idpsicoevaluador .')">
                            <i class="fas fa-arrow-up"></i>
                            </button>
                            ';
                }
            })
            ->addColumn('imagen', function (PsicoEvaluador $psicoEvaluador){
                if ($psicoEvaluador->deleted_at == null){
                    if ($psicoEvaluador->firma_imagen != null){
                        return '
                <button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="Modificar archivo" 
                onclick="vm.setPsicoevaluadorFileUpdate(' . $psicoEvaluador->idpsicoevaluador .',\''. $psicoEvaluador->firma.'\',\''.$psicoEvaluador->firma_imagen.'\',\''.$psicoEvaluador->email.'\',\''.$psicoEvaluador->matricula.'\','.$psicoEvaluador->agente->documento. ')">
                <i class="far fa-edit"></i>
                </button>
                <button type="button" class="btn btn-secondary" data-toggle="tooltip" data-placement="right" title="Ver imagen" 
                onclick="vm.setPsicoevaluadorFileView(' . $psicoEvaluador->idpsicoevaluador .',\''. $psicoEvaluador->firma.'\',\''.$psicoEvaluador->firma_imagen.'\',\''.$psicoEvaluador->email.'\',\''.$psicoEvaluador->matricula.'\','.$psicoEvaluador->agente->documento. ')">
                <i class="fas fa-search-plus"></i>
                </button>
                <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Ver imagen" 
                onclick="vm.setPsicoevaluadorDeleteImage(' . $psicoEvaluador->idpsicoevaluador .')">
                <i class="fas fa-trash-alt"></i>
                </button>
                ';
                    }else{
                        return '
                <button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="Subir archivo" 
                onclick="vm.setPsicoevaluadorFileUpload(' . $psicoEvaluador->idpsicoevaluador .',\''. $psicoEvaluador->firma.'\',\''.$psicoEvaluador->firma_imagen.'\',\''.$psicoEvaluador->email.'\',\''.$psicoEvaluador->matricula.'\','.$psicoEvaluador->agente->documento. ')">
                <i class="fas fa-upload"></i>
                </button>
                ';
                    }
                }
            })
            ->rawColumns(['actions','imagen'])
            ->setRowClass(function (PsicoEvaluador $psicoEvaluador) {
                return $psicoEvaluador->deleted_at != null ? 'bg-danger' : '';
            });
        $datatable = $this->filter($datatable);
        $datatable = $this->order($datatable);
        return $datatable;
    }

    /**
     * @param QueryDataTable $datatable
     * @return QueryDataTable
     */
    private function filter(QueryDataTable $datatable): QueryDataTable
    {
        $datatable->filterColumn('documento_column', function ($query, $keyword) {
            $query->whereHas('agente', function ($que) use ($keyword) {
                $que->whereRaw("CAST(documento AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
            });
        });
        return $datatable;
    }

    /**
     * @param QueryDataTable $datatable
     * @return QueryDataTable
     */
    private function order(QueryDataTable $datatable): QueryDataTable
    {
        $datatable->orderColumn('documento_column', function ($query, $order) {
            $query = $this->leftJoinAgente($query);
            $query->orderBy(DB::raw('COALESCE(agente.documento)'), $order);
        });
        return $datatable;
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinAgente($query)
    {
        return $query->leftJoin('agente', function ($join) {
            $join->on('psicoevaluador.idagente', '=', 'agente.idagente');
        });
    }
}
