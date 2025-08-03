@extends('layouts.main', ['title' => 'Transaksi'])

@section('title-content')
    <i class="fas fa-cash-register mr-2"></i>
    Transaksi
@endsection

@section('content')
  @if (session('store') == 'gagal')
        <x-alert type="danger">
            <strong>Pukimak!</strong> liat stok lu.
        </x-alert>
    @endif
<div class="row">
    <div class="col-4">
        @include('transaksi.form-code')
        <div class="card card-orange card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="cari-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="cari-produk-tab" data-toggle="pill"
                            href="#cari-produk" role="tab">
                            Cari Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="cari-pelanggan-tab" data-toggle="pill"
                            href="#cari-pelanggan" role="tab">
                            Cari Pelanggan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="cari-tabContent">
                    <div class="tab-pane fade active show" id="cari-produk" role="tabpanel">
                        @include('transaksi.cari-produk')
                    </div>
                    <div class="tab-pane fade" id="cari-pelanggan" role="tabpanel">
                        @include('transaksi.cari-pelanggan')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        @include('transaksi.form-cart')
    </div>
</div>
@endsection
