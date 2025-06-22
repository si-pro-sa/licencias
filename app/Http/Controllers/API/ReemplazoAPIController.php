<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\DataTables\ReemplazoDataTable;
use App\Repositories\ReemplazoRepository;
use App\Http\Controllers\AppBaseController;

class ReemplazoAPIController extends AppBaseController
{
    /** @var  ReemplazoRepository */
    private $reemplazoRepository;

    public function __construct(ReemplazoRepository $reemplazoRepo)
    {
        $this->reemplazoRepository = $reemplazoRepo;
    }

    public function getReemplazosAgenteReemplazado(int $agente)
    {
        $forms = $this->reemplazoRepository->getReemplazosAgente($agente);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Datos de Reemplazos de Agente');
        }
        return $this->sendError('Reemplazos no encontrados');
    }

    public function getReemplazosAgenteReemplazante(int $agente)
    {

        $forms = $this->reemplazoRepository->getReemplazosAgente($agente, true);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Datos de Reemplazos de Agente');
        }
        return $this->sendError('Reemplazos no encontrados');
    }
    public function getReemplazosAgenteReemplazanteDni(int $agente)
    {

        $forms = $this->reemplazoRepository->getReemplazosAgenteDni($agente, true);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Datos de Reemplazos de Agente');
        }
        return $this->sendError('Reemplazos no encontrados');
    }
    public function getF1Visado()
    {
        $forms = $this->reemplazoRepository->getF1Visado();
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Reemplazos para visado');
        }
        return $this->sendError('Reemplazos no encontrados');
    }

    public function getF1VisadoUO(int $iddependencia)
    {
        $forms = $this->reemplazoRepository->getF1VisadoUO($iddependencia);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Reemplazos para visado');
        }
        return $this->sendError('Reemplazos no encontrados');
    }

    public function getF2Visado(int $iddependencia, int $idperiodo)
    {
        $forms = $this->reemplazoRepository->getF2Visado($iddependencia, $idperiodo);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Reemplazos para visado');
        }
        return $this->sendError('Reemplazos no encontrados');
    }

    /**
    * Process datatables ajax request.
    *
    * @return \Illuminate\Http\JsonResponse
    */
    public function getReemplazosDataTable(ReemplazoDataTable $dataTable, $tipoFormulario = 'f1')
    {
        return $dataTable->dataTable(request(), $tipoFormulario)->toJson();
    }

    public function visar(Request $request)
    {
        $inputs = $request->all();
        $result = $this->reemplazoRepository->visar($inputs['idreemplazo'], $inputs['nuevoEstado']);
        if (is_string($result)) {
            return $this->sendError($result);
        } elseif (is_bool($result) && $result) {
            return $this->sendResponse($result, 'El formulario cambio su estado correctamente.');
        }
        return $this->sendError('Ocurrió un error al intentar visar el Formulario');
    }
}
