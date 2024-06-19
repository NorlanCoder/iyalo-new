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
        Schema::create('favories', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();            
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->bigInteger('property_id')->unsigned();            
            $table->foreign('property_id')->on('properties')->references('id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favories');
    }
};
