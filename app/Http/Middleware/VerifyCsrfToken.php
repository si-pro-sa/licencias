<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     *
     * Modificado por Alvaro Fraga y debe ser modificado de nuevo cuando se pueda guardar el token
     */
    protected $except = [
        ''
    ];
}
