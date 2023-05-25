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
            $table->foreignId("user_id")->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->date('purchase_date');//fecha de compra
            //$table->enum('purchase_status', ['pending', 'delivered'])->default('pending');//estado de compra
            $table->enum('payment_status', ['pending', 'paid'])->default('pending');//estado de pago
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
