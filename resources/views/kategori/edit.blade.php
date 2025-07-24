@extends('layouts.main', ['title' => 'Kategori'])
@section('title-content')
    <i class="fas fa-list mr-2"></i>
    Kategori
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <form method="POST" class="card card-orange card-outline"
                action="{{ route('kategori.update', [
                    'kategori' => $kategori->id,
                ]) }}">
                <div class="card-header">
                    <h3 class="card-title">Ubah Kategori</h3>
                </div>
                <div class="card-body">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <x-input name="nama_kategori" type="text" :value="$kategori->nama_kategori" />
                    </div>
                </div>
                <div class="card-footer form-inline">
                    <button type="submit" class="btn btn-primary">
                        Update Kategori
                    </button>
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary ml-auto">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
