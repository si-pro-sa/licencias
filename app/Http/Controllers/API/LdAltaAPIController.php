<?php

namespace App\Http\Controllers\API;

use App\Repositories\LdAltaRepository;
use App\Repositories\LdCambioEstadoRepository;
use App\Http\Controllers\AppBaseController;

class LdAltaAPIController extends AppBaseController
{
    /** @var  LdAltaRepository */
    private $ldAltaRepository;
    private $ldCambioEstadoRepository;

    public function __construct(LdAltaRepository $ldAltaRepo, LdCambioEstadoRepository $ldCambioEstadoRepo)
    {
        $this->ldAltaRepository = $ldAltaRepo;
        $this->ldCambioEstadoRepository = $ldCambioEstadoRepo;
    }

    public function getLDVigentes()
    {
        return $this->ldAltaRepository->getVigentes();
    }

    public function getLibresAgente(int $idpuesto)
    {
        $forms = $this->ldAltaRepository->getLibresAgente($idpuesto);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Datos de Libre Disponibilidad de Agente');
        }
        return $this->sendError('Libre Disponibilidades no encontradas');
    }

    public function fixLdContinuidades()
    {
        $forms = $this->ldCambioEstadoRepository->setFechaInicioContinuidades();
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Seteo de Fecha Desde de todas las continuidades');
        }
        return $this->sendError('Continuidades no encontradas');
    }
}
