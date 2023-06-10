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
            $table->text('code')->unique();
            $table->string('request_id')->nullable();
            $table->foreignId("user_id")->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('purchase_date');
            $table->enum('currency', ['COP', 'USD'])->default('COP');
            $table->string('url')->nullable();
            $table->enum('payment_status', ['canceled', 'paid', 'pending', 'waiting', 'verify_bank'])->default('pending');//estado de pago
            $table->dateTime('payment_date')->nullable();
            $table->double('purchase_total', 15, 2);
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
