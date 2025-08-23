<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.form');
    }

    public function harian(Request $request)
    {
        // Ambil semua transaksi, termasuk yang tidak ada pelanggan
        $penjualan = Penjualan::join('users', 'users.id', '=', 'penjualans.user_id')
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id') // ganti join jadi leftJoin
            ->whereDate('tanggal', $request->tanggal)
            ->select(
                'penjualans.*',
                DB::raw('COALESCE(pelanggans.nama, "Pelanggan") as nama_pelanggan'), // default "Umum" kalau null
                'users.nama as nama_kasir'
            )
            ->orderBy('id')
            ->get();

        // Hitung total hanya transaksi tidak batal
        $totalHarian = Penjualan::whereDate('tanggal', $request->tanggal)
            ->where('status', '!=', 'batal')
            ->sum('total');

        return view('laporan.harian', [
            'penjualan' => $penjualan,
            'totalHarian' => $totalHarian
        ]);
    }

    public function bulanan(Request $request)
    {
        // Data bulanan tetap bisa pakai agregasi, tidak butuh join pelanggan
        $penjualan = Penjualan::select(
                DB::raw('COUNT(id) as jumlah_transaksi'),
                DB::raw('SUM(CASE WHEN status != "batal" THEN total ELSE 0 END) as jumlah_total'),
                DB::raw('COUNT(CASE WHEN status != "batal" THEN 1 END) as jumlah_transaksi_berhasil'),
                DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl")
            )
            ->whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->groupBy('tgl')
            ->get();

        // Total keseluruhan untuk bulan tsb (tidak batal saja)
        $totalBulanan = Penjualan::whereMonth('tanggal', $request->bulan)
            ->whereYear('tanggal', $request->tahun)
            ->where('status', '!=', 'batal')
            ->sum('total');

        $nama_bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei',
            'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $bulan = isset($nama_bulan[$request->bulan - 1]) ? $nama_bulan[$request->bulan - 1] : null;

        return view('laporan.bulanan', [
            'penjualan' => $penjualan,
            'bulan' => $bulan,
            'totalBulanan' => $totalBulanan
        ]);
    }
}
