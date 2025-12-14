<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bug #19 Fix: Add khuyen_mai_id foreign key column
     * Code uses khuyen_mai_id but database only has promotion_code
     */
    public function up(): void
    {
        Schema::table('dat_phongs', function (Blueprint $table) {
            $table->foreignId('khuyen_mai_id')->nullable()
                  ->after('tong_tien')
                  ->constrained('khuyen_mais')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dat_phongs', function (Blueprint $table) {
            $table->dropForeign(['khuyen_mai_id']);
            $table->dropColumn('khuyen_mai_id');
        });
    }
};
