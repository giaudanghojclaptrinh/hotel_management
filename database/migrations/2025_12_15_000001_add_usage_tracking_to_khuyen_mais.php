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
        Schema::table('khuyen_mais', function (Blueprint $table) {
            // Tổng số lần có thể sử dụng (toàn hệ thống)
            $table->integer('usage_limit')->nullable()->after('so_tien_giam_gia')->comment('Null = không giới hạn');
            
            // Đã được sử dụng bao nhiêu lần
            $table->integer('used_count')->default(0)->after('usage_limit');
            
            // Giới hạn số lần dùng/user
            $table->integer('usage_per_user')->default(1)->after('used_count')->comment('Mỗi user có thể dùng bao nhiêu lần');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khuyen_mais', function (Blueprint $table) {
            $table->dropColumn(['usage_limit', 'used_count', 'usage_per_user']);
        });
    }
};
