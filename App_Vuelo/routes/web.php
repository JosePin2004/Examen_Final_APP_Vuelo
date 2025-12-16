<?php

use Illuminate\Support\Facades\Route;

// 1. Ruta de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// 2. Ruta para el LOGIN 
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

// 2.5. Ruta para el REGISTRO
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// 3. Ruta para el DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard');
});

// 4. Ruta para el ADMIN - Mostrar solo la vista, la validación ocurre en JavaScript
Route::get('/admin', function () {
    return view('admin');
});