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
        Schema::table('flights', function (Blueprint $table) {
            $table->integer('economy_seats')->default(100)->after('capacity');
            $table->integer('business_seats')->default(50)->after('economy_seats');
            $table->decimal('economy_price', 10, 2)->default(0)->after('business_seats');
            $table->decimal('business_price', 10, 2)->default(0)->after('economy_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropColumn(['economy_seats', 'business_seats', 'economy_price', 'business_price']);
        });
    }
};
