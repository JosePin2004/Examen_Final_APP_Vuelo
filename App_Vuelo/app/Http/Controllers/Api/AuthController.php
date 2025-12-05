<?php

namespace App\Http\Controllers\Api; // <--- Importante: Carpeta Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Función para Iniciar Sesión
    public function login(Request $request)
    {
        // 1. Validar que lleguen los datos
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Intentar autenticar con email y password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciales incorrectas (Revisa tu correo o contraseña)'
            ], 401);
        }

        // 3. Si pasa, buscamos al usuario
        $user = User::where('email', $request['email'])->firstOrFail();

        // 4. CREAMOS EL TOKEN (La llave de acceso)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Devolvemos el token al Frontend
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // Función para Cerrar Sesión (Logout)
    public function logout(Request $request)
    {
        // Elimina el token actual para que no se pueda usar más
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}