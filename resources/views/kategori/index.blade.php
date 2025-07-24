@extends('layouts.main', ['title' => 'Kategori'])
@section('title-content')
  <i class="fas fa-list mr-2"></i>
  Kategori
@endsection

@section('content')
  @if (session('store') == 'success')
    <x-alert type="success">
      <strong>Berhasil dibuat!</strong> Kategori berhasil dibuat.
    </x-alert>
  @endif
  @if (session('update') == 'success')
    <x-alert type="success">
      <strong>Berhasil diupdate!</strong> Kategori berhasil diupdate.
    </x-alert>
  @endif
  @if (session('destroy') == 'success')
    <x-alert type="success">
      <strong>Berhasil dihapus!</strong> Kategori berhasil dihapus.
    </x-alert>
  @endif

  <div class="card card-orange card-outline">
    <div class="card-header form-inline">
      <a href="{{ route('kategori.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i> Tambah
      </a>
      <form action="?" method="get" class="ml-auto">
        <div class="input-group">
          <input type="text" class="form-control" name="search" value="{{ request()->search }}" placeholder="Nama Kategori">
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
            <th>Nama Kategori</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($kategoris as $key => $kategori)
            <tr>
              <td>{{ $kategoris->firstItem() + $key }}</td>
              <td>{{ $kategori->nama_kategori }}</td>
              <td class="text-right">
                <a href="{{ route('kategori.edit', ['kategori' => $kategori->id]) }}"
                   class="btn btn-xs text-success p-0 mr-1">
                  <i class="fas fa-edit"></i>
                </a>

                <button type="button" data-toggle="modal" data-target="#modalDelete"
                        data-url="{{ route('kategori.destroy', ['kategori' => $kategori->id]) }}"
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
      {{ $kategoris->links('vendor.pagination.bootstrap-4') }}
    </div>
  </div>
@endsection

@push('modals')
  <x-modal-delete />
@endpush
