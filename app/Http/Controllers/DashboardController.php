<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use DB;

class DashboardController extends Controller
{
    function index()
{
    $user = User::selectRaw('count(*) as jumlah')->first();
    $pelanggan = Pelanggan::selectRaw('count(*) as jumlah')->first();
    $kategori = Kategori::selectRaw('count(*) as jumlah')->first();
    $produk = Produk::selectRaw('count(*) as jumlah')->first();

    $penjualan = Penjualan::select(
        DB::raw('SUM(total) as jumlah_total'),
        DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') tgl")
    )
    ->whereMonth('tanggal', date('m'))
    ->whereYear('tanggal', date('Y'))
    ->groupBy('tgl')
    ->get();

    $nama_bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei',
        'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $label = 'Transaksi ' . $nama_bulan[date('m') - 1] . ' ' . date('Y');
    $labels = [];

    $data = [];

    foreach ($penjualan as $row) {
        $labels[] = substr($row->tgl, 0, 2);
        $data[] = $row->jumlah_total;
    }

    return view('welcome', [
        'user' => $user,
        'pelanggan' => $pelanggan,
        'kategori' => $kategori,
        'produk' => $produk,
        'cart' => [
            'label' => $label,
            'labels' => json_encode($labels),
            'data' => json_encode($data)
        ]
    ]);
}

}
