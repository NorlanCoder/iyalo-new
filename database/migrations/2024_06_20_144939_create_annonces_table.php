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
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->enum('type', ['Agence de demenagement', 'Agence de menage'])->default('Agence de demenagement');
            $table->string('adresse')->nullable();
            $table->text('image')->nullable();          
            $table->boolean('active')->default(true);
            $table->text('description')->nullable();  
            $table->bigInteger('user_id')->unsigned();            
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
