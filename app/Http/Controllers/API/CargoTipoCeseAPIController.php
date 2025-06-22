<?php
namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoTipoCeseAPIRequest;
use App\Http\Requests\API\UpdateCargoTipoCeseAPIRequest;
use App\Repositories\CargoTipoCeseRepository;
use App\Http\Controllers\AppBaseController;

/**
 * Class CargoTipoCeseController
 * @package App\Http\Controllers\API
 */

class CargoTipoCeseAPIController extends AppBaseController
{
    /** @var  CargoTipoCeseRepository */
    private $cargoTipoCeseRepository;

    public function __construct(CargoTipoCeseRepository $cargoTipoCeseRepo)
    {
        $this->cargoTipoCeseRepository = $cargoTipoCeseRepo;
    }

    public function index()
    {
        return $this->sendResponse($this->cargoTipoCeseRepository->toArray(), 'Listado');
    }

    public function store(CreateCargoTipoCeseAPIRequest $request)
    {
        $input = $request->all();
        $input['agente_reemplazado'] = (bool) $input['agenteReemplazado'] ? true : false;
        $cargoTipoCese = $this->cargoTipoCeseRepository->create($input);

        return $this->sendResponse($cargoTipoCese->idcargo_tipo_cese, 'Se creó correctamente');
    }

    public function update($id, UpdateCargoTipoCeseAPIRequest $request)
    {
        $input = $request->all();
        $input['agente_reemplazado'] = (bool) $input['agenteReemplazado'] ? true : false;
        $cargoTipoCese = $this->cargoTipoCeseRepository->find($id);

        if (empty($cargoTipoCese)) {
            return $this->sendError('Registro no encontrado.');
        }

        $cargoTipoCese = $this->cargoTipoCeseRepository->update($input, $id);

        return $this->sendResponse($cargoTipoCese->idcargo_tipo_cese, 'Se actualizó correctamente');
    }

    public function destroy($id)
    {
        $cargoTipoCese = $this->cargoTipoCeseRepository->find($id);

        if (empty($cargoTipoCese)) {
            return $this->sendError('Registro no encontrado');
        }

        $cargoTipoCese->delete();

        return $this->sendResponse($id, 'Se borró correctamente');
    }

    public function show(int $id)
    {
        $cargoTipoCese = $this->cargoTipoCeseRepository->findToArray($id);

        return $this->sendResponse($cargoTipoCese, 'Cargo Tipo Cese');
    }
}
