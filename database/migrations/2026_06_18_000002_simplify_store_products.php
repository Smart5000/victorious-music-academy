<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'material_file')) {
                $table->string('material_file')->nullable()->after('thumbnail');
            }
        });

        DB::table('products')
            ->where('product_type', 'digital')
            ->update(['product_type' => 'materials']);

        DB::table('products')
            ->where('product_type', 'physical')
            ->update(['product_type' => 'instrument']);

        DB::table('products')
            ->where('price_type', 'free')
            ->update(['price' => 0, 'is_free' => true]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'material_file')) {
                $table->dropColumn('material_file');
            }
        });
    }
};
