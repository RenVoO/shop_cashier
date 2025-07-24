@extends('layouts.main', ['title' => 'Profile'])
@section('title-content')
    <i class="fas fa-user mr-2"></i>
    Profile
@endsection
@section('content')
    <div class="row">
      <div class="col-xl-4 col-lg-6">
        <form class="card card-orange card-outline" method="post" action="{{ route('profile.update') }}">
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
                    <label>Password Baru</label>
                    <x-input name="password_baru" type="password" />
                </div>
                <div class="form-group">
                    <label>Password Baru Konfirmasi</label>
                    <x-input name="password_baru_confirmation" type="password" />
                </div>
            </div>
            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
      </div>
    </div>
@endsection