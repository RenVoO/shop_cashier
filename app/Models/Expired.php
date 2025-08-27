<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expired extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'produk_id',
        'jumlah',
        'tanggal'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
