<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'kategori_id',
        'kode_produk',
        'nama_produk',
        'harga',
        'stok'
    ];
}
