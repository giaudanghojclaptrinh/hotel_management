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
        // Thêm cột subtotal và vat_amount vào bảng dat_phongs
        Schema::table('dat_phongs', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('ngay_di')->comment('Tạm tính trước thuế');
            $table->decimal('vat_amount', 10, 2)->default(0)->after('discount_amount')->comment('Thuế VAT 8%');
        });

        // Thêm cột subtotal và vat_amount vào bảng hoa_dons
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('ngay_lap')->comment('Tạm tính trước thuế');
            $table->decimal('vat_amount', 10, 2)->default(0)->after('subtotal')->comment('Thuế VAT 8%');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_phongs', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_amount']);
        });

        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'vat_amount']);
        });
    }
};
