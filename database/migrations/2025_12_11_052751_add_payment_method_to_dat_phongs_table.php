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
        Schema::table('dat_phongs', function (Blueprint $table) {
            // [FIXED] Thêm cột payment_method, sau cột payment_status
            if (!Schema::hasColumn('dat_phongs', 'payment_method')) {
                $table->string('payment_method')->default('pay_at_hotel')->after('payment_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_phongs', function (Blueprint $table) {
            if (Schema::hasColumn('dat_phongs', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};