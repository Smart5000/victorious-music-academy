<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('instruments', function (Blueprint $table) {
            if (! Schema::hasColumn('instruments', 'thumbnail_url')) {
                $table->string('thumbnail_url', 2048)->nullable()->after('thumbnail');
                $table->string('thumbnail_public_id')->nullable()->after('thumbnail_url');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'thumbnail_url')) {
                $table->string('thumbnail_url', 2048)->nullable()->after('thumbnail');
                $table->string('thumbnail_public_id')->nullable()->after('thumbnail_url');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'thumbnail_url')) {
                $table->string('thumbnail_url', 2048)->nullable()->after('thumbnail');
                $table->string('thumbnail_public_id')->nullable()->after('thumbnail_url');
            }

            if (! Schema::hasColumn('products', 'material_url')) {
                $table->string('material_url', 2048)->nullable()->after('material_file');
                $table->string('material_public_id')->nullable()->after('material_url');
            }
        });

        Schema::table('store_banners', function (Blueprint $table) {
            if (! Schema::hasColumn('store_banners', 'banner_url')) {
                $table->string('banner_url', 2048)->nullable()->after('image');
                $table->string('banner_public_id')->nullable()->after('banner_url');
            }
        });

        Schema::table('homepage_intro_videos', function (Blueprint $table) {
            if (! Schema::hasColumn('homepage_intro_videos', 'video_url')) {
                $table->string('video_url', 2048)->nullable()->after('video');
                $table->string('video_public_id')->nullable()->after('video_url');
            }

            if (! Schema::hasColumn('homepage_intro_videos', 'poster_url')) {
                $table->string('poster_url', 2048)->nullable()->after('poster');
                $table->string('poster_public_id')->nullable()->after('poster_url');
            }
        });

        Schema::table('thumbnails', function (Blueprint $table) {
            if (! Schema::hasColumn('thumbnails', 'thumbnail_url')) {
                $table->string('thumbnail_url', 2048)->nullable()->after('path');
                $table->string('thumbnail_public_id')->nullable()->after('thumbnail_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('thumbnails', function (Blueprint $table) {
            if (Schema::hasColumn('thumbnails', 'thumbnail_public_id')) {
                $table->dropColumn(['thumbnail_url', 'thumbnail_public_id']);
            }
        });

        Schema::table('homepage_intro_videos', function (Blueprint $table) {
            if (Schema::hasColumn('homepage_intro_videos', 'poster_public_id')) {
                $table->dropColumn(['poster_url', 'poster_public_id']);
            }

            if (Schema::hasColumn('homepage_intro_videos', 'video_public_id')) {
                $table->dropColumn(['video_url', 'video_public_id']);
            }
        });

        Schema::table('store_banners', function (Blueprint $table) {
            if (Schema::hasColumn('store_banners', 'banner_public_id')) {
                $table->dropColumn(['banner_url', 'banner_public_id']);
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'material_public_id')) {
                $table->dropColumn(['material_url', 'material_public_id']);
            }

            if (Schema::hasColumn('products', 'thumbnail_public_id')) {
                $table->dropColumn(['thumbnail_url', 'thumbnail_public_id']);
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'thumbnail_public_id')) {
                $table->dropColumn(['thumbnail_url', 'thumbnail_public_id']);
            }
        });

        Schema::table('instruments', function (Blueprint $table) {
            if (Schema::hasColumn('instruments', 'thumbnail_public_id')) {
                $table->dropColumn(['thumbnail_url', 'thumbnail_public_id']);
            }
        });
    }
};
