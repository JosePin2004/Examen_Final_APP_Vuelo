<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\FlightController;

// Ruta pública para ver el catálogo de vuelos
Route::get('/flights', [FlightController::class, 'index']);

use App\Http\Controllers\Api\AuthController;

// Rutas Públicas de Autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas (Solo usuarios logueados pueden entrar aquí)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Aquí pondremos las reservas más adelante
});