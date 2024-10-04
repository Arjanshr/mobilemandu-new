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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('total_price', 8, 2);
            $table->decimal('discount', 8, 2);
            $table->decimal('grand_total', 8, 2);
            $table->string('coupon_code')->nullable()->default(null);
            $table->enum('payment_type', ['online', 'cash', 'mixed']);
            $table->enum('payment_status', ['unpaid', 'paid', 'partially_paid'])->default('unpaid');
            $table->enum('status', [
                'pending',
                'processing',
                'confirmed',
                'on_hold',
                'sent_for_delivery',
                'completed',
                'cancelled',
                'returned'
            ])->default('pending');
            $table->text('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
