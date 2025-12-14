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
        Schema::create('khuyen_mai_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('khuyen_mai_id')->constrained('khuyen_mais')->onDelete('cascade');
            $table->integer('used_count')->default(1)->comment('Số lần user này đã dùng mã');
            $table->timestamp('last_used_at')->nullable()->comment('Lần cuối sử dụng');
            $table->timestamps();
            
            // Unique constraint: mỗi user chỉ có 1 record cho 1 mã
            $table->unique(['user_id', 'khuyen_mai_id']);
            
            // Index cho performance
            $table->index('user_id');
            $table->index('khuyen_mai_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_usage');
    }
};
