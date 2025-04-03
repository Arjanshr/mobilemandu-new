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
        Schema::table('category_specification', function (Blueprint $table) { // Use the singular table name
            $table->integer('display_order')->default(0)->after('specification_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_specification', function (Blueprint $table) { // Use the singular table name
            $table->dropColumn('display_order');
        });
    }
};
