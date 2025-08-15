<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_diskons_table.php
    public function up()
    {
        Schema::create('diskons', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kupon')->unique();
            $table->enum('tipe_diskon', ['persen', 'nominal']);
            $table->decimal('jumlah_diskon', 10, 2);
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('produk_id')->nullable();
            $table->decimal('minimal_belanja', 10, 2)->nullable();
            $table->date('tanggal_kadaluarsa')->nullable();
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('set null');
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diskons');
    }
};
