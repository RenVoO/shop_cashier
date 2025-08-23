@extends('layouts.laporan', ['title' => 'Laporan Harian'])

@section('content')

<h1 class="text-center">Laporan Harian</h1>

<p>Tanggal : {{ date('d/m/Y', strtotime(request()->tanggal)) }}</p>

<table class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>No</th>
            <th>No. Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Kasir</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($penjualan as $key => $row)
        <tr class="{{ $row->status == 'batal' ? 'text-muted' : '' }}">
            <td>{{ $key + 1 }}</td>
            <td>{{ $row->nomor_transaksi }}</td>
            <td>{{ $row->nama_pelanggan ?? 'Pelanggan' }}</td>
            <td>{{ $row->nama_kasir }}</td>
            <td>
                @if($row->status == 'batal')
                    <span class="badge badge-danger">{{ ucwords($row->status) }}</span>
                @else
                    <span class="badge badge-success">{{ ucwords($row->status) }}</span>
                @endif
            </td>
            <td>{{ date('H:i:s', strtotime($row->tanggal)) }}</td>
            <td>
                @if($row->status == 'batal')
                    <strike class="text-muted">{{ number_format($row->total, 0, ',', '.') }}</strike>
                @else
                    {{ number_format($row->total, 0, ',', '.') }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Jumlah Total (Transaksi Berhasil)</th>
            <th>{{ number_format($totalHarian, 0, ',', '.') }}</th>
        </tr>
        <tr class="table-info">
            <th colspan="6">Total Semua Transaksi (Termasuk Batal)</th>
            <th>{{ number_format($penjualan->sum('total'), 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<div class="mt-3">
    <p><small class="text-muted">
        * Transaksi dengan status BATAL ditampilkan dengan warna abu-abu dan dicoret.<br>
        * Total hanya menghitung transaksi yang berhasil (tidak termasuk transaksi batal).
    </small></p>
</div>

@endsection