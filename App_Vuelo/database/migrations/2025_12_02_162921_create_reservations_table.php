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
    Schema::create('reservations', function (Blueprint $table) {
        $table->id();
        // Relación con el usuario que compra
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Relación con el vuelo reservado
        $table->foreignId('flight_id')->constrained()->onDelete('cascade');
        
        // Estado: 'pending' (pendiente), 'accepted' (confirmada), 'rejected' (cancelada) [cite: 53]
        $table->string('status')->default('pending');
        
        $table->text('comments')->nullable(); // Comentarios adicionales [cite: 50]
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
