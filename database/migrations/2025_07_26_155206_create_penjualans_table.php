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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()
                ->cascadeOnDelete()
                ->noActionOnUpdate();
            $table->foreignId('pelanggan_id')->constrained()
                ->cascadeOnDelete()
                ->noActionOnUpdate();
            $table->string('nomor_transaksi')->unique();
            $table->dateTime('tanggal');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('pajak');
            $table->unsignedInteger('total');
            $table->unsignedInteger('tunai');
            $table->unsignedBigInteger('kembalian');
            $table->enum('status',['selesai','batal'])->default('selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
