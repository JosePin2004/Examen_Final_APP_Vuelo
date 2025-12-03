<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Flight;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // Para manejar fechas

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear un USUARIO ADMIN (Para que tú puedas entrar)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@vuelos.com',
            'password' => Hash::make('admin123'), // Contraseña encriptada
            'role' => 'admin',
        ]);

        // 2. Crear un USUARIO PASAJERO (Para pruebas)
        User::create([
            'name' => 'Pasajero Test',
            'email' => 'cliente@vuelos.com',
            'password' => Hash::make('cliente123'),
            'role' => 'user',
        ]);

        // 3. Crear VUELOS DE PRUEBA
        Flight::create([
            'origin' => 'Quito (UIO)',
            'destination' => 'Guayaquil (GYE)',
            'departure_time' => Carbon::now()->addDays(1)->setTime(8, 0), // Mañana 8am
            'arrival_time' => Carbon::now()->addDays(1)->setTime(8, 50),
            'price' => 85.50,
        ]);

        Flight::create([
            'origin' => 'Quito (UIO)',
            'destination' => 'Miami (MIA)',
            'departure_time' => Carbon::now()->addDays(5)->setTime(10, 0),
            'arrival_time' => Carbon::now()->addDays(5)->setTime(14, 30),
            'price' => 450.00,
        ]);

        Flight::create([
            'origin' => 'Cuenca (CUE)',
            'destination' => 'Quito (UIO)',
            'departure_time' => Carbon::now()->addDays(2)->setTime(18, 0),
            'arrival_time' => Carbon::now()->addDays(2)->setTime(18, 50),
            'price' => 60.00,
        ]);
    }
}