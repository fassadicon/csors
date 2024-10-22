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
        Schema::create('caterers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('downpayment')->default(25);
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->text('about')->nullable();
            $table->text('logo_path')->nullable();
            $table->text('qr_path')->nullable();
            $table->text('requirements_path')->nullable();
            $table->unsignedTinyInteger('is_verified')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caterers');
    }
};
