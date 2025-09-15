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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->enum('type', ['percentage', 'fixed']); // percentage = %, fixed = nominal
            $table->decimal('value', 10, 2); // nilai diskon (misal: 20 untuk 20% atau 5000 untuk Rp 5.000)
            $table->date('start_date')->nullable(); // tanggal mulai diskon
            $table->date('end_date')->nullable(); // tanggal berakhir diskon
            $table->time('start_time')->nullable(); // jam mulai (untuk happy hour)
            $table->time('end_time')->nullable(); // jam berakhir
            $table->boolean('is_active')->default(true); // status aktif/nonaktif
            $table->text('description')->nullable(); // deskripsi diskon
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

            // Index untuk performa
            $table->index(['menu_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
