<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'selected_instrument_id')) {
                $table->foreignUuid('selected_instrument_id')
                    ->nullable()
                    ->after('role')
                    ->constrained('instruments')
                    ->nullOnDelete();
            }
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('payment_transactions', 'selected_instrument_id')) {
                $table->foreignUuid('selected_instrument_id')
                    ->nullable()
                    ->after('subscription_plan_id')
                    ->constrained('instruments')
                    ->nullOnDelete();
            }
        });

        Schema::table('user_subscriptions', function (Blueprint $table) {
            if (! Schema::hasColumn('user_subscriptions', 'instrument_id')) {
                $table->foreignUuid('instrument_id')
                    ->nullable()
                    ->after('subscription_plan_id')
                    ->constrained('instruments')
                    ->nullOnDelete();
            }
        });

        if (! Schema::hasTable('student_course_access')) {
            Schema::create('student_course_access', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
                $table->foreignUuid('course_id')->constrained()->cascadeOnDelete();
                $table->string('status')->default('locked')->index();
                $table->string('unlocked_by')->nullable()->index();
                $table->timestamp('unlocked_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'course_id']);
                $table->index(['user_id', 'status']);
                $table->index(['course_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_course_access');

        Schema::table('user_subscriptions', function (Blueprint $table) {
            if (Schema::hasColumn('user_subscriptions', 'instrument_id')) {
                $table->dropConstrainedForeignId('instrument_id');
            }
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('payment_transactions', 'selected_instrument_id')) {
                $table->dropConstrainedForeignId('selected_instrument_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'selected_instrument_id')) {
                $table->dropConstrainedForeignId('selected_instrument_id');
            }
        });
    }
};
