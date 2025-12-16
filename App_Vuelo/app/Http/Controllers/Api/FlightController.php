<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Reservation;
use App\Services\FirebaseService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FlightController extends Controller
{
    //


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
        // 1. Verificar admin
        if (Auth::user()->role !== 'admin') {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    // 2. Validar datos
    $request->validate([
        'origin' => 'required|string|max:100',
        'destination' => 'required|string|max:100',
        'departure_time' => 'required|date_format:Y-m-d\TH:i',
        // Nota:El nombre del campo debe coincidir con el HTML (departure_time vs departure_date)
        'arrival_time' => 'required|date_format:Y-m-d\TH:i', 
        'price' => 'nullable|numeric|min:0.01',
        'economy_price' => 'required|numeric|min:0.01',
        'business_price' => 'required|numeric|min:0.01',
        
        // CAMBIO CLAVE: No validamos imagen como archivo, sino la URL como texto
        'image_url' => 'nullable|string' 
    ]);

    try {
        // 3. Crear vuelo usando la URL que llega del Frontend
        $flight = Flight::create([
            'origin' => $request->origin,
            'destination' => $request->destination,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'price' => $request->price ?? $request->economy_price,
            'economy_price' => $request->economy_price,
            'business_price' => $request->business_price,
            
            // AQUÍ ESTÁ LO IMPORTANTE: Guardamos lo que el JS puso en el input hidden
            'image_url' => $request->image_url, 
        ]);

        return response()->json([
            'message' => 'Vuelo creado exitosamente',
            'data' => $flight
        ], 201);

    } catch (\Exception $e) {
        // Log::error('Error creando vuelo: ' . $e->getMessage());
        return response()->json(['message' => 'Error al crear vuelo: ' . $e->getMessage()], 500);
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
            'price' => 'nullable|numeric|min:0.01',
            'economy_price' => 'required|numeric|min:0.01',
            'business_price' => 'required|numeric|min:0.01',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
            'image_url' => 'nullable|string',
        ]);

        try {
            $imageUrl = $flight->image_url;

            // Si viene image_url desde el formulario (nueva subida), usarla
            if ($request->has('image_url') && $request->image_url) {
                $imageUrl = $request->image_url;
            }
            // Si hay nueva imagen, subirla a Firebase
            elseif ($request->hasFile('image') && FirebaseService::isConfigured()) {
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
                'price' => $request->price ?? $request->economy_price,
                'economy_price' => $request->economy_price,
                'business_price' => $request->business_price,
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

    // 6. OBTENER CLIMA DEL DESTINO (Público)
    public function weather($id, WeatherService $weatherService)
    {
        $flight = Flight::findOrFail($id); // Obtener vuelo

        $city = $flight->destination; // Usar destino del vuelo
        $weather = $weatherService->getCurrentByCity($city); // Obtener clima: esto proceso se realiza en el servicio

        if (!$weather) { // Si falla, retornar error
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el clima. Verifique OPENWEATHER_API_KEY.',
            ], 503);
        }

        return response()->json([  // si funciona, retornar datos
            'success' => true,
            'city' => $city,
            'data' => $weather,
        ]);
    }

    // 7. SUBIR IMAGEN A FIREBASE/LOCAL (Solo Admin)
    public function uploadImage(Request $request)
    {
        if (Auth::user()->role !== 'admin') {  // Verificar que sea admin
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([ // Validar imagen
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {   
            $imageUrl = null; 
            $error = null;

            // Intentar subir a Firebase primero
            if (FirebaseService::isConfigured()) { 
                try {
                    $firebase = new FirebaseService(); // verifica configuracion de firebase
                    $imageUrl = $firebase->uploadImage($request->file('image'), 'vuelos'); // Subir imagen
                    Log::info('Image uploaded to Firebase: ' . $imageUrl); // exito
                } catch (\Exception $firebaseError) {
                    Log::warning('Firebase error: ' . $firebaseError->getMessage()); // fallo
                    $error = $firebaseError->getMessage();
                    
                    // Fallback: Guardar localmente
                    try {
                        $file = $request->file('image'); // Obtener archivo
                        $filename = 'vuelos/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension(); // Crear nombre unico
                        $path = $file->storePublicly($filename, 'public'); // Guardar en storage/app/public/vuelos
                        $imageUrl = asset('storage/' . $path); // Crear URL 
                        Log::info('Image saved locally (Firebase failed): ' . $imageUrl); // exito 
                    } catch (\Exception $localError) {
                        Log::error('Local storage error: ' . $localError->getMessage()); // fallo
                    }
                }
            } else {
                // Firebase no configurado, guardar localmente lo mismo qu el fallback
                try {
                    $file = $request->file('image');
                    $filename = 'vuelos/' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storePublicly($filename, 'public');
                    $imageUrl = asset('storage/' . $path);
                    Log::info('Image saved locally (Firebase not configured): ' . $imageUrl);
                } catch (\Exception $localError) {
                    Log::error('Local storage error: ' . $localError->getMessage());
                }
            }

            if ($imageUrl) {  // Si se pudo subir la imagen
                return response()->json([
                    'success' => true,
                    'image_url' => $imageUrl,
                    'message' => 'Imagen subida exitosamente',
                    'source' => strpos($imageUrl, 'storage.googleapis.com') ? 'firebase' : 'local' // Indicar origen
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo subir la imagen: ' . ($error || 'Error desconocido') // Mostrar error
                ], 500);
            }

        } catch (\Exception $e) {  // En caso de error
            Log::error('Error subiendo imagen: ' . $e->getMessage());
            return response()->json([  
                'success' => false,
                'message' => 'Error al subir imagen: ' . $e->getMessage()
            ], 500);
        }
    }
}
