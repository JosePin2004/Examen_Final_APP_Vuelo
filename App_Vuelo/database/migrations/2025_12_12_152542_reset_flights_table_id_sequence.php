<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Desactivar restricciones de clave foránea
        DB::statement('ALTER TABLE reservations NOCHECK CONSTRAINT ALL');
        
        // Eliminar todos los vuelos existentes
        DB::table('flights')->delete();
        
        // Resetear el contador de identidad en SQL Server
        DB::statement('DBCC CHECKIDENT (flights, RESEED, 0)');
        
        // Reactivar restricciones de clave foránea
        DB::statement('ALTER TABLE reservations CHECK CONSTRAINT ALL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay nada que revertir, ya que truncate es irreversible
    }
};
