<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ESTO ES LO NUEVO QUE NECESITAS AGREGAR:

// 1. Ruta para mostrar la pantalla de Login
Route::get('/login', function () {
    return view('auth.login'); // Busca el archivo resources/views/auth/login.blade.php
})->name('login');

// 2. Ruta para mostrar el Dashboard después de loguearse
Route::get('/dashboard', function () {
    return view('dashboard'); // Busca el archivo resources/views/dashboard.blade.php
});

// Esta ya la tenías, la puedes dejar si la usas
Route::view('/ver-reservas', 'reservas');