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
        Schema::create('loai_phongs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai_phong', 100);
            $table->decimal('gia', 10, 2);
            $table->integer('so_nguoi');
            $table->json(('tien_nghi'))->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_phongs');
    }
};
