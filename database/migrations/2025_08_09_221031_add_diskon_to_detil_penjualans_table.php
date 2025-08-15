<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detil_penjualans', function (Blueprint $table) {
            $table->unsignedInteger('diskon')->default(0)->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('detil_penjualans', function (Blueprint $table) {
            $table->dropColumn('diskon');
        });
    }
};
