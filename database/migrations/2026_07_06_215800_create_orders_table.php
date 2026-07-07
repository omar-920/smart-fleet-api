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
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'accepted', 'picked_up', 'delivered', 'cancelled'])->default('pending');
            $table->string('pickup_location');
            $table->string('dropoff_location');
            $table->decimal('delivery_fee', 8, 2);
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
