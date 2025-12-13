<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('loai_phong_id');
                $table->index('parent_id');
                // optional foreign key
                $table->foreign('parent_id')->references('id')->on('reviews')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (Schema::hasColumn('reviews', 'parent_id')) {
                    $table->dropForeign(['parent_id']);
                    $table->dropIndex(['parent_id']);
                    $table->dropColumn('parent_id');
                }
            });
        }
    }
};
