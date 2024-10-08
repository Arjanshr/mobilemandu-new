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
        Schema::create('popular_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable()->default(null);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_products');
    }
};
