<?php
namespace App\Http\Controllers\API;

use App\Models\ValidacionHorasMaximas;
use App\Repositories\PuestoRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PuestoAdicionalRepository;
use App\Http\Requests\CreatePuestoAdicionalRequest;
use App\Http\Requests\CreatePuestoAperturaRequest;
use App\Models\Puesto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// use Prettus\Repository\Criteria\RequestCriteria;

class PuestoAPIController extends AppBaseController
{
    /** @var  PuestoRepository */
    private $puestoRepository;

    public function __construct(PuestoRepository $puestoRepo, PuestoAdicionalRepository $puestoAdicionalRepo)
    {
        $this->puestoRepository = $puestoRepo;
        $this->puestoAdicionalRepository = $puestoAdicionalRepo;
    }

    public function getPuestosAgente(int $idagente)
    {
        $puestos = $this->puestoRepository->getPuestosAgente($idagente);
        if (isset($puestos)) {
            return $this->sendResponse($puestos, 'Datos de Puestos de Agente');
        }
        return $this->sendError('Puestos no encontradas');
    }

    public function getHorasMensualesAgente(int $idpuesto, $fecha = null)
    {
        $horario = $this->puestoRepository->getHorasMensualesAgente($idpuesto, $fecha);
        if (isset($horario)) {
            return $this->sendResponse($horario, 'Datos de Horario de Agente');
        }
        return $this->sendError('Horarios no encontradas');
    }

    // opciones tipoFormulario: reemplazos, libres, guardias, cargos
    public function agenteSuperaLimiteHorasMensuales(int $idagente, string $tipoFormulario, int $horasNuevas, string $fechaDesdeNueva, ?string $fechaHastaNueva = null, int $idtipo_campania = 0, int $idtipo_guardia = 0)
    {
        $agenteSuperaHorasMensuales = (new ValidacionHorasMaximas(
            $idagente,
            $tipoFormulario,
            $fechaDesdeNueva,
            $fechaHastaNueva,
            $horasNuevas,
            $idtipo_campania,
            $idtipo_guardia
        ))->agenteSuperaLimiteHorasMensuales();

        if (isset($agenteSuperaHorasMensuales) && $agenteSuperaHorasMensuales !== false) {
            return $this->sendResponse(true, $agenteSuperaHorasMensuales);
        } else {
            return $this->sendResponse(false, 'El Agente está dentro de los límites horarios.');
        }
    }

    public function store(CreatePuestoAdicionalRequest $request)
    {
        $puesto = (bool) $this->puestoAdicionalRepository->create($request->all());

        if ($puesto) {
            return $this->sendResponse($puesto, 'El Puesto Adicional fue creado correctamente.');
        }
        return $this->sendError('Ocurrió un error al crear el puesto adicional');
    }

    // nuevo metodo para apertura puesto reducido
    public function storePuesto(CreatePuestoAperturaRequest $request) 
    {
        $request->validate([
            'fdesde' => "required",
            'iddependencia' => "required"
        ]);

        $puesto = $this->puestoRepository->createPuesto(puesto: $request);
        if ($puesto) {
            return $this->sendResponse($puesto, 'El puesto fue creado correctamente.', 200);
        }
        return $this->sendError('Ocurrió un error al crear el puesto.');
    }

    public function getPuestosAdicionalesAgente(int $idpuesto)
    {
        $puestos = $this->puestoAdicionalRepository->getPuestosAdicionales($idpuesto);
        if (isset($puestos)) {
            return $this->sendResponse($puestos, 'Datos de Puestos Adicionales de Agente');
        }
        return $this->sendError('Puestos Adicionales no encontrados');
    }

    public function destroy($id)
    {
        $id = (int) $id;
        $puestoAdicional = $this->puestoAdicionalRepository->find($id);

        if (!isset($puestoAdicional)) {
            return $this->sendError('No se encontró el Puesto Adicional');
        }

        if ($puestoAdicional->delete()) {
            return $this->sendResponse($id, 'Se borró el Puesto Adicional exitosamente');
        }

        return $this->sendError('Ocurrió un error al borrar el Puesto Adicional. Intente nuevamente');
    }

    public function getTotalPuestos()
    {
        $puestos = $this->puestoRepository->getTotalPuestos();
        if (isset($puestos)) {
            return $puestos;
        }
        return $this->sendError('Puestos no encontradas');
    }

    public function getTotalHorasFormularios($fecha = null)
    {
        $puestos = $this->puestoRepository->getTotalHorasFormularios($fecha);
        if (isset($puestos)) {
            return $puestos;
        }
        return $this->sendError('Puestos no encontradas');
    }
}
