<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('pending');
            $table->text('shipping_address');
            $table->string('payment_method');
            $table->string('tracking_number')->nullable();
            $table->timestamps(); // created_at = waktu order dibuat
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
