<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. REGISTRO DE USUARIO
    public function register(Request $request)
    {
        // Validamos que los datos vengan bien
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        // Creamos el usuario en la base de datos
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Por defecto todos son usuarios normales
        ]);

        // Devolvemos el token de acceso para que pueda navegar
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 201);
    }

    // 2. INICIO DE SESIÓN (LOGIN)
    public function login(Request $request)
    {
        // Intentamos autenticar con email y contraseña
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas (Email o Password mal)'
            ], 401);
        }

        // Si pasa, buscamos al usuario y le damos un token
        $user = User::where('email', $request->email)->firstOrFail();

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $user->createToken('API TOKEN')->plainTextToken
        ], 200);
    }
    
    // 3. CERRAR SESIÓN (LOGOUT)
    public function logout()
    {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}