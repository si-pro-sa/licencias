<?php
namespace App\Repositories;

use App\Models\HorarioPuesto;
use App\Models\PuestoAdicional;
use App\Models\HorarioPuestoHistorico;
use App\Models\Puesto;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class HorarioPuestoRepository
 * @package App\Repositories
 * @version October 6, 2020, 7:07 am -03
*/

class HorarioPuestoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'puesto_id',
        'puesto_type',
        'idtipo_dia',
        'iddependencia',
        'hora_desde',
        'hora_hasta',
        'cantidad_mensual',
        'cantidad_horas',
        'created_by',
        'updated_by'
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
        return HorarioPuesto::class;
    }

    /**
     * Configure the Model
     **/
    public function modelHistorico()
    {
        return HorarioPuestoHistorico::class;
    }

    public function getHorarioAgente(int $idpuesto) : array
    {
        //Obtengo horario sistema nuevo
        $horariosPuestoPrincipal = $this->model
            ->with(['tipoDia', 'dependencia'])
            ->puestoPrincipal($idpuesto)
            ->get()
            ->map->format()
            ->all();

        //Busco puestos adicionales
        $puestosAdicionales = PuestoAdicional::select('idpuesto_adicional')
            ->where('idpuesto', $idpuesto)
            ->get();

        $horariosPuestosAdicionales = collect();
        if (isset($puestosAdicionales)) {
            foreach ($puestosAdicionales as $puesto) {
                //Obtengo horarios de puestos adicionales
                $horariosPuestosAdicionales->push($this->model
                    ->with(['tipoDia', 'dependencia'])
                    ->puestoAdicional($puesto->idpuesto_adicional)
                    ->get()
                    ->map->format()
                    ->all());
            }
        }

        //Obtengo horarios de sistema histórico si no tiene horarios cargados
        if (count($horariosPuestoPrincipal) === 0 && count($horariosPuestosAdicionales) === 0) {
            $horariosPuestosAdicionales = HorarioPuestoHistorico::with(['tipoHorario', 'puesto', 'puesto.dependencia'])
                ->where('idpuesto', $idpuesto)
                ->get()
                ->map->format()
                ->all();
            return $horariosPuestosAdicionales;
        }
        return array_merge($horariosPuestoPrincipal, $horariosPuestosAdicionales->collapse()->all());
    }

    public function getHorarioDiaAgente(int $idpuesto, int $idtipo_dia) : array
    {
        //Obtengo horario sistema nuevo
        $horariosPuestoPrincipal = $this->model
            ->with(['tipoDia', 'dependencia'])
            ->where('idtipo_dia', $idtipo_dia)
            ->puestoPrincipal($idpuesto)
            ->get()
            ->map->format()
            ->all();
        //Busco puestos adicionales
        $horariosPuestosAdicionales = collect();
        $puestosAdicionales = PuestoAdicional::select('idpuesto_adicional')
            ->where('idpuesto', $idpuesto)
            ->get();

        if (isset($puestosAdicionales)) {
            foreach ($puestosAdicionales as $puesto) {
                //Obtengo horarios de puestos adicionales
                $horariosPuestosAdicionales->push($this->model
                    ->with(['tipoDia', 'dependencia'])
                    ->puestoAdicional($puesto->idpuesto_adicional)
                    ->where('idtipo_dia', $idtipo_dia)
                    ->get()
                    ->map->format()
                    ->all());
            }
        }

        return array_merge($horariosPuestoPrincipal, $horariosPuestosAdicionales->collapse()->all());
    }

    public function create($inputs)
    {
        DB::beginTransaction();
        $documento = (int) $inputs['documento'];
        //Borro horarios históricos existentes
        HorarioPuestoHistorico::documento($documento)->delete();
        //Borro horarios existentes
        foreach ($inputs['efectores'] as $horario) {
            HorarioPuesto::where('puesto_id', $horario['puesto_id'])
                        ->where('puesto_type', $horario['puesto_type'])
                        ->delete();
        }

        foreach ($inputs['efectores'] as $efector) {
            $idtipo_dia = [];
            //Creación de horarios personalizados o personalizados guardia
            if ($efector['tipoHorario'] === 'p' || $efector['tipoHorario'] === 'pg') {
                foreach ($efector['dias'] as $key => $dia) {
                    if ($dia['isChecked'] === true) {
                        $tipoDia = HorarioPuesto::getTipoDia($efector['tipoHorario'], $key);
                        $idtipo_dia[] = $tipoDia['idtipo_dia'] ?? 0;
                        $horarioCreado = HorarioPuesto::createOrUpdateHorario($efector['puesto_id'], $efector['puesto_type'], $efector['tipoHorario'], $efector['idservicio'], $dia['hora_desde'], $dia['hora_hasta'], $key);
                        if (!$horarioCreado) {
                            DB::rollBack();
                            return false;
                        }
                    }
                }
                //Crear horarios de lunes a viernes, rotativos o lunes a domingo
            } else {
                $tipoDia = HorarioPuesto::getTipoDia($efector['tipoHorario'], $efector['cantidad_mensual']);
                $idtipo_dia[] = $tipoDia['idtipo_dia'] ?? 0;
                $horarioCreado = HorarioPuesto::createOrUpdateHorario($efector['puesto_id'], $efector['puesto_type'], $efector['tipoHorario'], $efector['idservicio'], $efector['hora_desde'], $efector['hora_hasta'], $efector['cantidad_mensual']);
                if (!$horarioCreado) {
                    DB::rollBack();
                    return false;
                }
            }
        }
        DB::commit();
        return true;
    }

    public function isHorarioHistorico(int $idpuesto): bool
    {
        return HorarioPuestoHistorico::where('idpuesto', $idpuesto)->count() > 0;
    }

    public function existeInterposicionHoraria(int $idpuesto, string $fecha, string $horaDesde, string $horaHasta): bool
    {
        $puesto = Puesto::firstWhere('idpuesto', $idpuesto);
        return $puesto->existeInterposicionHoraria($fecha, $horaDesde, $horaHasta);
    }

    //Retorna Horario de Puesto Principal en una sola línea para visado de reemplazos y agentes de planta
    public function getHorarioTrabajoFormateado(int $idpuesto): string
    {
        $puesto = Puesto::where('idpuesto', $idpuesto)->first();
        return $puesto->horario_trabajo_formateado;
    }
}
