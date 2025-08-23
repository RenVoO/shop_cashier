@extends('layouts.laporan', ['title' => 'Laporan Bulanan'])

@section('content')
    <h1 class="text-center">Laporan Bulanan</h1>

    <p>Bulan : {{ $bulan }} {{ request()->tahun }}</p>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total Transaksi</th>
                <th>Transaksi Berhasil</th>
                <th>Transaksi Batal</th>
                <th>Total (Berhasil)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $key => $row)
                @php
                    $transaksi_batal = $row->jumlah_transaksi - $row->jumlah_transaksi_berhasil;
                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->tgl }}</td>
                    <td>{{ $row->jumlah_transaksi }}</td>
                    <td>{{ $row->jumlah_transaksi_berhasil }}</td>
                    <td>{{ $transaksi_batal }}</td>
                    <td>{{ number_format($row->jumlah_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Jumlah Total</th>
                <th>{{ $penjualan->sum('jumlah_transaksi_berhasil') }}</th>
                <th>{{ $penjualan->sum('jumlah_transaksi') - $penjualan->sum('jumlah_transaksi_berhasil') }}</th>
                <th>{{ number_format($totalBulanan, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Ringkasan Bulanan</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total Transaksi:</strong> {{ $penjualan->sum('jumlah_transaksi') }}</p>
                    <p><strong>Transaksi Berhasil:</strong> {{ $penjualan->sum('jumlah_transaksi_berhasil') }}</p>
                    <p><strong>Transaksi Batal:</strong> {{ $penjualan->sum('jumlah_transaksi') - $penjualan->sum('jumlah_transaksi_berhasil') }}</p>
                    <p><strong>Total Pendapatan:</strong> {{ number_format($totalBulanan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <p><small class="text-muted">
            * Laporan bulanan hanya menghitung total dari transaksi yang berhasil.<br>
            * Transaksi dengan status BATAL tidak dihitung dalam total pendapatan.
        </small></p>
    </div>
@endsection