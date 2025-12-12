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
            'seat_class' => 'required|in:economy,business',
            'seat_number' => 'required|string'
        ]);

        // Verificar que el asiento no esté ya reservado (activo/aprobado)
        $seatTaken = Reservation::where('flight_id', $request->flight_id)
            ->where('seat_class', $request->seat_class)
            ->where('seat_number', $request->seat_number)
            ->whereIn('status', ['pending', 'approved', 'confirmed'])
            ->first();

        if ($seatTaken) {
            return response()->json([
                'message' => 'Asiento ya reservado'
            ], 409);
        }

        // Obtener el vuelo para calcular el precio
        $flight = \App\Models\Flight::find($request->flight_id);
        $price = $request->seat_class === 'economy' ? $flight->economy_price : $flight->business_price;

        $reserva = Reservation::create([
            'user_id' => Auth::id(),
            'flight_id' => $request->flight_id,
            'seat_class' => $request->seat_class,
            'seat_number' => $request->seat_number,
            'status' => 'pending'
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