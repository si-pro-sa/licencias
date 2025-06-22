<?php
namespace App\Repositories;

use App\Exports\HorariosFormulariosExport;
use App\Models\Puesto;
use App\Models\Periodo;
use App\Repositories\BaseRepository;
use App\Exports\PuestosTotalesExport;
use Illuminate\Support\Collection;

/**
 * Class PuestoRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:09 pm -03
 *
 * @method Puesto findWithoutFail($id, $columns = ['*'])
 * @method Puesto find($id, $columns = ['*'])
 * @method Puesto first($columns = ['*'])
*/
class PuestoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idpuesto',
        'iddependencia',
        'idagente',
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
        return Puesto::class;
    }

    public function getPuestosAgente(int $idagente): array
    {
        return $this->model
        ->with('puestosAdicionales')
        ->with('puestosAdicionales.dependencia')
        ->where('idagente', $idagente)
        ->orderByDesc('fhasta', 'fdesde')
        ->get()
        ->map->format()
        ->all();
    }

    public function getHorasMensualesAgente(int $idpuesto, $fecha = null)
    {
        $puesto = $this->model
        ->where('idpuesto', $idpuesto)
        ->first();

        if (isset($puesto)) {
            return $puesto->getCantidadHorasEnPeriodo($fecha);
        }
        return null;
    }

    // opciones tipoFormulario: reemplazos, libres, guardias, cargos
    public function superaHorasMensualesAgente(int $idpuesto, int $horasNuevas, int $horasMax, string $fechaDesde, $fechaHasta = null)
    {
        $puesto = $this->model
        ->where('idpuesto', $idpuesto)
        ->first();

        $periodos = Periodo::getIdsPeriodos($fechaDesde, $fechaHasta);
        foreach ($periodos['fechas'] as $fecha) {
            $horasMensuales = $puesto->getCantidadHorasEnPeriodo($fecha);
            if (isset($horasMensuales['total'])) {
                $total = $horasMensuales['total'] + $horasNuevas;
                if ($total > $horasMax) {
                    return "Error: El agente no puede tener más de 240 hs mensuales de trabajo. Con este formulario tendría un total de {$total} hs en el Período {$fecha}";
                }
            } else {
                return 'Error: No se pudo obtener las horas mensuales del agente.';
            }
        }

        return null;
    }

    public function getTotalPuestos()
    {
        $puestosFormateados = $this->model->with([
            'agente',
            'agente.tipoSexo',
            'tipoPlanta',
            'tipoFuncion',
            'tipoNivel',
            'tipoAgrupamiento',
            'dependencia',
            'puestosAdicionales',
            'puestosAdicionales.dependencia',
        ])
            ->get()
            ->map->formatTotalPuestos()
            ->all();

        ob_end_clean();
        ob_start();
        return (new PuestosTotalesExport($puestosFormateados))
            ->download('PuestosTotales_' . date('d-m-Y_H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function getTotalHorasFormularios($fecha)
    {
        $puestosFormateados = $this->model->with([
            'agente',
            // 'tipoPlanta',
            // 'tipoFuncion',
            // 'tipoNivel',
            // 'tipoAgrupamiento',
            // 'dependencia',
            // 'puestosAdicionales',
            // 'puestosAdicionales.dependencia',
            'ldAlta',
            'agente.reemplazos',
            'guardiaLineas'
        ])
            ->where('fhasta', null)
            ->where('idpuesto', '>', 50000)
            ->get()
            ->map->formatTotalHorasFormularios($fecha)
            ->all();

        ob_end_clean();
        ob_start();
        return (new HorariosFormulariosExport($puestosFormateados))
            ->download('HorasDeFormularios_' . date('d-m-Y_H:i:s') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function createPuesto($puesto) : Puesto
    {
        $newPuesto = new Puesto();
        $newPuesto->idagente = $puesto->idagente;
        $newPuesto->iddependencia = $puesto->iddependencia;
        $newPuesto->idtipo_agrupamiento = $puesto->idtipo_agrupamiento;
        $newPuesto->idtipo_funcion = $puesto->idtipo_funcion;
        $newPuesto->idtipo_nivel = $puesto->idtipo_nivel;
        $newPuesto->idtipo_planta = $puesto->idtipo_planta;
        $newPuesto->fdesde = $puesto->fdesde;
        $newPuesto->fhasta = $puesto->fhasta;
        $newPuesto->usuario = auth()->user()->usuario;
        $newPuesto->foperacion = date("Y-m-d H:i:s");
        $newPuesto->saveOrFail();

        return $newPuesto;
    }
}
