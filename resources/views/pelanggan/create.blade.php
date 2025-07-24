@extends('layouts.main', ['title' => 'Pelanggan'])

@section('title-content')
    <i class="fas fa-users mr-2"></i>
    Pelanggan
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-6">
        <form method="POST" action="{{ route('pelanggan.store') }}"
            class="card card-orange card-outline">
            <div class="card-header">
                <h3 class="card-title">Buat Pelanggan Baru</h3>
            </div>

            <div class="card-body">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <x-input name="nama" type="text" />
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <x-textarea name="alamat" />
                </div>
                <div class="form-group">
                    <label>Nomor Telepon/HP</label>
                    <x-input name="nomor_tlp" type="text" />
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">
                    Simpan Pelanggan
                </button>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary ml-auto">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
