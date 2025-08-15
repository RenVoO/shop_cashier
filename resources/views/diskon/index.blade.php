@extends('layouts.main', ['title' => 'Diskon'])

@section('title-content')
<i class="fas fa-tag mr-2"></i> Manajemen Diskon
@endsection

@section('content')
<div class="card card-orange card-outline shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="m-0">Daftar Diskon</h5>
        <a href="{{ route('diskon.create') }}" class="btn btn-outline-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Diskon
        </a>
    </div>
    <div class="card-body table-responsive bg-white">
        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Kode Kupon</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Kategori</th>
                    <th>Produk</th>
                    <th>Min. Belanja</th>
                    <th>Kadaluarsa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($diskons as $diskon)
                <tr>
                    <td>{{ $diskon->kode_kupon }}</td>
                    <td>{{ ucfirst($diskon->tipe_diskon) }}</td>
                    <td>
                        {{ $diskon->tipe_diskon == 'persen' ? $diskon->jumlah_diskon . '%' : 'Rp' . number_format($diskon->jumlah_diskon, 0, ',', '.') }}
                    </td>
                    <td>{{ $diskon->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $diskon->produk->nama_produk ?? '-' }}</td>
                    <td>{{ $diskon->minimal_belanja ? 'Rp' . number_format($diskon->minimal_belanja, 0, ',', '.') : '-' }}</td>
                    <td>{{ $diskon->tanggal_kadaluarsa }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('diskon.edit', $diskon->id) }}" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('diskon.destroy', $diskon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
