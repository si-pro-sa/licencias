<?php

namespace App\DataTables;

use App\App\Models\CandidatoSinPsico;
use App\Models\Agente;
use App\Models\Candidato;
use App\Models\RecomendacionCandidato;
use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Services\DataTable;

class CandidatoSinPsicoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query, array $candidatos)
    {
        $model = RecomendacionCandidato::with([
            'candidato',
            'candidato.domicilioWithDefault',
            'candidato.domicilioWithDefault.localidadWithDefault',
            'candidato.domicilioWithDefault.departamentoWithDefault',
            'formacionWithDefault',
            'referidoInternoWithDefault',
            'referidoPoliticoWithDefault',
            'especialidadWithDefault',
            'nivelWithDefault'
        ])->whereIn('idrecomendacion_candidato', $candidatos);

        if(User::tienePermiso('Referido-consolidadoReferido2') && !User::isRole("gerencia")){
            $model->whereHas('referidoInternoWithDefault', function ($q) {
                $q->whereRaw("idtipo_referido = 4");
            });
        }

        $datatable = datatables()
            ->eloquent($model)
            ->orderByNullsLast()
            ->setRowId(function ($model) {
                return $model->idrecomendacion_candidato;
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
        $datatable->filterColumn('candidato_column.apellido', function ($query, $keyword) {
            $query->whereHasMorph('candidato',
                [Agente::class, Candidato::class],
                function ($q, $type) use ($keyword) {
                    $q->whereRaw("LOWER(apellido) LIKE ?", "%" . strtolower($keyword) . "%");
                });
        })
            ->filterColumn('candidato_column.nombre', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
                    });
            })
            ->filterColumn('candidato_column.documento', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(documento AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    });
            })
            ->filterColumn('candidato_column.telefono', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(telefono AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    });
            })
            ->filterColumn('candidato_column.celular', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(celular AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    });
            })
            ->filterColumn('candidato_column.email', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(email AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    });
            })
            ->filterColumn('localidad_column', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereHas('localidadWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(localidad) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    });
            })
            ->filterColumn('departamento_column', function ($query, $keyword) {
                $query->whereHasMorph('candidato',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereHas('departamentoWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(departamento) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    });
            })
            ->filterColumn('formacion_column.titulo', function ($query, $keyword) {
                $query->whereHas('formacionWithDefault', function ($que) use ($keyword) {
                    $que->whereRaw("LOWER(titulo) LIKE ?", "%" . strtolower($keyword) . "%");
                });
            })
            ->filterColumn('especialidad.tipofuncion', function ($query, $keyword) {
                $query->whereHas('especialidadWithDefault', function ($que) use ($keyword) {
                    $que->whereRaw("LOWER(tipofuncion) LIKE ?", "%" . strtolower($keyword) . "%");
                });
            })
            ->filterColumn('nivel.tiponivel', function ($query, $keyword) {
                $query->whereHas('nivelWithDefault', function ($que) use ($keyword) {
                    $que->whereRaw("LOWER(tiponivel) LIKE ?", "%" . strtolower($keyword) . "%");
                });
            })
            ->filterColumn('referido_interno.nombre', function ($query, $keyword) {
                $query->whereHas('referidoInternoWithDefault', function ($que) use ($keyword) {
                    $que->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
                });
            })
            ->filterColumn('referido_politico.nombre', function ($query, $keyword) {
                $query->whereHas('referidoPoliticoWithDefault', function ($que) use ($keyword) {
                    $que->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
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
        $datatable
            ->orderColumn('candidato_column.apellido', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.apellido,candidato.apellido)'), $order);
            })
            ->orderColumn('candidato_column.nombre', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.nombre,candidato.nombre)'), $order);
            })
            ->orderColumn('candidato_column.documento', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.documento,candidato.documento)'), $order);
            })
            ->orderColumn('candidato_column.telefono', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.telefono,candidato.telefono)'), $order);
            })
            ->orderColumn('candidato_column.celular', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.celular,candidato.celular)'), $order);
            })
            ->orderColumn('candidato_column.email', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.email,candidato.email)'), $order);
            })
            ->orderColumn('departamento_column', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinDomicilio($query);
                $query = $this->leftJoinDepartamento($query);
                $query->orderBy(DB::raw('COALESCE(depagente.departamento,depcandidato.departamento)'), $order);
            })
            ->orderColumn('localidad_column', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinDomicilio($query);
                $query = $this->leftJoinDLocalidad($query);
                $query->orderBy(DB::raw('COALESCE(locagente.localidad,loccandidato.localidad)'), $order);
            })
            ->orderColumn('formacion_column.titulo', function ($query, $order) {
                $query = $this->leftJoinTitulo($query);
                $query->orderBy(DB::raw('COALESCE(titcandidato.titulo)'), $order);
            })
            ->orderColumn('especialidad.tipofuncion', function ($query, $order) {
                $query = $this->leftJoinTipoFuncion($query);
                $query->orderBy(DB::raw('COALESCE(tfcandidato.tipofuncion)'), $order);
            })
            ->orderColumn('nivel.tiponivel', function ($query, $order) {
                $query = $this->leftJoinTipoNivel($query);
                $query->orderBy(DB::raw('COALESCE(tncandidato.tiponivel)'), $order);
            })
            ->orderColumn('referido_interno.nombre', function ($query, $order) {
                $query = $this->leftJoinReferido($query, true);
                $query->orderBy(DB::raw('COALESCE(refecandidato.nombre)'), $order);
            })
            ->orderColumn('referido_politico.nombre', function ($query, $order) {
                $query = $this->leftJoinReferido($query, false);
                $query->orderBy(DB::raw('COALESCE(refecandidato.nombre)'), $order);
            });
        return $datatable;
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinCandidato($query)
    {
        return $query
            ->leftJoin('agente', function ($join) {
                $join->on('recomendacion_candidato.candidato_id', '=', 'agente.idagente');
                $join->on('recomendacion_candidato.candidato_type', '=', DB::raw("'App\Models\Agente'"));
            })
            ->leftJoin('candidato', function ($join) {
                $join->on('recomendacion_candidato.candidato_id', '=', 'candidato.idcandidato');
                $join->on('recomendacion_candidato.candidato_type', '=', DB::raw("'App\Models\Candidato'"));
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinDomicilio($query)
    {
        return $query
            ->leftJoin('domicilio as domagente', function ($join) {
                $join->on('domagente.iddomicilio', '=', 'agente.iddomicilio');
            })->leftJoin('domicilio as domcandidato', function ($join) {
                $join->on('domcandidato.iddomicilio', '=', 'candidato.iddomicilio');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinDepartamento($query)
    {
        return $query
            ->leftJoin('departamento as depagente', function ($join) {
                $join->on('depagente.iddepartamento', '=', 'domagente.iddepartamento');
            })->leftJoin('departamento as depcandidato', function ($join) {
                $join->on('depcandidato.iddepartamento', '=', 'domcandidato.iddepartamento');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinDLocalidad($query)
    {
        return $query
            ->leftJoin('localidad as locagente', function ($join) {
                $join->on('locagente.idlocalidad', '=', 'domagente.idlocalidad');
            })->leftJoin('localidad as loccandidato', function ($join) {
                $join->on('loccandidato.idlocalidad', '=', 'domcandidato.idlocalidad');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTipoFuncion($query)
    {
        return $query
            ->leftJoin('tipo_funcion as tfcandidato', function ($join) {
                $join->on('tfcandidato.idtipo_funcion', '=', 'recomendacion_candidato.idtipo_funcion');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTitulo($query)
    {
        return $query
            ->leftJoin('titulo as titcandidato', function ($join) {
                $join->on('titcandidato.idtitulo', '=', 'recomendacion_candidato.idtitulo');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTipoNivel($query)
    {
        return $query
            ->leftJoin('tipo_nivel as tncandidato', function ($join) {
                $join->on('tncandidato.idtipo_nivel', '=', 'recomendacion_candidato.idtipo_nivel');
            });
    }

    /**
     * @param $query Builder
     * @param bool $referido
     * @return Builder
     */
    private function leftJoinReferido($query, bool $referido)
    {
        if ($referido) {
            return $query
                ->leftJoin('tipo_referido as refecandidato', function ($join) {
                    $join->on('refecandidato.idtipo_referido', '=', 'recomendacion_candidato.idtipo_referido_interno');
                });
        } else {
            return $query
                ->leftJoin('tipo_referido as refecandidato', function ($join) {
                    $join->on('refecandidato.idtipo_referido', '=', 'recomendacion_candidato.idtipo_referido_politico');
                });
        }
    }
}
