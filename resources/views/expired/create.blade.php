@extends('layouts.main', ['title' => 'Barang Expired'])

@section('title-content')
    <i class="fas fa-ban mr-2"></i>
    Barang Expired
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-6">
        <form method="POST" action="{{ route('expired.store') }}" class="card card-danger card-outline">
            <div class="card-header">
                <h3 class="card-title">Tambah Barang Expired</h3>
            </div>

            <div class="card-body">
                @csrf
                <div class="form-group">
                    <label>Nama Produk</label>
                    <div class="input-group">
                        <input type="text" id="namaProduk" class="form-control" disabled>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modalCari">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="produk_id" id="produkId">
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <x-input name="jumlah" type="number" min="1" />
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-danger">Simpan</button>
                <a href="{{ route('expired.index') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('modals')
<div class="modal fade" id="modalCari" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Produk</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formSearch" class="input-group">
                    <input type="text" class="form-control" id="search">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <table class="table table-sm mt-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
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
            let search = $('#search').val();
            if (search.length >= 2) {
                fetchProduk(search);
            }
        });
    });

    function fetchProduk(search) {
        let url = "{{ route('expired.produk') }}?search=" + search;
        $.getJSON(url, function (result) {
            $('#resultProduk').html('');
            result.forEach((produk, index) => {
                let row = `<tr>
                    <td>${index + 1}</td>
                    <td>${produk.nama_produk}</td>
                    <td>${produk.stok}</td>
                    <td class="text-right">
                        <button type="button" class="btn btn-xs btn-success"
                            onclick="addProduk(${produk.id}, '${produk.nama_produk}')">Add</button>
                    </td>
                </tr>`;
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
