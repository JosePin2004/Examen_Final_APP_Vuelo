<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Reservation;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    // 1. LISTAR VUELOS (Público)



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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        try {
            $imageUrl = null;

            // Si hay archivo de imagen, subirlo a Firebase
            if ($request->hasFile('image') && FirebaseService::isConfigured()) {
                try {
                    $firebase = new FirebaseService();
                    $imageUrl = $firebase->uploadImage($request->file('image'), 'vuelos');
                } catch (\Exception $e) {
                    Log::warning('No se pudo subir imagen a Firebase: ' . $e->getMessage());
                    // Continuar sin imagen si falla Firebase
                }
            }

            // Crear vuelo
            $flight = Flight::create([
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'price' => $request->price,
                'image_url' => $imageUrl,
            ]);

            return response()->json([
                'message' => 'Vuelo creado exitosamente',
                'data' => $flight
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creando vuelo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear vuelo'], 500);
        }
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ]);

        try {
            $imageUrl = $flight->image_url;

            // Si hay nueva imagen, subirla a Firebase
            if ($request->hasFile('image') && FirebaseService::isConfigured()) {
                try {
                    // Eliminar imagen anterior si existe
                    if ($flight->image_url) {
                        $firebase = new FirebaseService();
                        $firebase->deleteImage($flight->image_url);
                    }

                    $firebase = new FirebaseService();
                    $imageUrl = $firebase->uploadImage($request->file('image'), 'vuelos');
                } catch (\Exception $e) {
                    Log::warning('Error al actualizar imagen en Firebase: ' . $e->getMessage());
                    // Mantener imagen anterior si falla Firebase
                }
            }

            // Actualizar vuelo
            $flight->update([
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_time' => $request->departure_time,
                'arrival_time' => $request->arrival_time,
                'price' => $request->price,
                'image_url' => $imageUrl,
            ]);

            return response()->json([
                'message' => 'Vuelo actualizado exitosamente',
                'data' => $flight
            ]);

        } catch (\Exception $e) {
            Log::error('Error actualizando vuelo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar vuelo'], 500);
        }
    }

    // 5. ELIMINAR VUELO (Solo Admin)
    public function destroy($id)
    {
        // Verificar que sea admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $flight = Flight::findOrFail($id);

        try {
            // Eliminar imagen de Firebase si existe
            if ($flight->image_url && FirebaseService::isConfigured()) {
                try {
                    $firebase = new FirebaseService();
                    $firebase->deleteImage($flight->image_url);
                } catch (\Exception $e) {
                    Log::warning('Error al eliminar imagen de Firebase: ' . $e->getMessage());
                    // Continuar con eliminación del vuelo aunque falle la imagen
                }
            }

            // Cancelar todas las reservas de este vuelo
            Reservation::where('flight_id', $id)
                ->where('status', '!=', 'cancelled')
                ->update(['status' => 'cancelled']);

            // Eliminar el vuelo
            $flight->delete();

            return response()->json([
                'message' => 'Vuelo eliminado y reservas canceladas'
            ]);

        } catch (\Exception $e) {
            Log::error('Error eliminando vuelo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar vuelo'], 500);
        }
    }
}
