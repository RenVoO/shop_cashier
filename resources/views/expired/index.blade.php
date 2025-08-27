@extends('layouts.main', ['title' => 'Barang Expired'])

@section('title-content')
    <i class="fas fa-ban mr-2"></i>
    Barang Expired
@endsection

@section('content')
<div class="card card-danger card-outline">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Barang Expired</h3>
        <a href="{{ route('expired.create') }}" class="btn btn-danger btn-sm">
            <i class="fas fa-plus"></i> Tambah
        </a>
    </div>
    <div class="card-body table-responsive">
        <form method="get" class="form-inline mb-3">
            <input type="date" name="search" class="form-control mr-2" value="{{ request('search') }}">
            <button class="btn btn-primary btn-sm">Cari</button>
        </form>

        <table class="table table-sm table-bordered table-striped">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th>Nama Produk</th>
                    <th width="10%">Jumlah</th>
                    <th width="15%">Tanggal</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($expireds as $index => $row)
                    <tr>
                        <td>{{ $expireds->firstItem() + $index }}</td>
                        <td>{{ $row->produk->nama_produk }}</td>
                        <td>{{ $row->jumlah }}</td>
                        <td>{{ $row->tanggal }}</td>
                        <td class="text-center">
                            <form action="{{ route('expired.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data expired</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            {{ $expireds->links() }}
        </div>
    </div>
</div>
@endsection
