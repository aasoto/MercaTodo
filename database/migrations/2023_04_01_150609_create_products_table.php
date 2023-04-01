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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->foreignId("products_category_id")->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('barcode', 1000)->unique();
            $table->text('description')->nullable();
            $table->integer('price');
            $table->enum('unit', ['unit', 'pair', 'dozen', 'box']);
            $table->integer('num_unit')->nullable();
            $table->integer('stock')->nullable();
            $table->text('picture_1');
            $table->text('picture_2')->nullable();
            $table->text('picture_3')->nullable();
            $table->boolean('availability')->default(true);
            $table->text('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
