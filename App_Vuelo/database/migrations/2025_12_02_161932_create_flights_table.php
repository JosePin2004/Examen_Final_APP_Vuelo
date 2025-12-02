<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('flights', function (Blueprint $table) {
        $table->id();
        $table->string('origin');       // Origen
        $table->string('destination');  // Destino
        $table->dateTime('departure_time'); // Hora salida
        $table->dateTime('arrival_time');   // Hora llegada
        $table->decimal('price', 10, 2);    // Precio
        // URL de la imagen en Firebase (puede ser nula al inicio) [cite: 44]
        $table->string('image_url')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
