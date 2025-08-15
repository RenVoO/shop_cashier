@extends('layouts.main', ['title' => 'Edit Diskon'])

@section('content')
<form action="{{ route('diskon.update', $diskon->id) }}" method="POST">
    @csrf @method('PUT')
    @include('diskon.form', ['diskon' => $diskon])
</form>
@endsection
