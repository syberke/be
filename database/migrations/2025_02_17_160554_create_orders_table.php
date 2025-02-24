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
            $table->uuid('id')->primary();
            $table->string('order_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->bigInteger('total_price');
            $table->integer('quantity');
            $table->enum('status', ['PENDING', 'SUCCESS', 'CANCEL']);

            $table->uuid('user_id');
            $table->uuid('book_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('book_id')->references('id')->on('books');

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
