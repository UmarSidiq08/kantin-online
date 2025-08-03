<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('canteen_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('status', ['diproses', 'selesai', 'ditolak']);
            $table->decimal('total_price', 10, 2);
            $table->json('items'); // contoh isi: [{"menu_id": 1, "name": "Nasi Goreng", "qty": 2}, ...]

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};
