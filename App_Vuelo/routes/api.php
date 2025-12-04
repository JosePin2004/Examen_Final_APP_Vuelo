<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\Api\FlightController;

// Ruta pública para ver el catálogo de vuelos
Route::get('/flights', [FlightController::class, 'index']);