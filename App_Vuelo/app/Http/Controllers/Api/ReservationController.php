<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    // 1. LISTAR RESERVAS
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $reservations = Reservation::with(['user', 'flight'])->get();
        } else {
            $reservations = Reservation::with(['flight'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
    }

    // 2. CREAR RESERVA (Esta es la función que te faltaba)
    public function store(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'comments' => 'nullable|string'
        ]);

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'flight_id' => $request->flight_id,
            'status' => 'pending',
            'comments' => $request->comments
        ]);

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'data' => $reservation
        ], 201);
    }
    
    // 3. ACTUALIZAR ESTADO (Admin)
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Estado actualizado',
            'data' => $reservation
        ]);
    }
}