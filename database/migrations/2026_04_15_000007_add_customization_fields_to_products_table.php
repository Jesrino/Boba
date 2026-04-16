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
        Schema::table('products', function (Blueprint $table) {
            $table->json('sugar_levels')->nullable()->after('available_sizes');
            $table->json('ice_levels')->nullable()->after('sugar_levels');
            $table->json('add_on_options')->nullable()->after('ice_levels');
            $table->boolean('supports_customization')->default(true)->after('add_on_options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'sugar_levels',
                'ice_levels',
                'add_on_options',
                'supports_customization',
            ]);
        });
    }
};
