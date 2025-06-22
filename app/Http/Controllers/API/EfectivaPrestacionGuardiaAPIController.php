<?php
namespace App\Http\Controllers\API;

use App\Repositories\EfectivaPrestacionGuardiaRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\ExportEPGuardias;

class EfectivaPrestacionGuardiaAPIController extends AppBaseController
{
    /** @var  EfectivaPrestacionGuardiaRepository */
    private $epGuardiaRepository;

    public function __construct(EfectivaPrestacionGuardiaRepository $epGuardiaRepo)
    {
        $this->epGuardiaRepository = $epGuardiaRepo;
    }

    public function exportarEfectivaPrestacionAdelia(int $idperiodo)
    {
        $epGuardias = $this->epGuardiaRepository->exportarEfectivaPrestacionAdelia($idperiodo);
        if (isset($epGuardias) && count($epGuardias) > 0) {
            $exportEPGuardias = new ExportEPGuardias($idperiodo, 'ep_guardia', $epGuardias);
            $exportEPGuardias->transformarAFormatoAdelia();
            return $exportEPGuardias->export();
        }
        return $this->sendError('ep guardias no encontradas');
    }
}
