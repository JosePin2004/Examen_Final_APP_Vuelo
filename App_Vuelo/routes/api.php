<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController;

// Rutas Públicas
Route::get('/flights', [FlightController::class, 'index']);
Route::get('/flights/{id}', [FlightController::class, 'show']);
Route::get('/flights/{id}/weather', [FlightController::class, 'weather']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Obtener asientos reservados de un vuelo
Route::get('/flights/{flightId}/reserved-seats', function ($flightId) {
    $reservedSeats = \App\Models\Reservation::where('flight_id', $flightId)
        ->whereIn('status', ['pending', 'approved', 'confirmed'])
        ->pluck('seat_number')
        ->toArray();
    
    return response()->json([
        'reserved_seats' => $reservedSeats
    ]);
});

// Rutas Protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Reservaciones (Usuario normal)
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    // Vuelos Admin - CRUD completo
    Route::post('/flights', [FlightController::class, 'store']);
    Route::put('/flights/{id}', [FlightController::class, 'update']);
    Route::delete('/flights/{id}', [FlightController::class, 'destroy']);

    // Admin endpoints
    Route::get('/admin/reservations', function () {
        return response()->json([
            'data' => \App\Models\Reservation::with(['user', 'flight'])->latest()->get()
        ]);
    });

    Route::get('/users/count', function () {
        return response()->json([
            'count' => \App\Models\User::count()
        ]);
    });
});