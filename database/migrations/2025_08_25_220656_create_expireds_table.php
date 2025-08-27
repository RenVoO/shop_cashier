<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expireds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('jumlah');
            $table->date('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expireds');
    }
};
