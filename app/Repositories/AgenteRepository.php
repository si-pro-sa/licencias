<?php

namespace App\Repositories;

use App\Models\Agente;
use App\Models\Puesto;
use App\Models\AgenteTitulo;
use App\Models\Candidato;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class AgenteRepository
 * @package App\Repositories
 * @version November 29, 2018, 9:02 am UTC
 *
 * @method Agente findWithoutFail($id, $columns = ['*'])
 * @method Agente find($id, $columns = ['*'])
 * @method Agente first($columns = ['*'])
 */
class AgenteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
    ];

    /**
     * Return searchable fields.
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Agente::class;
    }

    public function getDatosAgente(int $idagente)
    {
        $agente = $this->find($idagente);
        if (isset($agente)) {
            $puesto = $agente->puestoActivo();
            $datosAgente = [
                'idagente' => $agente->idagente,
                'idpuesto' => ($puesto->idpuesto ?? ''),
                'documento' => $agente->documento,
                'cuil' => $agente->cuil,
                'sexo' => ($agente->tipoSexo->tiposexo ?? ''),
                'apellido' => $agente->apellido,
                'nombre' => $agente->nombre,
                'fecha_nacimiento' => (isset($agente->fnacimiento) ? $agente->fnacimiento->format('d/m/Y') : ''),
                'fecha_alta' => (isset($agente->falta) ? $agente->falta->format('d/m/Y') : ''),
                'edad' => $agente->edad,
            ];
            return $datosAgente;
        }
        return null;
    }

    public function getAgente($agente)
    {
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw("CONCAT(documento,' - ',nombre,apellido) as label");
        $results = $this->model
            ->addSelect(DB::raw('CONCAT(documento,nombre) as label'))
            ->addSelect('*')
            ->agente($agente)
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    /**
     * @param $documento integer
     *
     * @return AgenteRepository
     */
    public function documento($documento)
    {
        $this->scopeQuery(function ($query) use ($documento) {
            return $query->where(function ($query) use ($documento) {
                $query->where('documento', $documento);
            });
        });

        return $this;
    }

    public function getAgentesActivos(): array
    {
        return $this->whereHas('puestos', function ($q) {
            $q->where('fhasta', null);
        })
            ->with([
                'puestos',
                'puestos.tipoPlanta',
                'puestos.tipoFuncion',
                'puestos.tipoNivel',
                'puestos.tipoAgrupamiento',
                'puestos.dependencia',
                'puestos.horario_historico',
                'puestos.horario_historico.tipoHorario',
                'puestos.tipoEspecialidad',
                'titulos'
            ])
            ->get()
            ->map->format()
            ->all();
    }

    public function crearAgente($inputs)
    {
        $documento = $inputs['documento'];

        $cantidadAgentes = Agente::documento($documento)->count();
        if ($cantidadAgentes > 0) {
            return 'Ya existe un Agente con el mismo DNI';
        }

        return $this->createAgente($inputs);
    }

    private function createAgente($inputs)
    {
        DB::beginTransaction();
        $agente = new Agente();
        $agente->documento = $inputs['documento'];
        $agente->apellido = $inputs['apellido'];
        $agente->nombre = $inputs['nombre'];
        $agente->fnacimiento = $inputs['fnacimiento'];
        $agente->cuil = $inputs['cuil'];
        $agente->idtipo_sexo = $inputs['idtipo_sexo'];
        $agente->falta = $inputs['falta'];
        $agente->telefono = $inputs['telefono'];
        $agente->celular = $inputs['celular'];
        $agente->email = $inputs['email'];

        $agenteCreado = $agente->save();
        if ($agenteCreado && empty($inputs['idagente'])) {
            $agenteTitulo = new AgenteTitulo();
            $agenteTitulo->idagente = $agente->idagente;
            $agenteTitulo->idtitulo = $inputs['idtitulo'];
            $agenteTitulo->falta = $inputs['falta'];

            if (!$agenteTitulo->save()) {
                DB::rollBack();
                return 'No se pudo crear el Título del Agente';
            }

            $puesto = new Puesto();
            $puesto->idagente = $agente->idagente;
            $puesto->fdesde = $inputs['falta'];
            $puesto->idtipo_planta = $inputs['idtipo_planta'];
            $puesto->iddependencia = $inputs['idservicio'];
            $puesto->idtipo_agrupamiento = $inputs['idtipo_agrupamiento'];
            $puesto->idtipo_funcion = $inputs['idtipo_funcion'];
            $puesto->idtipo_nivel = $inputs['idtipo_nivel'];
            $puesto->idtipo_especialidad = $inputs['idtipo_especialidad'];

            if (!$puesto->save()) {
                DB::rollBack();
                return 'No se pudo crear el Puesto del Agente';
            }

            $candidatoBorrado = Candidato::corregirRegistrosRelacionadosACandidato($inputs['documento'] ?? null, $agente->idagente, get_class($agente));

            if (!$candidatoBorrado) {
                DB::rollBack();
                return 'No se pudo borrar el Candidato';
            }

            DB::commit();
            return true;
        }
    }
}
