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
        Schema::create('coupon_specifics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('specific_id'); // ID of category, brand, or product
            $table->enum('specific_type', ['category', 'brand', 'product']);
            $table->timestamps();

            // Ensure a coupon can't have multiple types at the same time
            $table->unique(['coupon_id', 'specific_id', 'specific_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_specifics');
    }
};
