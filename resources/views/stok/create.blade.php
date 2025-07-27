@extends('layouts.main', ['title' => 'Stok'])
@section('title-content')
    <i class="fas fa-pallet mr-2"></i>
    Stok
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-6">
        <form method="POST" action="{{ route('stok.store') }}" class="card card-orange card-outline">
            <div class="card-header">
                <h3 class="card-title">Tambah Stok Barang</h3>
            </div>

            <div class="card-body">
                @csrf
                <div class="form-group">
                    <label>Nama Produk</label>
                    <div class="input-group">
                        <input type="text" id="namaProduk" name="nama_produk"
                            class="form-control @error('produk_id') is-invalid @enderror" disabled>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalCari">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    @error('produk_id')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                    <input type="hidden" name="produk_id" id="produkId">
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <x-input name="jumlah" type="text" />
                </div>

                <div class="form-group">
                    <label>Nama Suplier</label>
                    <x-input name="nama_suplier" type="text" />
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">Simpan Stok</button>
                <a href="{{ route('stok.index') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="modalCari" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSearch" action="" method="get" class="input-group">
                    <input type="text" class="form-control" id="search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <table class="table table-sm table-striped table-hover mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="resultProduk"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    $(function () {
        $('#formSearch').submit(function (e) {
            e.preventDefault();
            let search = $(this).find('#search').val();
            if (search.length >= 3) {
                fetchProduk(search);
            }
        });
    });

    function fetchProduk(search) {
        let url = "{{ route('stok.produk') }}?search=" + search;
        $.getJSON(url, function (result) {
            $('#resultProduk').html('');
            result.forEach((produk, index) => {
                let row = `<tr>`;
                row += `<td>${index + 1}</td>`;
                row += `<td>${produk.nama_produk}</td>`;
                row += `<td class="text-right">`;
                row += `<button type="button" class="btn btn-xs btn-success"
                            onclick="addProduk(${produk.id}, '${produk.nama_produk}')">Add</button>`;
                row += `</td>`;
                row += `</tr>`;
                $('#resultProduk').append(row);
            });
        });
    }

    function addProduk(id, nama_produk) {
        $('#namaProduk').val(nama_produk);
        $('#produkId').val(id);
        $('#modalCari').modal('hide');
    }
</script>
@endpush
