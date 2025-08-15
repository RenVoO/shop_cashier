@extends('layouts.main', ['title' => 'Tambah Diskon'])

@section('content')
<form action="{{ route('diskon.store') }}" method="POST">
    @csrf
    @include('diskon.form')
</form>
@endsection
