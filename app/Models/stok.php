<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stok extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'nama_suplier',
        'jumlah',
        'tanggal'
    ];
}
