<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (Schema::hasTable('reviews')) {
            // Attempt to drop the unique index. If MySQL refuses because the index
            // is used by a foreign key, create single-column indexes that the
            // foreign key can use and retry dropping the unique index.
            try {
                Schema::table('reviews', function (Blueprint $table) {
                    if (Schema::hasColumn('reviews', 'user_id') && Schema::hasColumn('reviews', 'loai_phong_id')) {
                        $table->dropUnique('reviews_user_loai_unique');
                    }
                });
            } catch (\Exception $e) {
                // Try to create single-column indexes that satisfy any FK
                // dependency, then attempt to drop the unique index again.
                try {
                    Schema::table('reviews', function (Blueprint $table) {
                        if (Schema::hasColumn('reviews', 'loai_phong_id')) {
                            // create index on loai_phong_id if not present
                            $table->index('loai_phong_id');
                        }
                        if (Schema::hasColumn('reviews', 'user_id')) {
                            $table->index('user_id');
                        }
                    });
                } catch (\Exception $ignored) {
                    // ignore index creation errors
                }

                try {
                    Schema::table('reviews', function (Blueprint $table) {
                        if (Schema::hasColumn('reviews', 'user_id') && Schema::hasColumn('reviews', 'loai_phong_id')) {
                            $table->dropUnique('reviews_user_loai_unique');
                        }
                    });
                } catch (\Exception $ex) {
                    // give up if still failing; migration will continue without dropping
                }
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unique(['user_id', 'loai_phong_id'], 'reviews_user_loai_unique');
            });
        }
    }
};
