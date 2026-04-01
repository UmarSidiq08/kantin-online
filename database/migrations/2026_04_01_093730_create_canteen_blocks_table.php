<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canteen_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('canteen_id')->constrained('canteens')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['canteen_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canteen_blocks');
    }
};
