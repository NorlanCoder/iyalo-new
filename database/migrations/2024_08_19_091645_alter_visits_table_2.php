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
        Schema::table('visits', function (Blueprint $table) {
            $table->text('describ')->nullable();
            $table->boolean('confirm_client')->default(false);
            $table->boolean('confirm_owner')->default(false);
            $table->boolean('is_refund')->default(false);
            $table->float('free')->default(0);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->float('free')->default(20);
            $table->string('token')->nullable();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('describ');
            $table->dropColumn('confirm_client');
            $table->dropColumn('confirm_owner');
            $table->dropColumn('is_refund');
            $table->dropColumn('free');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('free');
            $table->dropColumn('token');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
