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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('image_url')->nullable();
            $table->date('birthday')->nullable();
            $table->string('token_notify')->default('');
            $table->integer('solde')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->text('password');
            $table->boolean('status')->default(true);
            $table->enum('role', ['visitor','admin','announcer'])->default('visitor');

            $table->string('adress')->nullable();
            $table->string('card_image')->nullable();
            $table->string('logo')->nullable();

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
