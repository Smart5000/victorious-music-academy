<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('created_at', 'users_created_at_index');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->index('created_at', 'courses_created_at_index');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index('created_at', 'lessons_created_at_index');
            $table->index(['course_id', 'lesson_order'], 'lessons_course_order_index');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('updated_at', 'lesson_progress_updated_at_index');
            $table->index(['completed', 'updated_at'], 'lesson_progress_completed_updated_index');
        });

        Schema::table('video_watch_history', function (Blueprint $table) {
            $table->index('watched_at', 'video_watch_history_watched_at_index');
            $table->index(['user_id', 'watched_at'], 'video_watch_history_user_watched_index');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->index('created_at', 'product_categories_created_at_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('created_at', 'products_created_at_index');
            $table->index(['product_category_id', 'is_active'], 'products_category_active_index');
            $table->index(['product_type', 'is_active'], 'products_type_active_index');
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->index('created_at', 'user_subscriptions_created_at_index');
            $table->index(['status', 'created_at'], 'user_subscriptions_status_created_index');
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->index('created_at', 'payment_transactions_created_at_index');
            $table->index(['status', 'created_at'], 'payment_transactions_status_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropIndex('payment_transactions_status_created_index');
            $table->dropIndex('payment_transactions_created_at_index');
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            $table->dropIndex('user_subscriptions_status_created_index');
            $table->dropIndex('user_subscriptions_created_at_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_type_active_index');
            $table->dropIndex('products_category_active_index');
            $table->dropIndex('products_created_at_index');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropIndex('product_categories_created_at_index');
        });

        Schema::table('video_watch_history', function (Blueprint $table) {
            $table->dropIndex('video_watch_history_user_watched_index');
            $table->dropIndex('video_watch_history_watched_at_index');
        });

        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->dropIndex('lesson_progress_completed_updated_index');
            $table->dropIndex('lesson_progress_updated_at_index');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex('lessons_course_order_index');
            $table->dropIndex('lessons_created_at_index');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_created_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_created_at_index');
        });
    }
};
