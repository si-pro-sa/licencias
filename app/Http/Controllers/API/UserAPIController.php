<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\Puesto;
use App\Models\User;
use App\Models\Dependencia;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Support\Facades\Log;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $this->userRepository->pushCriteria(new LimitOffsetCriteria($request));
        $users = $this->userRepository->all();

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();

        $users = $this->userRepository->create($input);

        return $this->sendResponse($users->toArray(), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param  int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendResponse($id, 'User deleted successfully');
    }

    public function searchUsuario(Request $request)
    {
        $usuarios = $this->userRepository->with('agente')->getUsuario($request->get('usuario'));

        if (empty($usuarios)) {
            return $this->sendError('Usuario not found');
        }

        return $this->sendResponse($usuarios->toArray(), 'Usuarios retrieved successfully', 200);
    }

    /**
     *  Devuelve la coleccion de permisos asociados
     *
     * GET /api/user/permisos
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function permisos(Request $request)
    {
        $user = $request->user();
        $permisos = $user->permisosLicencias();
        return response()->json($permisos);
    }

    /**
     * Llama a Laratrust para acceder al facilitador can para permisos
     *
     * GET /api/user/permisos/{$cadena}
     *
     * @param Request $request
     * @param $cadena
     * @return \Illuminate\Http\JsonResponse
     */
    public function permiso(Request $request, $cadena)
    {
        $user = $request->user();
        $permiso = $user->can($cadena);
        return response()->json($permiso);
    }


    /**
     * Buscar que el puesto es valido
     *
     * GET /api/user/puestoValidado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function puestoValidado(Request $request)
    {
        Log::info($request->all());
        Log::info($request->user());
        $user = $request->user();
        $idagente = $user->idagente;
        $puesto = Puesto::where('puesto.fhasta', '=', null)
            ->where('puesto.idagente', '=', $idagente)
            ->first();

        return response()->json($puesto === null ? false : true);
    }

    public function getDependenciasVisibles(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return $user->getDependenciasVisible();
    }
}
