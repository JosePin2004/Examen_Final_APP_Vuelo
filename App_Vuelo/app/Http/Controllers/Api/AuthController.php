<?php

namespace App\Http\Controllers\Api; // <--- Importante: Carpeta Api

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Función para Registrarse
    public function register(Request $request)
    {
        // 1. Validar los datos del registro
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Por defecto todos son usuarios normales
        ]);

        // 3. Crear token para que inicie sesión automáticamente
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Devolver respuesta con el token
        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

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

    // Función para obtener datos del usuario logueado
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // Función para eliminar la cuenta del usuario
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Eliminar todas las reservaciones del usuario
        $user->reservations()->delete();

        // Eliminar todos los tokens del usuario (todas las sesiones)
        $user->tokens()->delete();

        // Eliminar el usuario
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tu cuenta y todas tus reservaciones han sido eliminadas exitosamente'
        ]);
    }

    // Función para actualizar el perfil del usuario
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Actualizar los datos
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente',
            'user' => $user
        ]);
    }
}