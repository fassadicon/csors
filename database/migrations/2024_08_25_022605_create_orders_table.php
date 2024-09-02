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
            $table->foreignId('user_id')->constrained();
            $table->foreignId('caterer_id')->constrained();
            $table->unsignedBigInteger('promo_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('location');
            $table->decimal('total_amount', 8, 2);
            $table->decimal('deducted_amount', 8, 2)->nullable();
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
            ])->default('pending');
            $table->enum('order_status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ]);
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
