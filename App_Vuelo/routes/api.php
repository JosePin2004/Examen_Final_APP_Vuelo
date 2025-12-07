<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController; // 1. ¡IMPORTANTE: ESTA LÍNEA DEBE ESTAR ARRIBA!

// Rutas Públicas
Route::get('/flights', [FlightController::class, 'index']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas Protegidas (Aquí es donde faltaba código)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // 2. AGREGA ESTAS LÍNEAS SI NO ESTÁN:
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
});