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
        Schema::create('phongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'loai_phong_id')->constrained(table: 'loai_phongs')->onDelete('cascade');
            $table->string('so_phong');
            $table->string('tinh_trang')->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phongs');
    }
};
