<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilPenjualan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'jumlah',
        'harga_produk',
        'subtotal',
    ];
}
