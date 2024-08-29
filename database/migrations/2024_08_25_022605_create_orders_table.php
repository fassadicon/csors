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
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->decimal('deducted_amount', 8, 2)->nullable();
            $table->decimal('total_amount', 8, 2);
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
