<?php

namespace App\DataTables;

use App\Models\Agente;
use App\Models\Candidato;
use App\Models\EvaluacionPsicotecnica;
use App\Models\Periodo;
use App\Models\RecomendacionCandidato;
use App\Models\TipoPlanta;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\QueryDataTable;
use Yajra\DataTables\Services\DataTable;

class PsicotecnicosDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @param bool $export
     * @return DataTableAbstract
     */
    public function dataTable($query, bool $export)
    {
        DB::enableQueryLog();
        $model = EvaluacionPsicotecnica::with([
            'tipoEntrevista',
            'evaluacion_psicotecnica',
            'evaluacion_psicotecnica.domicilioWithDefault' => function ($query) {
                $query->select('iddomicilio', 'calle', 'codigo_postal', 'idlocalidad', 'iddepartamento', 'numero');
            },
            'evaluacion_psicotecnica.domicilioWithDefault.localidadWithDefault' => function ($query) {
                $query->select('idlocalidad', 'localidad');
            },
            'evaluacion_psicotecnica.domicilioWithDefault.departamentoWithDefault' => function ($query) {
                $query->select('iddepartamento', 'departamento');
            },
            'tipoRecomendacion',
            'psicoevaluadorWithTrashed' => function ($query) {
                $query->select('idpsicoevaluador', 'firma');
            },
            'grupoWithDefault',

        ])
            ->select(DB::raw('aspectos_cognitivos+aspectos_psicoafectivos+desempeno+motivacion+atencion_usuario+trabajo_en_equipo+tolerancia_presion+organizacion+adaptabilidad as puntaje,*'));
        if (User::tienePermiso('Referido-consolidadoReferido2') && !User::isRole("gerencia")) {
            $model->whereHasMorph(
                'evaluacion_psicotecnica',
                [Agente::class, Candidato::class],
                function ($quer, $type) {
                    $quer->whereHas('recomendacion', function ($que) {
                        $que->whereHas('referidoInternoWithDefault', function ($q) {
                            $q->whereRaw("idtipo_referido = 4");
                        });
                    });
                }
            );
        }

        $datatable = datatables()
            ->eloquent($model)
            ->orderByNullsLast()
            ->setRowId(function ($model) {
                return $model->idevaluacion_psicotecnica;
            });
        $datatable
            ->addColumn('tipo_ingreso_sin_dependencia', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $tipoIngreso = $evaluacionPsicotecnica->tipoIngreso();
                if (isset($tipoIngreso['planta'])) {
                    return 'Planta: ' . $tipoIngreso['planta'];
                }
                return 'Reemplazos: ' . $tipoIngreso['reemplazos']['count'] . ' Libres: ' . $tipoIngreso['libres']['count'] . ' Guardias: ' . $tipoIngreso['guardias']['count'];
            })
            ->addColumn('lugar', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $tipoIngreso = $evaluacionPsicotecnica->tipoIngreso();
                if (isset($tipoIngreso['planta'])) {
                    return '';
                }
                return 'Reemplazos: ' . $tipoIngreso['reemplazos']['dependencias'] . ' Libres: ' . $tipoIngreso['libres']['dependencias'] . ' Guardias: ' . $tipoIngreso['guardias']['dependencias'];
            })
            ->addColumn('ingreso', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return $evaluacionPsicotecnica->ingreso();
            })
            ->addColumn('puntaje', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return $evaluacionPsicotecnica->puntaje;
            })
            ->addColumn('fecha_evaluacion', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return Carbon::parse($evaluacionPsicotecnica->fecha_evaluacion)->format('d/m/Y');
            })
            ->addColumn('created_at', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return Carbon::parse($evaluacionPsicotecnica->created_at)->format('d/m/Y');
            })
            ->addColumn('evaluacion_psicotecnica.fnacimiento', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                return Carbon::parse($evaluacionPsicotecnica->evaluacion_psicotecnica->fnacimiento)->format('d/m/Y');
            })
            ->addColumn('recomendacion', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                $documento = $evaluacionPsicotecnica->evaluacion_psicotecnica->documento;
                $recomendacion = RecomendacionCandidato::whereHasMorph(
                    'candidato',
                    [Agente::class, Candidato::class],
                    function (\Illuminate\Database\Eloquent\Builder $q) use ($documento) {
                        $q->whereRaw("documento = ?", intval($documento));
                    }
                )
                    ->orderBy('created_at', 'desc')
                    ->with([
                        'especialidad',
                        'formacion' => function ($query) {
                            $query->select('idtitulo', 'titulo');
                        },
                        'nivel' => function ($query) {
                            $query->select('idtipo_nivel', 'tiponivel');
                        },
                        'referidoInternoWithDefault' => function ($query) {
                            $query->select('idtipo_referido', 'nombre');
                        },
                        'referidoPoliticoWithDefault' => function ($query) {
                            $query->select('idtipo_referido', 'nombre');
                        },
                    ])
                    ->first();
                /** @var $recomendacion RecomendacionCandidato */
                return (isset($recomendacion) && !empty($recomendacion)) ? $recomendacion->toArray() :
                    [
                        "idrecomendacion_candidato" => '',
                        "especialidad" => [
                            "tipofuncion" => "-"
                        ],
                        "formacion" => [
                            "titulo" => '-',
                        ],
                        "nivel" => [
                            "tiponivel" => '-'
                        ],
                        "referido_interno_with_default" => [
                            "nombre" => "-",
                        ],
                        "referido_politico_with_default" => [
                            "nombre" => "-",
                        ]
                    ];
            })
            ->addColumn('recomienda_para', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
                if ($evaluacionPsicotecnica->idtipo_recomendacion == 1) {
                    $recomiendaPara = '';
                    if ($evaluacionPsicotecnica->tipoFuncion1) {
                        $recomiendaPara .= $evaluacionPsicotecnica->tipoFuncion1->tipofuncion;
                    }
                    if ($evaluacionPsicotecnica->tipoFuncion2) {
                        $recomiendaPara .= ' - ' . $evaluacionPsicotecnica->tipoFuncion2->tipofuncion;
                    }
                    if ($evaluacionPsicotecnica->tipoFuncion3) {
                        $recomiendaPara .= ' - ' . $evaluacionPsicotecnica->tipoFuncion3->tipofuncion;
                    }
                    return $recomiendaPara;
                }
            })
            ->rawColumns(['tipo_ingreso', 'observaciones_column']);
        $datatable = $this->filter($datatable);
        $datatable = $this->order($datatable);
        if (!$export) {
            $datatable = $this->noExport($datatable);
        }

        $laQuery = DB::getQueryLog();
        return $datatable;
    }

    /**
     * @param QueryDataTable $datatable
     * @return QueryDataTable
     */
    public function noExport(QueryDataTable $datatable): QueryDataTable
    {
        return $datatable
            ->addColumn('observaciones_column', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
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
            ->addColumn('tipo_ingreso', function (EvaluacionPsicotecnica $evaluacionPsicotecnica) {
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

    /**
     * @param QueryDataTable $datatable
     * @return QueryDataTable
     */
    private function filter(QueryDataTable $datatable): QueryDataTable
    {
        $datatable
            ->filterColumn('calle_column', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereRaw("LOWER(calle) LIKE ?", "%" . strtolower($keyword) . "%");
                        });
                    }
                );
            })
            ->filterColumn('departamento_column', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereHas('departamentoWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(departamento) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('localidad_column', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereHas('localidadWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(localidad) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('codigo_postal_column', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($que) use ($keyword) {
                        $que->whereHas('domicilioWithDefault', function ($qu) use ($keyword) {
                            $qu->whereRaw("CAST(codigo_postal AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                        });
                    }
                );
            })
            ->filterColumn('candidato_column.telefono', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(telefono AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.celular', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(celular AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.documento', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(documento AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.apellido', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("LOWER(apellido) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.nombre', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.fnacimiento', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("fnacimiento::text LIKE ?", "%" . strtolower($this->getFecha($keyword)) . "%");
                    }
                );
            })
            ->filterColumn('candidato_column.email', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($q, $type) use ($keyword) {
                        $q->whereRaw("CAST(email AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('titulo_formateado', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($quer, $type) use ($keyword) {
                        $quer->whereHas('recomendacion', function ($que) use ($keyword) {
                            $que->whereHas('formacion', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(titulo) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('especialidad_column.tipofuncion', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($quer, $type) use ($keyword) {
                        $quer->whereHas('recomendacion', function ($que) use ($keyword) {
                            $que->whereHas('especialidad', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(tipofuncion) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('nivel_column.tiponivel', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($quer, $type) use ($keyword) {
                        $quer->whereHas('recomendacion', function ($que) use ($keyword) {
                            $que->whereHas('nivel', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(tiponivel) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('tiporecomendacion_column', function ($query, $keyword) {
                $query->whereHas(
                    'tipoRecomendacion',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(tiporecomendacion) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('recomienda_para_column', function ($query, $keyword) {
                $query->whereHas(
                    'tipoFuncion1',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(tipofuncion) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                )->orWhereHas(
                    'tipoFuncion2',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(tipofuncion) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                )->orWhereHas(
                    'tipoFuncion3',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(tipofuncion) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('fecha_evaluacion_column', function ($query, $keyword) {
                $query->whereRaw("CAST(fecha_evaluacion AS TEXT) LIKE ?", "%" . strtolower($this->getFecha($keyword)) . "%");
            })
            ->filterColumn('created_at_column', function ($query, $keyword) {
                $query->whereRaw("created_at::text LIKE ?", "%" . strtolower($this->getFecha($keyword)) . "%");
            })
            ->filterColumn('observaciones_column', function ($query, $keyword) {
                $query->whereRaw("LOWER(observaciones) LIKE ?", "%" . strtolower($keyword) . "%");
            })
            ->filterColumn('psicoevaluador_column', function ($query, $keyword) {
                $query->whereHas(
                    'psicoevaluadorWithTrashed',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(firma) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('tipo_entrevista_column', function ($query, $keyword) {
                $query->whereHas(
                    'tipoEntrevista',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(tipoentrevista) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('grupo_column', function ($query, $keyword) {
                $query->whereHas(
                    'grupoWithDefault',
                    function ($quer) use ($keyword) {
                        $quer->whereRaw("LOWER(evaluacion_psicotecnica_grupo) LIKE ?", "%" . strtolower($keyword) . "%");
                    }
                );
            })
            ->filterColumn('puntaje_column', function ($query, $keyword) {
                $query->whereRaw("CAST(aspectos_cognitivos+aspectos_psicoafectivos+desempeno+motivacion+atencion_usuario+trabajo_en_equipo+tolerancia_presion+organizacion+adaptabilidad AS TEXT) LIKE ?", "%" . strtolower($keyword) . "%");
            })
            ->filterColumn('ingreso_column', function ($query, $keyword) {
                $periodo = Periodo::getPeriodoConFecha(null);

                $idEvaluaciones = $this->getIdsEvaluacionesPorPlanta(EvaluacionPsicotecnica::query());
                $idEvaluaciones = array_merge($idEvaluaciones, $this->getIdsEvaluacionesReemplazos(EvaluacionPsicotecnica::query(), $periodo));
                $idEvaluaciones = array_merge($idEvaluaciones, $this->getIdsEvaluacionesLibres(EvaluacionPsicotecnica::query()));
                $idEvaluaciones = array_merge($idEvaluaciones, $this->getIdsEvaluacionesGuardias(EvaluacionPsicotecnica::query(), $periodo));

                if (strtolower($keyword) == 'si') {
                    $query->whereIn('idevaluacion_psicotecnica', $idEvaluaciones);
                }
                if (strtolower($keyword) == 'no') {
                    $query->whereNotIn('idevaluacion_psicotecnica', $idEvaluaciones);
                }
            })
            ->filterColumn('tipo_ingreso_column', function ($query, $keyword) {
                $periodo = Periodo::getPeriodoConFecha(null);
                $split = explode('-', $keyword);
                foreach ($split as $sp) {
                    try {
                        if (strtolower(substr($sp, 0, 6)) == 'planta') {
                            $planta = explode(':', $sp);
                            if ($planta[1][0] == " ") {
                                $planta[1] = substr($planta[1], 1);
                            }
                            $plantas = explode(',', $planta[1]);
                            $tipo_planta = TipoPlanta::query();
                            foreach ($plantas as $p) {
                                $tipo_planta->orWhereRaw("LOWER(tipoplanta) LIKE ?", "%" . strtolower($p) . "%");
                            }
                            $tipo_planta = $tipo_planta->get()->toArray();
                            $idEvaluacionesPlanta = $this->getIdsEvaluacionesPorPlanta(EvaluacionPsicotecnica::query(), array_column($tipo_planta, 'idtipo_planta'));
                            $query->whereIn('idevaluacion_psicotecnica', $idEvaluacionesPlanta);
                        }
                    } catch (\Exception $e) {
                        Log::debug('Error Evaluaciones por planta '.$e->getMessage());
                    }
                    try {
                        if (strtolower(substr($sp, 0, 10)) == 'reemplazos') {
                            $reemplazo = explode(':', $sp);
                            $reemplazo[1] = trim($reemplazo[1]);
                            $idEvaluacionesReemplazo = $this->getIdsEvaluacionesReemplazos(EvaluacionPsicotecnica::query(), $periodo);
                            if (strtolower($reemplazo[1]) == 'si') {
                                $query->orWhereIn('idevaluacion_psicotecnica', $idEvaluacionesReemplazo);
                            } else {
                                $query->whereNotIn('idevaluacion_psicotecnica', $idEvaluacionesReemplazo);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::debug('Error Evaluaciones por Reemplazo '.$e->getMessage());
                    }
                    try {
                        if (strtolower(substr($sp, 0, 6)) == 'libres') {
                            $libres = explode(':', $sp);
                            $libres[1] = trim($libres[1]);
                            $idEvaluacionesLibres = $this->getIdsEvaluacionesLibres(EvaluacionPsicotecnica::query());
                            if (strtolower($libres[1]) == 'si') {
                                $query->orWhereIn('idevaluacion_psicotecnica', $idEvaluacionesLibres);
                            } else {
                                $query->whereNotIn('idevaluacion_psicotecnica', $idEvaluacionesLibres);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::debug('Error Evaluaciones por Reemplazo '.$e->getMessage());
                    }
                    try {
                        if (strtolower(substr($sp, 0, 8)) == 'guardias') {
                            $guardia = explode(':', $sp);
                            $guardia[1] = trim($guardia[1]);
                            $idEvaluacionesGuardia = $this->getIdsEvaluacionesGuardias(EvaluacionPsicotecnica::query(), $periodo);
                            if (strtolower($guardia[1]) == 'si') {
                                $query->orWhereIn('idevaluacion_psicotecnica', $idEvaluacionesGuardia);
                            } else {
                                $query->whereNotIn('idevaluacion_psicotecnica', $idEvaluacionesGuardia);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::debug('Error Evaluaciones por guardia '.$e->getMessage());
                    }
                }
            })
            ->filterColumn('referido_interno.nombre', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($quer, $type) use ($keyword) {
                        $quer->whereHas('recomendacion', function ($que) use ($keyword) {
                            $que->whereHas('referidoInternoWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
            })
            ->filterColumn('referido_politico.nombre', function ($query, $keyword) {
                $query->whereHasMorph(
                    'evaluacion_psicotecnica',
                    [Agente::class, Candidato::class],
                    function ($quer, $type) use ($keyword) {
                        $quer->whereHas('recomendacion', function ($que) use ($keyword) {
                            $que->whereHas('referidoPoliticoWithDefault', function ($q) use ($keyword) {
                                $q->whereRaw("LOWER(nombre) LIKE ?", "%" . strtolower($keyword) . "%");
                            });
                        });
                    }
                );
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
            ->orderColumn('calle_column', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinDomicilio($query);
                $query->orderBy(DB::raw('COALESCE(domagente.calle,domcandidato.calle)'), $order);
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
            ->orderColumn('codigo_postal_column', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinDomicilio($query);
                $query->orderBy(DB::raw('COALESCE(domagente.codigo_postal,domcandidato.codigo_postal)'), $order);
            })
            ->orderColumn('candidato_column.telefono', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.telefono,candidato.telefono)'), $order);
            })
            ->orderColumn('candidato_column.celular', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.celular,candidato.celular)'), $order);
            })
            ->orderColumn('candidato_column.documento', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.documento,candidato.documento)'), $order);
            })
            ->orderColumn('candidato_column.apellido', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.apellido,candidato.apellido)'), $order);
            })
            ->orderColumn('candidato_column.nombre', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.nombre,candidato.nombre)'), $order);
            })
            ->orderColumn('candidato_column.fnacimiento', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query->orderBy(DB::raw('COALESCE(agente.fnacimiento,candidato.fnacimiento)'), $order);
            })
            ->orderColumn('especialidad_column.tipofuncion', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinRecomendacion($query);
                $query = $this->leftJoinTipoFuncion($query);
                $query->orderBy(DB::raw('COALESCE(tfagente.tipofuncion,tfcandidato.tipofuncion)'), $order);
            })
            ->orderColumn('titulo_formateado', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinRecomendacion($query);
                $query = $this->leftJoinTitulo($query);
                $query->orderBy(DB::raw('COALESCE(titagente.titulo,titcandidato.titulo)'), $order);
            })
            ->orderColumn('nivel_column.tiponivel', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinRecomendacion($query);
                $query = $this->leftJoinTipoNivel($query);
                $query->orderBy(DB::raw('COALESCE(tnagente.tiponivel,tncandidato.tiponivel)'), $order);
            })
            ->orderColumn('tiporecomendacion_column', function ($query, $order) {
                $query->join('tipo_recomendacion', 'evaluacion_psicotecnica.idevaluacion_psicotecnica', '=', 'tipo_recomendacion.idtipo_recomendacion')
                    ->orderBy("tipo_recomendacion.tiporecomendacion", $order);
            })
            ->orderColumn('recomienda_para_column', function ($query, $order) {
                $query->join('tipo_funcion', 'evaluacion_psicotecnica.idtipo_funcion1', '=', 'tipo_funcion.idtipo_funcion')
                    ->orderBy("tipo_funcion.tipofuncion", $order);
            })
            ->orderColumn('fecha_evaluacion_column', function ($query, $order) {
                $query->orderBy("fecha_evaluacion", $order);
            })
            ->orderColumn('created_at_column', function ($query, $order) {
                $query->orderBy("created_at", $order);
            })
            ->orderColumn('observaciones_column', function ($query, $order) {
                $query->orderBy("observaciones", $order);
            })
            ->orderColumn('psicoevaluador_column', function ($query, $order) {
                $query->join('psicoevaluador', 'evaluacion_psicotecnica.idpsicoevaluador', '=', 'psicoevaluador.idpsicoevaluador')
                    ->orderBy("psicoevaluador.firma", $order);
            })
            ->orderColumn('tipo_entrevista_column', function ($query, $order) {
                $query->join('tipo_entrevista', 'evaluacion_psicotecnica.idtipo_entrevista', '=', 'tipo_entrevista.idtipo_entrevista')
                    ->orderBy("tipo_entrevista.tipoentrevista", $order);
            })
            ->orderColumn('grupo_column', function ($query, $order) {
                $query->join('evaluacion_psicotecnica_grupo', 'evaluacion_psicotecnica_grupo.idevaluacion_psicotecnica_grupo', '=', 'evaluacion_psicotecnica.idevaluacion_psicotecnica_grupo')
                    ->orderBy("evaluacion_psicotecnica_grupo.evaluacion_psicotecnica_grupo", $order);
            })
            ->orderColumn('puntaje_column', function ($query, $order) {
                $query->orderBy("puntaje", $order);
            })
            ->orderColumn('referido_interno.nombre', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinRecomendacion($query);
                $query = $this->leftJoinReferido($query, true);
                $query->orderBy(DB::raw('COALESCE(refeagente.nombre,refecandidato.nombre)'), $order);
            })
            ->orderColumn('referido_politico.nombre', function ($query, $order) {
                $query = $this->leftJoinCandidato($query);
                $query = $this->leftJoinRecomendacion($query);
                $query = $this->leftJoinReferido($query, false);
                $query->orderBy(DB::raw('COALESCE(refeagente.nombre,refecandidato.nombre)'), $order);
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
                $join->on('evaluacion_psicotecnica.evaluacion_psicotecnica_id', '=', 'agente.idagente');
                $join->on('evaluacion_psicotecnica.evaluacion_psicotecnica_type', '=', DB::raw("'App\Models\Agente'"));
            })
            ->leftJoin('candidato', function ($join) {
                $join->on('evaluacion_psicotecnica.evaluacion_psicotecnica_id', '=', 'candidato.idcandidato');
                $join->on('evaluacion_psicotecnica.evaluacion_psicotecnica_type', '=', DB::raw("'App\Models\Candidato'"));
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinRecomendacion($query)
    {
        return $query
            ->leftJoin('recomendacion_candidato as recoagente', function ($join) {
                $join->on('recoagente.candidato_id', '=', 'agente.idagente');
                $join->on('recoagente.candidato_type', '=', DB::raw("'App\Models\Agente'"));
            })->leftJoin('recomendacion_candidato as recocandidato', function ($join) {
                $join->on('recocandidato.candidato_id', '=', 'candidato.idcandidato');
                $join->on('recocandidato.candidato_type', '=', DB::raw("'App\Models\Candidato'"));
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTipoFuncion($query)
    {
        return $query
            ->leftJoin('tipo_funcion as tfagente', function ($join) {
                $join->on('tfagente.idtipo_funcion', '=', 'recoagente.idtipo_funcion');
            })->leftJoin('tipo_funcion as tfcandidato', function ($join) {
                $join->on('tfcandidato.idtipo_funcion', '=', 'recocandidato.idtipo_funcion');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTitulo($query)
    {
        return $query
            ->leftJoin('titulo as titagente', function ($join) {
                $join->on('titagente.idtitulo', '=', 'recoagente.idtitulo');
            })->leftJoin('titulo as titcandidato', function ($join) {
                $join->on('titcandidato.idtitulo', '=', 'recocandidato.idtitulo');
            });
    }

    /**
     * @param $query Builder
     * @return Builder
     */
    private function leftJoinTipoNivel($query)
    {
        return $query
            ->leftJoin('tipo_nivel as tnagente', function ($join) {
                $join->on('tnagente.idtipo_nivel', '=', 'recoagente.idtipo_nivel');
            })->leftJoin('tipo_nivel as tncandidato', function ($join) {
                $join->on('tncandidato.idtipo_nivel', '=', 'recocandidato.idtipo_nivel');
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
                ->leftJoin('tipo_referido as refeagente', function ($join) {
                    $join->on('refeagente.idtipo_referido', '=', 'recoagente.idtipo_referido_interno');
                })->leftJoin('tipo_referido as refecandidato', function ($join) {
                    $join->on('refecandidato.idtipo_referido', '=', 'recocandidato.idtipo_referido_interno');
                });
        } else {
            return $query
                ->leftJoin('tipo_referido as refeagente', function ($join) {
                    $join->on('refeagente.idtipo_referido', '=', 'recoagente.idtipo_referido_politico');
                })->leftJoin('tipo_referido as refecandidato', function ($join) {
                    $join->on('refecandidato.idtipo_referido', '=', 'recocandidato.idtipo_referido_politico');
                });
        }
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
     * @param $evaluacionesPlanta \Illuminate\Database\Eloquent\Builder
     * @param $plantas array
     * @return array
     */
    private function getIdsEvaluacionesPorPlanta($evaluacionesPlanta, $plantas = array())
    {
        $evaluacionesPlanta->where('evaluacion_psicotecnica_type', 'App\Models\Agente')
            ->leftJoin('puesto', function ($join) {
                $join->on('puesto.idagente', '=', 'evaluacion_psicotecnica.evaluacion_psicotecnica_id');
            })
            ->leftJoin('tipo_planta', function ($join) {
                $join->on('tipo_planta.idtipo_planta', '=', 'puesto.idtipo_planta');
            })
            ->whereRaw('puesto.idpuesto in(select idpuesto from puesto where puesto.idagente=evaluacion_psicotecnica.evaluacion_psicotecnica_id order by idpuesto desc LIMIT 1)');
        if (count($plantas) == 0) {
            $evaluacionesPlanta->whereIn('tipo_planta.idtipo_planta', [1,2,4,5,6]);
        } else {
            $evaluacionesPlanta->whereIn('tipo_planta.idtipo_planta', $plantas);
        }
        $evaluacionesPlanta = $evaluacionesPlanta->get()->toArray();
        return array_column($evaluacionesPlanta, 'idevaluacion_psicotecnica');
    }

    /**
     * @param $evaluacionesRee \Illuminate\Database\Eloquent\Builder
     * @param $periodo Periodo
     * @return array
     */
    private function getIdsEvaluacionesReemplazos($evaluacionesRee, $periodo)
    {
        $evaluacionesRee
            ->leftJoin('puesto', function ($join) {
                $join->on('puesto.idagente', '=', 'evaluacion_psicotecnica.evaluacion_psicotecnica_id');
            })
            ->leftJoin('reemplazo', function ($join) {
                $join->on('puesto.idpuesto', '=', 'reemplazo.idpuesto_reemplazante');
            })
            ->whereRaw('reemplazo.idreemplazo is not null')
            ->where('aprobado', true)
            ->where('idperiodo', $periodo->idperiodo)
            ->groupBy(['reemplazo.iddependencia','evaluacion_psicotecnica.idevaluacion_psicotecnica'])
            ->select(DB::raw('evaluacion_psicotecnica.idevaluacion_psicotecnica,count(*)'));
        $evaluacionesRee = $evaluacionesRee->get()->toArray();
        return array_column($evaluacionesRee, 'idevaluacion_psicotecnica');
    }

    /**
     * @param $evaluacionesLibres \Illuminate\Database\Eloquent\Builder
     * @return array
     */
    private function getIdsEvaluacionesLibres($evaluacionesLibres)
    {
        $evaluacionesLibres
            ->leftJoin('puesto', function ($join) {
                $join->on('puesto.idagente', '=', 'evaluacion_psicotecnica.evaluacion_psicotecnica_id');
            })
            ->leftJoin('ld_alta', function ($join) {
                $join->on('puesto.idpuesto', '=', 'ld_alta.idpuesto');
            })
            ->groupBy(['ld_alta.iddependencia_destino','evaluacion_psicotecnica.idevaluacion_psicotecnica'])
            ->where('idld_estado', 3)
            ->select(DB::raw('evaluacion_psicotecnica.idevaluacion_psicotecnica,count(*)'));
        $evaluacionesLibres = $evaluacionesLibres->get()->toArray();
        return array_column($evaluacionesLibres, 'idevaluacion_psicotecnica');
    }

    /**
     * @param $evaluacionesGuardia \Illuminate\Database\Eloquent\Builder
     * @param $periodo Periodo
     * @return array
     */
    private function getIdsEvaluacionesGuardias($evaluacionesGuardia, $periodo)
    {
        $evaluacionesGuardia
            ->leftJoin('puesto', function ($join) {
                $join->on('puesto.idagente', '=', 'evaluacion_psicotecnica.evaluacion_psicotecnica_id');
            })
            ->leftJoin('guardia_linea', function ($join) {
                $join->on('puesto.idpuesto', '=', 'guardia_linea.idpuesto');
            })
            ->leftJoin('guardia', function ($join) {
                $join->on('guardia.idguardia', '=', 'guardia_linea.idguardia');
            })
            ->where('guardia.idperiodo', $periodo->idperiodo)
            ->whereIn('idguardia_tipo_estado_linea', [1, 3, 4])
            ->groupBy(['guardia.idservicio','evaluacion_psicotecnica.idevaluacion_psicotecnica'])
            ->select(DB::raw('evaluacion_psicotecnica.idevaluacion_psicotecnica,count(*)'));
        $evaluacionesGuardia = $evaluacionesGuardia->get()->toArray();
        return array_column($evaluacionesGuardia, 'idevaluacion_psicotecnica');
    }

    /**
     * @param $keyword string
     * @return string
     */
    private function getFecha($keyword)
    {
        $array = explode('/', $keyword);
        $array = array_reverse($array);
        $fecha = '';
        for ($i = 0; $i < count($array); $i++) {
            if ($i != 0) {
                $fecha .= '-' . $array[$i];
            } else {
                $fecha .= $array[$i];
            }
        }
        return $fecha;
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