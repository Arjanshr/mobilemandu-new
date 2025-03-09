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
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('is_category_specific'); // Remove old flag
            $table->enum('specific_type', ['normal', 'category', 'brand', 'product', 'free_delivery'])->nullable()->after('is_user_specific');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->boolean('is_category_specific')->default(false);
            $table->dropColumn('specific_type');
        });
    }
};
