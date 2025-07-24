@extends('layouts.main', ['title' => 'Profile'])
@section('title-content')
    <i class="fas fa-user mr-2"></i>
    Profile
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-4 col-lg-6">
          @if (session('update') == 'success')
            <x-alert type="success">
                <strong>Berhasil diupdate!</strong> Profile berhasil diupdate.
            </x-alert>
          @endif
          <div class="card card-orange card-outline">
            <div class="card-body">
                <p>Nama : <strong>{{ $user->nama }}</strong></p>
                <p>Username : {{ $user->username }}</p>
                <p>Dibuat Tanggal : {{ $user->created_at }}</p>
                <p>Diupdate Tanggal : {{ $user->updated_at }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
            </div>
          </div>
        </div>
    </div>
@endsection