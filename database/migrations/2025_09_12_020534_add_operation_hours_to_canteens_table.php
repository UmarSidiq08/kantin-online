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
        Schema::table('canteens', function (Blueprint $table) {
            // Jam operasional per hari (JSON format)
            $table->json('operating_hours')->nullable();
            // Status kantin (buka/tutup manual override)
            $table->boolean('is_open')->default(true);
            // Catatan khusus (opsional)
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('canteens', function (Blueprint $table) {
            $table->dropColumn(['operating_hours', 'is_open', 'notes']);
        });
    }
};
