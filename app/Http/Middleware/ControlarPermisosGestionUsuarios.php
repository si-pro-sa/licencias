<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Response;

class ControlarPermisosGestionUsuarios
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso = null)
    {
        if (isset($permiso) && !User::tienePermiso($permiso)) {
            $stringPermiso = '';
            if (auth()->user()->isRRHH()) {
                $stringPermiso = " {$permiso} en SIARHUV2";
            }
            return Response::json([
                'success' => false,
                'message' => "El usuario no posee permisos.{$stringPermiso}"
            ], 403);
        }
        return $next($request);
    }
}
