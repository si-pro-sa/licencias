<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCargoTipoHorarioAPIRequest;
use App\Http\Requests\API\UpdateCargoTipoHorarioAPIRequest;
use App\Models\CargoTipoHorario;
use App\Repositories\CargoTipoHorarioRepository;
use App\Tables\Builders\CargoTipoHorarioTable;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CargoTipoHorarioController
 * @package App\Http\Controllers\API
 */

class CargoTipoHorarioAPIController extends AppBaseController
{
    use Datatable,Excel;
    protected $tableClass = CargoTipoHorarioTable::class;

    /** @var  CargoTipoHorarioRepository */
    private $cargoTipoHorarioRepository;

    public function __construct(CargoTipoHorarioRepository $cargoTipoHorarioRepo)
    {
        $this->cargoTipoHorarioRepository = $cargoTipoHorarioRepo;
    }

    /**
     * Display a listing of the CargoTipoHorario.
     * GET|HEAD /cargoTipoHorarios
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->cargoTipoHorarioRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoTipoHorarioRepository->pushCriteria(new LimitOffsetCriteria($request));
        $cargoTipoHorarios = $this->cargoTipoHorarioRepository->all();

        return $this->sendResponse($cargoTipoHorarios->toArray(), 'Cargo Tipo Horarios retrieved successfully');
    }

    /**
     * Display a listing of the Cargo.
     * POST|HEAD /cargos-table-init
     *
     * @return Response
     */
    public function init()
    {
        $response = (new $this->tableClass())
            ->init();
        $response['template']->readPath = env('APP_URL').$response['template']->readPath;
        $response['message'] = 'Se ha validado correctamente la configuración de la tabla';
        return $response;
    }

    /**
     * Display a listing of the Cargo.
     * POST|HEAD /cargos/tableData
     *
     * @param Request $request
     * @return Response
     */
    public function table(Request $request)
    {
        $input = $request->all();
        $this->cargoTipoHorarioRepository->pushCriteria(new RequestCriteria($request));
        $this->cargoTipoHorarioRepository->pushCriteria(new LimitOffsetCriteria($request));
//        relaciones que devuelve
//        $this->$this->cargoTipoHorarioRepository->with('agenteReemplazante');
//        scope desde repositorio
        if($request->get('searchParam')['tipohorario'] != null)
            $cargoTipoHorario = $this->cargoTipoHorarioRepository->tipohorario($request->get('searchParam')['tipohorario']);

        $cargoTipoHorario = $this->cargoTipoHorarioRepository->paginate(15);

        return $this->sendResponse($cargoTipoHorario->toArray(), 'Cargos retrieved successfully');
    }

    /**
     * Store a newly created CargoTipoHorario in storage.
     * POST /cargoTipoHorarios
     *
     * @param CreateCargoTipoHorarioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCargoTipoHorarioAPIRequest $request)
    {
        $input = $request->all();

        $cargoTipoHorario = $this->cargoTipoHorarioRepository->create($input);

        return $this->sendResponse($cargoTipoHorario->toArray(), 'Cargo Tipo Horario se ha guardado correctamente');
    }

    /**
     * Display the specified CargoTipoHorario.
     * GET|HEAD /cargoTipoHorarios/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CargoTipoHorario $cargoTipoHorario */
        $cargoTipoHorario = $this->cargoTipoHorarioRepository->findWithoutFail($id);

        if (empty($cargoTipoHorario)) {
            return $this->sendError('Cargo Tipo Horario not found');
        }

        return $this->sendResponse($cargoTipoHorario->toArray(), 'Cargo Tipo Horario retrieved successfully');
    }

    /**
     * Update the specified CargoTipoHorario in storage.
     * PUT/PATCH /cargoTipoHorarios/{id}
     *
     * @param  int $id
     * @param UpdateCargoTipoHorarioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCargoTipoHorarioAPIRequest $request)
    {
        $input = $request->all();

        /** @var CargoTipoHorario $cargoTipoHorario */
        $cargoTipoHorario = $this->cargoTipoHorarioRepository->findWithoutFail($id);

        if (empty($cargoTipoHorario)) {
            return $this->sendError('Cargo Tipo Horario not found');
        }

        $cargoTipoHorario = $this->cargoTipoHorarioRepository->update($input, $id);

        return $this->sendResponse($cargoTipoHorario->toArray(), 'CargoTipoHorario se ha actualizado correctamente');
    }

    /**
     * Remove the specified CargoTipoHorario from storage.
     * DELETE /cargoTipoHorarios/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CargoTipoHorario $cargoTipoHorario */
        $cargoTipoHorario = $this->cargoTipoHorarioRepository->findWithoutFail($id);

        if (empty($cargoTipoHorario)) {
            return $this->sendError('Cargo Tipo Horario not found');
        }

        $cargoTipoHorario->delete();

        return $this->sendResponse($id, 'Cargo Tipo Horario se ha eliminado correctamente');
    }
}
