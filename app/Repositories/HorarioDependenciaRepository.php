<?php
namespace App\Repositories;

use App\Models\Puesto;
use App\Models\Dependencia;
use App\Models\RangoTiempo;
use App\Models\HorarioPuesto;
use App\Models\HorarioDependencia;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class HorarioDependenciaRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:09 pm -03
 *
 * @method HorarioDependencia findWithoutFail($id, $columns = ['*'])
 * @method HorarioDependencia find($id, $columns = ['*'])
 * @method HorarioDependencia first($columns = ['*'])
*/
class HorarioDependenciaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idtipo_dia',
        'iddependencia',
        'hora_desde',
        'hora_hasta',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return HorarioDependencia::class;
    }

    /**
     * Crear un modelo
     *
     * @param [type] $inputs
     * @return bool
     */
    public function create($inputs)
    {
        DB::beginTransaction();
        if ($inputs['tipoHorario'] === 'p') {
            $horariosExistentes = HorarioDependencia::where('iddependencia', (int) $inputs['idservicio'])->whereIn('idtipo_dia', [8, 9, 10]);
            foreach ($horariosExistentes->get() as $h) {
                $h->delete();
            }

            foreach ($inputs['dias'] as $key => $dia) {
                if ($dia['isChecked'] === true && isset($dia['hora_desde'], $dia['hora_hasta'])) {
                    if (isset($dia['id']) && is_numeric($dia['id'])) {
                        $horario = HorarioDependencia::find((int) $dia['id']);
                    } else {
                        $horario = new HorarioDependencia();
                    }

                    $horario->idtipo_dia = $key + 1;
                    $horario->iddependencia = $inputs['idservicio'];
                    $horario->hora_desde = $dia['hora_desde'];
                    $horario->hora_hasta = $dia['hora_hasta'];

                    if (!$horario->save()) {
                        DB::rollBack();
                        return false;
                    }
                }
            }
        } else {
            $horariosExistentes = HorarioDependencia::where('iddependencia', (int) $inputs['idservicio'])->whereIn('idtipo_dia', [1, 2, 3, 4, 5, 6, 7]);
            foreach ($horariosExistentes->get() as $h) {
                $h->delete();
            }

            if (isset($inputs['id']) && is_numeric($inputs['id'])) {
                $horario = HorarioDependencia::find((int) $inputs['id']);
            } else {
                $horario = new HorarioDependencia();
            }
            $horario->idtipo_dia = $inputs['tipoHorario'] === 'lv' ? 8 : 10;
            $horario->iddependencia = $inputs['idservicio'];
            $horario->hora_desde = $inputs['hora_desde'];
            $horario->hora_hasta = $inputs['hora_hasta'];
            if (!$horario->save()) {
                DB::rollBack();
                return false;
            }
        }

        DB::commit();
        return true;
    }

    private function hasHorarioFueraRango(string $tipoHorario, string $horaDesde = null, string $horaHasta = null, array $dias = [], array $descendencia = [], $tipoHorarioAControlar = 'agentes'): int
    {
        if ($tipoHorarioAControlar === 'servicios') {
            array_shift($descendencia);
        }
        $claseHorario = $tipoHorarioAControlar === 'agentes' ? HorarioPuesto::class : HorarioDependencia::class;
        $agentesFueraDeRangoHorario = 0;
        if ($tipoHorario === 'ld') {
            $horariosDescendencia = $claseHorario::whereIn('iddependencia', $descendencia)
                                                    ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                        $query->whereHasMorph(
                                                            'puesto',
                                                            Puesto::class,
                                                            function (Builder $q) {
                                                                $q->whereNull('fhasta');
                                                            }
                                                        );
                                                    })
                                                    ->get();
            foreach ($horariosDescendencia as $horario) {
                $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);

                if ($tipoHorarioAControlar === 'efectores') {
                    $horarioDentroRango = $rango1->estaDentroDeRango($rango2);
                } else {
                    $horarioDentroRango = $rango2->estaDentroDeRango($rango1);
                }

                if (!$horarioDentroRango) {
                    $agentesFueraDeRangoHorario++;
                }
            }
        } elseif ($tipoHorario === 'lv' && $horaDesde === $horaHasta) {
            //Busco horarios Sábado, Domingo, Sábado Guardia, Domingo Guardia y Lunes a Domingo
            $idtipo_dia = [6, 7, 10, 16, 17];
            $horariosDescendencia = $claseHorario::whereIn('iddependencia', $descendencia)
                ->whereIn('idtipo_dia', $idtipo_dia)
                ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                    $query->whereHasMorph(
                        'puesto',
                        Puesto::class,
                        function (Builder $q) {
                            $q->whereNull('fhasta');
                        }
                    );
                })
                ->count();
            if ($horariosDescendencia > 0) {
                $agentesFueraDeRangoHorario += $horariosDescendencia;
            }
        } elseif ($tipoHorario === 'lv' && $horaDesde !== $horaHasta) {
            //Busco días Lunes, Martes, Miércoles, Jueves, Viernes y Días de Guardia
            if ($tipoHorarioAControlar === 'efectores') {
                $idtipo_dia = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17];
            } else {
                $idtipo_dia = [1, 2, 3, 4, 5, 8, 9, 11, 12, 13, 14, 15];
            }
            $horariosDescendencia = $claseHorario::whereIn('iddependencia', $descendencia)
                                                    ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                        $query->whereHasMorph(
                                                            'puesto',
                                                            Puesto::class,
                                                            function (Builder $q) {
                                                                $q->whereNull('fhasta');
                                                            }
                                                        );
                                                    })
                                                    ->get();
            foreach ($horariosDescendencia as $horario) {
                $isDia = in_array($horario->idtipo_dia, $idtipo_dia);
                if ($isDia) {
                    $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                    $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);

                    if ($tipoHorarioAControlar === 'efectores') {
                        $horarioDentroRango = $rango1->estaDentroDeRango($rango2);
                    } else {
                        $horarioDentroRango = $rango2->estaDentroDeRango($rango1);
                    }
                }
                if ((isset($horarioDentroRango) && !$horarioDentroRango) || !$isDia) {
                    $agentesFueraDeRangoHorario++;
                }
            }
        } else {
            //Horario Personalizado
            $tiposDiaFueraHorario = collect();

            //Busco días fuera del horario personalizado elegido
            foreach ($dias as $i => $dia) {
                if (!$dia['isChecked']) {
                    $tiposDiaFueraHorario->push($i + 1);
                    $tiposDiaFueraHorario->push($i + 11);
                }
            }

            //Sabado o domingo no seleccionado -> Lunes a Domingos no puede incluir
            $isSabadoODomingo = !$dias[5]['isChecked'] || !$dias[6]['isChecked'];
            if ($isSabadoODomingo) {
                $tiposDiaFueraHorario->push(10);
            }

            //Lunes, Martes, Miércoles, Jueves, Viernes no seleccionado -> Lunes a Viernes no puede incluir
            $isLunesAViernes = $dias[0]['isChecked'] && $dias[1]['isChecked'] && $dias[2]['isChecked'] && $dias[3]['isChecked'] && $dias[4]['isChecked'];
            if (!$isLunesAViernes) {
                $tiposDiaFueraHorario->push(8);
                $tiposDiaFueraHorario->push(10);
            }

            //Si existen dias fuera del horario personalizado, busco horarios en esos días
            if ($tiposDiaFueraHorario->count() > 0) {
                $horariosDistintoDia = $claseHorario::whereIn('iddependencia', $descendencia)
                                                        ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                            $query->whereHasMorph(
                                                                'puesto',
                                                                Puesto::class,
                                                                function (Builder $q) {
                                                                    $q->whereNull('fhasta');
                                                                }
                                                            );
                                                        })
                                                        ->whereIn('idtipo_dia', $tiposDiaFueraHorario->toArray())
                                                        ->count();
                if ($horariosDistintoDia > 0) {
                    $agentesFueraDeRangoHorario += $horariosDistintoDia;
                }
            }

            //Recorro los días y voy buscando horarios para cada día y el día de guardia
            foreach ($dias as $i => $dia) {
                if ($dia['isChecked']) {
                    $horarios = $claseHorario::whereIn('iddependencia', $descendencia)
                                                ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                    $query->whereHasMorph(
                                                        'puesto',
                                                        Puesto::class,
                                                        function (Builder $q) {
                                                            $q->whereNull('fhasta');
                                                        }
                                                    );
                                                })
                                                ->whereIn('idtipo_dia', [($i + 1), ($i + 11)])
                                                ->get();
                    foreach ($horarios as $horario) {
                        $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                        $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);

                        if ($tipoHorarioAControlar === 'efectores') {
                            $horarioDentroRango = $rango1->estaDentroDeRango($rango2);
                        } else {
                            $horarioDentroRango = $rango2->estaDentroDeRango($rango1);
                        }

                        if (!$horarioDentroRango) {
                            $agentesFueraDeRangoHorario++;
                        }
                    }
                }
            }

            //Recorro todos los días y voy buscando horarios rotativos
            foreach ($dias as $i => $dia) {
                if ($dia['isChecked']) {
                    $horarios = $claseHorario::whereIn('iddependencia', $descendencia)
                                                ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                    $query->whereHasMorph(
                                                        'puesto',
                                                        Puesto::class,
                                                        function (Builder $q) {
                                                            $q->whereNull('fhasta');
                                                        }
                                                    );
                                                })
                                                ->where('idtipo_dia', 9)
                                                ->get();
                    foreach ($horarios as $horario) {
                        $rango1 = new RangoTiempo($dia['hora_desde'], $dia['hora_hasta']);
                        $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);

                        if ($tipoHorarioAControlar === 'efectores') {
                            $horarioDentroRango = $rango1->estaDentroDeRango($rango2);
                        } else {
                            $horarioDentroRango = $rango2->estaDentroDeRango($rango1);
                        }

                        if (!$horarioDentroRango) {
                            $agentesFueraDeRangoHorario++;
                        }
                    }
                }
            }

            //Recorro los días de lunes a viernes y voy buscando horarios con Lunes a Viernes
            if ($isLunesAViernes) {
                foreach ($dias as $i => $dia) {
                    if ($dia['isChecked']) {
                        $horarios = $claseHorario::whereIn('iddependencia', $descendencia)
                                                    ->when($tipoHorarioAControlar === 'agentes', function ($query) {
                                                        $query->whereHasMorph(
                                                            'puesto',
                                                            Puesto::class,
                                                            function (Builder $q) {
                                                                $q->whereNull('fhasta');
                                                            }
                                                        );
                                                    })
                                                    ->where('idtipo_dia', 8)
                                                    ->get();
                        foreach ($horarios as $horario) {
                            $rango1 = new RangoTiempo($dia['hora_desde'], $dia['hora_hasta']);
                            $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);

                            if ($tipoHorarioAControlar === 'efectores') {
                                $horarioDentroRango = $rango1->estaDentroDeRango($rango2);
                            } else {
                                $horarioDentroRango = $rango2->estaDentroDeRango($rango1);
                            }

                            if (!$horarioDentroRango) {
                                $agentesFueraDeRangoHorario++;
                            }
                        }
                    }
                }
            }
        }

        return $agentesFueraDeRangoHorario;
    }

    public function hasAgentesFueraDeRangoHorario(array $input): int
    {
        $dependencia = Dependencia::find((int) $input['idservicio']);
        $descendencia = $dependencia->getIdsDescendencia();
        $agentesFueraRangoHorario = $this->hasHorarioFueraRango($input['tipoHorario'], $input['hora_desde'], $input['hora_hasta'], $input['dias'], $descendencia, 'agentes');
        return $agentesFueraRangoHorario;
    }

    public function hasServiciosFueraDeRangoHorario(array $input): int
    {
        $dependencia = Dependencia::find((int) $input['idservicio']);
        if ($dependencia->horarios()->count() < 1) {
            return 0;
        }
        $descendencia = $dependencia->getIdsDescendencia();
        $serviciosFueraDeRangoHorario = $this->hasHorarioFueraRango($input['tipoHorario'], $input['hora_desde'], $input['hora_hasta'], $input['dias'], $descendencia, 'servicios');
        return $serviciosFueraDeRangoHorario;
    }

    public function hasPadresFueraDeRangoHorario(array $input): int
    {
        $idservicio = (int) $input['idservicio'];
        $dependencia = Dependencia::find($idservicio);
        $ascendencia = $dependencia->getAscendencia();
        $padresFueraDeRangoHorario = $this->hasHorarioFueraRango($input['tipoHorario'], $input['hora_desde'], $input['hora_hasta'], $input['dias'], $ascendencia, 'efectores');
        return $padresFueraDeRangoHorario;
    }

    public function puestoActivo($query)
    {
        return $query->whereHasMorph(
            'puesto',
            Puesto::class,
            function (Builder $q) {
                $q->whereNull('fhasta');
            }
        );
    }
}
