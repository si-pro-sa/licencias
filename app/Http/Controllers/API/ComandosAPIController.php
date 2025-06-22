<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Artisan;
use Response;

/**
 * Class ComandosController
 * @package App\Http\Controllers\API
 */
class ComandosAPIController extends AppBaseController
{
    public function clearConfig()
    {
        Artisan::call("config:clear");
        return $this->sendResponse([Artisan::output()], 'Comando "config:clear" Ejecutado', 200);
    }

    public function cacheConfig()
    {
        Artisan::call("config:cache");
        return $this->sendResponse([Artisan::output()], 'Comando "config:cache" Ejecutado', 200);
    }

    public function clearRoutes()
    {
        Artisan::call("route:clear");
        return $this->sendResponse([Artisan::output()], 'Comando "route:clear" Ejecutado', 200);
    }

    public function controlPermiso()
    {
        return $this->sendResponse([
            'CONTROL_PERMISOS' => env('CONTROL_PERMISOS'),
            'MAIL_DRIVER' => env('MAIL_DRIVER'),
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
        ], 'Estado Control Permiso', 200);
    }
}
