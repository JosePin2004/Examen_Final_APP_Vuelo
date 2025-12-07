<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlightController extends Controller
{
    // 1. LISTAR VUELOS (Público)
    public function index()
    {
        $flights = Flight::all();
        return response()->json([
            'success' => true,
            'data' => $flights,
        ], 200);
    }

    // 2. VER UN VUELO ESPECÍFICO
    public function show($id)
    {
        $flight = Flight::findOrFail($id);
        return response()->json([
            'data' => $flight
        ]);
    }

    // 3. CREAR VUELO (Solo Admin)
    public function store(Request $request)
    {
        // Verificar que sea admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Validar datos
        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0.01',
            'image_url' => 'nullable|url',
        ]);

        // Crear vuelo
        $flight = Flight::create([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'price' => $request->price,
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'message' => 'Vuelo creado exitosamente',
            'data' => $flight
        ], 201);
    }

    // 4. ACTUALIZAR VUELO (Solo Admin)
    public function update(Request $request, $id)
    {
        // Verificar que sea admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $flight = Flight::findOrFail($id);

        // Validar datos
        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'price' => 'required|numeric|min:0.01',
            'image_url' => 'nullable|url',
        ]);

        // Actualizar vuelo
        $flight->update([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'price' => $request->price,
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'message' => 'Vuelo actualizado exitosamente',
            'data' => $flight
        ]);
    }

    // 5. ELIMINAR VUELO (Solo Admin)
    public function destroy($id)
    {
        // Verificar que sea admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $flight = Flight::findOrFail($id);

        // Cancelar todas las reservas de este vuelo
        Reservation::where('flight_id', $id)
            ->where('status', '!=', 'cancelled')
            ->update(['status' => 'cancelled']);

        // Eliminar el vuelo
        $flight->delete();

        return response()->json([
            'message' => 'Vuelo eliminado y reservas canceladas'
        ]);
    }
}
