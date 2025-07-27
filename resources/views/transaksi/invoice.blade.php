@extends('layouts.main', ['title' => 'Invoice'])
@section('title-content')
    <i class="fas fa-file-invoice mr-2"></i>
    Invoice
@endsection

@section('content')
    @if (session('destroy') == 'success')
        <x-alert type="success">
            <strong>Berhasil dibatalkan!</strong> Transaksi berhasil dibatalkan.
        </x-alert>
    @endif
    <div class="card card-orange card-outline">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <p>No. Transaksi : {{ $penjualan->nomor_transaksi }}</p>
                    <p>Nama Pelanggan : {{ $pelanggan->nama }}</p>
                    <p>No. Telepon : {{ $pelanggan->nomor_tlp }}</p>
                    <p>Alamat : {{ $pelanggan->alamat }}</p>
                </div>
                <div class="col">
                    <p>Tgl. Transaksi : {{ date('d/m/Y H:i:s', strtotime($penjualan->tanggal)) }}</p>
                    <p>Kasir : {{ $user->nama }}</p>
                    <p>Status :
                        @if ($penjualan->status == 'selesai')
                            <span class="badge badge-success">Selesai</span>
                        @endif
                        @if ($penjualan->status == 'batal')
                            <span class="badge badge-danger">Dibatalkan</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detilPenjualan as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ number_format($item->harga_produk, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 offset-6 text-right">
                    <p>Sub Total : {{ number_format($penjualan->subtotal, 0, ',', '.') }}</p>
                    <p>Pajak 10% : {{ number_format($penjualan->pajak, 0, ',', '.') }}</p>
                    <p>Total : {{ number_format($penjualan->total, 0, ',', '.') }}</p>
                    <p>Cash : {{ number_format($penjualan->tunal, 0, ',', '.') }}</p>
                    <p>Kembalian : {{ number_format($penjualan->kembalian, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer form-inline">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mr-2">Ke Transaksi</a>
            @if ($penjualan->status == 'selesai')
                <button type="button" class="btn btn-danger ml-auto mr-2" data-toggle="modal" data-target="#modalBatal">
                    Dibatalkan
                </button>
            @endif
            <a target="_blank" href="{{ route('transaksi.cetak', ['transaksi' => $penjualan->id]) }}"
                class="btn btn-primary @if ($penjualan->status == 'batal') ml-auto @endif">
                <i class="fas fa-print mr-2"></i> Cetak
            </a>
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="modalBatal" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Dibatalkan</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin akan dibatalkan?</p>
                        <form action="{{ route('transaksi.destroy', ['transaksi' => $penjualan->id]) }}" method="post"
                            style="display: none;" id="formBatal">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="yesBatal">Ya, Batal!</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            $(function() {
                $('#yesBatal').click(function() {
                    $('#formBatal').submit();
                });
            });
        </script>
    @endpush
@endsection
