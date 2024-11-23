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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('amount_book')->nullable(false);
            $table->date('payment_date')->nullable('false');
            $table->enum('status', ['berhasil', 'menunggu', 'gagal'])->default('berhasil');
            $table->enum('payment_method', ['tunai', 'kartu kredit', 'kartu debit', 'm-banking'])->default('tunai');
            $table->integer('total_price')->nullable(false);
            $table->string('shopping_address', 256)->nullable(false);
            $table->unsignedBigInteger('bank_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('bank_id')->on('banks')->references('id');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
