<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Flight; // Importamos el modelo de Vuelos

class ReservationController extends Controller
{
    // 1. LISTAR (Ya lo tenías)
    public function index()
    {
        $reservas = Reservation::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json($reservas);
    }

    // 2. CREAR NUEVA RESERVA (Nuevo)
    public function store(Request $request)
    {
        // Validamos que envíen un ID de vuelo y que ese vuelo EXISTA en la tabla flights
        $request->validate([
            'flight_id' => 'required|exists:flights,id', 
        ]);

        // Verificar que el usuario no tenga ya una reserva activa para este vuelo
        $existingReservation = Reservation::where('user_id', Auth::id())
            ->where('flight_id', $request->flight_id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingReservation) {
            return response()->json([
                'message' => 'Ya tienes una reserva activa para este vuelo'
            ], 409);
        }

        $reserva = Reservation::create([
            'user_id' => Auth::id(), // El usuario logueado
            'flight_id' => $request->flight_id,
            'status' => 'pending' // Por defecto
        ]);

        return response()->json([
            'message' => 'Reserva creada con éxito',
            'data' => $reserva
        ], 201);
    }

    // 3. ACTUALIZAR ESTADO RESERVA (Admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled'
        ]);

        $reserva = Reservation::findOrFail($id);
        $reserva->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Estado actualizado correctamente',
            'data' => $reserva
        ]);
    }

    // 4. CANCELAR/BORRAR RESERVA (Usuario normal)
    public function destroy($id)
    {
        // Buscamos la reserva, asegurándonos que pertenezca al usuario logueado (seguridad)
        $reserva = Reservation::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$reserva) {
            return response()->json(['message' => 'Reserva no encontrada o no te pertenece'], 404);
        }

        // Cambiar status a 'cancelled' en lugar de eliminar
        $reserva->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Reserva cancelada correctamente']);
    }
}