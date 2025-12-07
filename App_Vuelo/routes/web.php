<?php

use Illuminate\Support\Facades\Route;

// 1. Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// 2. Ruta para el LOGIN (¡Esta es la que te falta!)
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

// 2.5. Ruta para el REGISTRO
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// 3. Ruta para el DASHBOARD (¡También falta!)
Route::get('/dashboard', function () {
    return view('dashboard');
});

// 4. Tu ruta de reservas
Route::view('/ver-reservas', 'reservas');