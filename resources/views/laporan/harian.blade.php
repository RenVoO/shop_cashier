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
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $row->nomor_transaksi }}</td>
            <td>{{ $row->nama_pelanggan }}</td>
            <td>{{ $row->nama_kasir }}</td>
            <td>{{ ucwords($row->status) }}</td>
            <td>{{ date('H:i:s', strtotime($row->tanggal)) }}</td>
            <td>{{ number_format($row->total, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6">Jumlah Total</th>
            <th>{{ number_format($penjualan->sum('total'), 0, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

@endsection
