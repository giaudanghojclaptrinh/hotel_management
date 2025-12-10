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
        Schema::table('chi_tiet_dat_phongs', function (Blueprint $table) {
            // Kiểm tra nếu chưa có thì mới thêm
            if (!Schema::hasColumn('chi_tiet_dat_phongs', 'don_gia')) {
                $table->decimal('don_gia', 15, 2)->default(0)->after('so_luong'); // Giá tại thời điểm đặt
            }
            
            if (!Schema::hasColumn('chi_tiet_dat_phongs', 'thanh_tien')) {
                $table->decimal('thanh_tien', 15, 2)->default(0)->after('don_gia'); // Tổng tiền (đơn giá * số lượng)
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chi_tiet_dat_phongs', function (Blueprint $table) {
            $table->dropColumn(['don_gia', 'thanh_tien']);
        });
    }
};
