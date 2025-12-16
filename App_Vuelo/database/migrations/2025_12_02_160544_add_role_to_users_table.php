<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Agregar el campo rol a la tabla de usuarios
{
    Schema::table('users', function (Blueprint $table) {
        // Agregamos el campo rol, por defecto será 'user' (pasajero)
        $table->string('role')->default('user'); // Opciones: 'admin', 'user' 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void // Eliminar el campo rol de la tabla de usuarios
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
