<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Esta ruta es una vista estática
        // La validación real ocurre en el JavaScript del admin.blade.php 
        // Si el usuario no tiene token válido o no es admin, será redirigido por JavaScript
        //Es decir la validacion nose hace en el backend sino en el frontend
        return $next($request); 
    }
}
