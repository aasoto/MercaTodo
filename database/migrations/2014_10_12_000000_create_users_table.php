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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('type_doc', ['cc', 'pas', 'o']);
            $table->string('num_doc', 100)->unique();
            $table->string('first_name', 100); //
            $table->string('second_name', 100)->nullable(); //
            $table->string('surname', 100);//
            $table->string('second_surname', 100)->nullable(); //
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('birthdate'); //
            $table->enum('gender', ['m', 'f', 'o']); //
            $table->string('phone', 100)->unique(); //
            $table->string('address', 1000);
            $table->foreignId("state_id")->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId("city_id")->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('enabled')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
