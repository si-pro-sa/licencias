<?php

namespace App\Repositories;

use App\Models\Reemplazo;
use App\Models\Dependencia;
use App\Models\CostoLaboral;
use App\Repositories\BaseRepository;
use App\Models\ReemplazoAprobado;
use App\Models\ReemplazoDesaprobado;
use App\Models\ReemplazoDesvisado;
use Exception;

/**
 * Class ReemplazoRepository
 * @package App\Repositories
 * @version March 8, 2020, 9:03 pm -03
*/

class ReemplazoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idperiodo',
        'idformulario',
        'iddependencia',
        'idagente_reemplazado',
        'idagente_reemplazante',
        'usuario',
        'operacion',
        'foperacion',
        'fdesde',
        'fhasta',
        'idtipo_nivel',
        'idtipo_funcion',
        'idtipo_agrupamiento',
        'idtipo_novedad',
        'aprobado',
        'desaprobado',
        'idusuario',
        'iddependenciapadre',
        'idtipo_horario',
        'horario',
        'idpuesto_reemplazado',
        'idpuesto_reemplazante',
        'idtipo_solicitud',
        'idreemplazo_padre',
        'estado',
        'novedad',
        'idtipo_nivel_reemplazado',
        'idtipo_nivel_reemplazante'
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
        return Reemplazo::class;
    }

    public function getReemplazosAgente(int $idagente, $reemplazante = false) : array
    {
        if ($reemplazante) {
            $tipoAgente = 'idagente_reemplazante';
            $relacionPuesto = 'puestoReemplazante';
            $relacionAgente = 'agenteReemplazante';
        } else {
            $tipoAgente = 'idagente_reemplazado';
            $relacionPuesto = 'puestoReemplazado';
            $relacionAgente = 'agenteReemplazado';
        }
        
        return $this->model
        ->with(['tipoNovedad', $relacionAgente, $relacionPuesto, 'puestoReemplazado.agente', 'puestoReemplazante', 'puestoReemplazante.agente'])
        ->where(function ($que) use ($idagente, $tipoAgente, $relacionPuesto) {
            $que->where($tipoAgente, $idagente)
                ->orWhereHas($relacionPuesto, function ($q) use ($idagente) {
                    $q->where('idagente', $idagente);
                });
        })
        ->orderByDesc('fhasta')
        ->limit(25)
        ->get()
        ->map->format($reemplazante)
        ->all();
    }
    public function getReemplazosAgenteDni(int $idagente, $reemplazante = false) : array
    {
        if ($reemplazante) {
            $tipoAgente = 'idagente_reemplazante';
            $relacionPuesto = 'puestoReemplazante';
            $relacionAgente = 'agenteReemplazante';
        } else {
            $tipoAgente = 'idagente_reemplazado';
            $relacionPuesto = 'puestoReemplazado';
            $relacionAgente = 'agenteReemplazado';
        }
        
        return $this->model
        ->with(['tipoNovedad', $relacionAgente, $relacionPuesto, 'puestoReemplazado.agente', 'puestoReemplazante', 'puestoReemplazante.agente','efectivaPrestacionReemplazos'])
        ->where(function ($que) use ($idagente, $tipoAgente, $relacionPuesto) {
            $que->where($tipoAgente, $idagente)
                ->orWhereHas($relacionPuesto, function ($q) use ($idagente) {
                    $q->where('idagente', $idagente);
                });
        })
        ->orderByDesc('fhasta')
        ->limit(25)
        ->get()
        ->map->format($reemplazante)
        ->all();
    }
    public function getF1Visado() : array
    {
        //Ultimo período realizado
        $fechaFin = Reemplazo::getFechaPeriodoRealizado();

        $costoLaboral = Costolaboral::whereNull('fhasta')->orderByDesc('fdesde')->first();

        return $this->model
        ->with(['tipoNovedad', 'puestoReemplazado', 'puestoReemplazado.agente', 'puestoReemplazante', 'puestoReemplazante.agente',
        'puestoReemplazado.tipoNivel', 'puestoReemplazante.tipoNivel', 'puestoReemplazado.tipoFuncion', 'puestoReemplazante.tipoFuncion',
        'dependencia', 'tipoSolicitud', 'tipoHorario', 'puestoReemplazado.horarios', 'puestoReemplazado.horario_historico'])
        ->whereNull('aprobado')
        ->whereNull('desaprobado')
        ->where('idformulario', 4)
        // ->orderByDesc('dependencia.dependencia')
        ->orderByDesc('idreemplazo')
        ->get()
        ->map->formatVisado($fechaFin, $costoLaboral)
        ->all();
    }

    public function getF1VisadoUO(int $iddependencia) : array
    {
        //Ultimo período realizado
        $fechaFin = Reemplazo::getFechaPeriodoRealizado();

        $costoLaboral = Costolaboral::whereNull('fhasta')->orderByDesc('fdesde')->first();

        $dependencia = Dependencia::firstWhere('iddependencia', $iddependencia);

        return $this->model
        ->with(['tipoNovedad', 'puestoReemplazado', 'puestoReemplazado.agente', 'puestoReemplazante', 'puestoReemplazante.agente',
        'puestoReemplazado.tipoNivel', 'puestoReemplazante.tipoNivel', 'puestoReemplazado.tipoFuncion', 'puestoReemplazante.tipoFuncion',
        'dependencia', 'tipoSolicitud', 'tipoHorario', 'puestoReemplazado.horarios', 'puestoReemplazado.horario_historico'])
        ->whereNull('aprobado')
        ->whereNull('desaprobado')
        ->where('idformulario', 4)
        ->whereIn('iddependencia', $dependencia->getIdsDescendencia())
        // ->orderByDesc('dependencia.dependencia')
        ->orderByDesc('idreemplazo')
        ->get()
        ->map->formatVisado($fechaFin, $costoLaboral)
        ->all();
    }

    public function getF2Visado(int $iddependencia, int $idperiodo) : array
    {
        //Ultimo período realizado
        $fechaFin = Reemplazo::getFechaPeriodoRealizado();

        $dependencia = Dependencia::firstWhere('iddependencia', $iddependencia);

        return $this->model
        ->with(['tipoNovedad', 'puestoReemplazado', 'puestoReemplazado.agente', 'puestoReemplazante', 'puestoReemplazante.agente',
        'puestoReemplazado.tipoNivel', 'puestoReemplazante.tipoNivel', 'puestoReemplazado.tipoFuncion', 'puestoReemplazante.tipoFuncion',
        'dependencia', 'tipoSolicitud', 'tipoHorario', 'puestoReemplazado.horarios', 'puestoReemplazado.horario_historico'])
        ->where('idformulario', 3)
        ->whereIn('iddependencia', $dependencia->getIdsDescendencia())
        ->where('idperiodo', $idperiodo)
        // ->orderByDesc('dependencia.dependencia')
        ->orderByDesc('idreemplazo')
        ->get()
        ->map->formatVisado($fechaFin)
        ->all();
    }

    public function visar(int $idreemplazo, string $nuevoEstado)
    {
        $visados = [
            'aprobar' => 'App\Models\ReemplazoAprobado',
            'desaprobar' => 'App\Models\ReemplazoDesaprobado',
            'desvisar' => 'App\Models\ReemplazoDesvisado',
        ];

        if (! array_key_exists($nuevoEstado, $visados)) {
            throw new Exception("No se encontró el nuevo estado para el formulario");
        }

        return (new $visados[$nuevoEstado]($idreemplazo))->visar();
    }
}
