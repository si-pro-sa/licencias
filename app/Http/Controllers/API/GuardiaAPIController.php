<?php
namespace App\Http\Controllers\API;

use App\Repositories\GuardiaLineaRepository;
use App\Http\Controllers\AppBaseController;

class GuardiaAPIController extends AppBaseController
{
    /** @var  GuardiaLineaRepository */
    private $guardiaRepository;

    public function __construct(GuardiaLineaRepository $guardiaRepo)
    {
        $this->guardiaRepository = $guardiaRepo;
    }

    public function getGuardiasAgente(int $idagente)
    {
        $forms = $this->guardiaRepository->getGuardiasAgente($idagente);
        if (isset($forms)) {
            return $this->sendResponse($forms, 'Datos de Guardia de Agente');
        }
        return $this->sendError('guardias no encontradas');
    }
}
