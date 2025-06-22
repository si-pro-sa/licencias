<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class PermisosController
 * @package App\Http\Controllers\API
 */
class PermisosAPIController extends AppBaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function tienePermiso(Request $request)
    {
        $tienePermiso = User::tienePermiso($request->get('permiso'));
        return $this->sendResponse($tienePermiso, 'Permiso', 200);
    }
}
