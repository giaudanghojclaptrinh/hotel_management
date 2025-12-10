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
        // 1. Bảng lưu tên các tiện nghi (Wifi, AC, Tivi...)
        Schema::create('tien_nghis', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tien_nghi'); // Ví dụ: "Wifi tốc độ cao"
            $table->string('ma_tien_nghi')->unique(); // Ví dụ: "wifi", "ac", "tv" (Dùng để lọc trong code)
            $table->string('icon')->nullable(); // Ví dụ: font-awesome class "fa-wifi"
            $table->timestamps();
        });

        // 2. Bảng trung gian (Pivot Table) nối Loại phòng & Tiện nghi
        Schema::create('loai_phong_tien_nghi', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại trỏ về bảng loai_phongs
            $table->unsignedBigInteger('loai_phong_id');
            $table->foreign('loai_phong_id')->references('id')->on('loai_phongs')->onDelete('cascade');

            // Khóa ngoại trỏ về bảng tien_nghis
            $table->unsignedBigInteger('tien_nghi_id');
            $table->foreign('tien_nghi_id')->references('id')->on('tien_nghis')->onDelete('cascade');
        });
    }   
    public function down(): void
    {
        Schema::dropIfExists('tien_nghis');
    }
};
