<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kupon', 'tipe_diskon', 'jumlah_diskon', 'kategori_id',
        'produk_id', 'minimal_belanja', 'tanggal_kadaluarsa'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
