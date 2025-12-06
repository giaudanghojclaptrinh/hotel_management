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
        Schema::create('dat_phongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('ngay_den');
            $table->date('ngay_di');
            $table->decimal('tong_tien', 12, 2);
            $table->string('trang_thai')->default('pending');
            $table->string('payment_status')->default('unpaid');
            $table->text('ghi_chu')->nullable();
            $table->string('promotion_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_phongs');
    }
};
