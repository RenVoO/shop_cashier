@extends('layouts.main', ['title' => 'User'])

@section('title-content')
    <i class="fas fa-user-tie mr-2"></i>
    User
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-6">
        <form method="POST" class="card card-orange card-outline" 
              action="{{ route('user.update', ['user' => $user->id]) }}">
            <div class="card-header">
                <h3 class="card-title">Ubah User</h3>
            </div>

            <div class="card-body">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <x-input name="nama" type="text" :value="$user->nama" />
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <x-input name="username" type="text" :value="$user->username" />
                </div>

                <div class="form-group">
                    <label>Role/Peran</label>
                    <x-select name="role" :value="$user->role"
                        :options="[['', 'Pilih:'], ['petugas', 'Petugas'], ['admin', 'Administrator']]" />
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <x-input name="password_baru" type="password" />
                </div>

                <div class="form-group">
                    <label>Password Baru Konfirmasi</label>
                    <x-input name="password_baru_confirmation" type="password" />
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">
                    Update User
                </button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary ml-auto">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
