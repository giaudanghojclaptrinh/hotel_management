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
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dat_phong_id')->constrained('dat_phongs')->onDelete('cascade');
            $table->string('ma_hoa_don')->unique();
            $table->DateTime('ngay_lap');
            $table->decimal('tong_tien', 12, 2);
            $table->string('phuong_thuc_thanh_toan');
            $table->string('trang_thai')->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
