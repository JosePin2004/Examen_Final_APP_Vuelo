<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    // 1. LISTAR VUELOS (Público)
    public function index()
    {
        // Traemos todos los vuelos de la base de datos
        $flights = Flight::all();
        
        // Devolvemos la respuesta en formato JSON (lo que pide el sílabo)
        return response()->json([
            'success' => true,
            'data' => $flights,
        ], 200);
    }

    // 2. CREAR VUELO (Solo Admin - Lo haremos más adelante)
    public function store(Request $request)
    {
        // ...
    }
}