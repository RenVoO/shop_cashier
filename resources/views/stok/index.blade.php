@extends('layouts.main', ['title' => 'Stok'])
@section('title-content')
    <i class="fas fa-pallet mr-2"></i>
    Stok
@endsection

@section('content')
    @if (session('store') == 'success')
        <x-alert type="success">
            <strong>Berhasil dibuat!</strong> Stok berhasil dibuat.
        </x-alert>
    @endif
    @if (session('destroy') == 'success')
        <x-alert type="success">
            <strong>Berhasil dihapus!</strong> Stok berhasil dihapus.
        </x-alert>
    @endif

    <div class="card card-orange card-outline">
        <div class="card-header form-inline">
            <a href="{{ route('stok.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i> Tambah
            </a>
            <form action="?" method="get" class="ml-auto">
                <div class="input-group">
                    <input type="date" class="form-control" name="search" value="<?= request()->search ?>"
                        placeholder="Tanggal">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Nama Suplier</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stoks as $key => $stok)
                        <tr>
                            <td>{{ $stoks->firstItem() + $key }}</td>
                            <td>{{ $stok->nama_produk }}</td>
                            <td>{{ $stok->jumlah }}</td>
                            <td>{{ $stok->nama_suplier }}</td>
                            <td>{{ $stok->tanggal }}</td>
                            <td class="text-right">
                                <button type="button" data-toggle="modal" data-target="#modalDelete"
                                    data-url="{{ route('stok.destroy', ['stok' => $stok->id]) }}"
                                    class="btn btn-xs text-danger p-0 btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $stoks->links('vendor.pagination.bootstrap-4') }}
        </div>
    </div>
@endsection

@push('modals')
    <x-modal-delete />
@endpush
