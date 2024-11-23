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
        Schema::create('inventory_books', function (Blueprint $table) {
            $table->id();
            $table->integer('stok')->nullable(false);
            $table->integer('price')->nullable(false);
            $table->enum('is_availaible', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->unsignedBigInteger('book_id')->nullable(false);
            $table->timestamps();

            $table->foreign('book_id')->on('books')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_books');
    }
};
