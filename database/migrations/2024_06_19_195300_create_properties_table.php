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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('label');

            $table->bigInteger('user_id')->unsigned();            
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->bigInteger('category_id')->unsigned();            
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade');

            $table->integer('price');
            $table->enum('frequency', ['daily', 'monthly', 'yearly'])->nullable();
            $table->string('city');
            $table->string('country');
            $table->string('district');
            $table->string('cover_url')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('long')->nullable();
            $table->text('description')->nullable();
            $table->integer('room')->nullable(); // chambre
            $table->integer('bathroom')->nullable(); // douche
            $table->integer('lounge')->nullable(); //salon
            $table->integer('swingpool')->nullable(); //piscine
            $table->boolean('status')->default(true);
            $table->integer('visite_price');
            $table->text('conditions')->nullable();
            $table->enum('device', ['FCFA', 'EUR']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
