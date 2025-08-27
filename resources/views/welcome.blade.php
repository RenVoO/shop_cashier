@extends('layouts.main', ['title' => 'Home'])
@section('title-content')
    <i class="fas fa-home mr-2"></i> Home
@endsection

@section('content')
<div class="row">
    @can('admin')
        <x-box title="User" icon="fas fa-user-tie" background="bg-danger"
            :route="route('user.index')" :jumlah="$user->jumlah" />
        <x-box title="Kategori" icon="fas fa-list" background="bg-warning"
            :route="route('kategori.index')" :jumlah="$kategori->jumlah" />
    @endcan
    <x-box title="Pelanggan" icon="fas fa-users" background="bg-primary"
        :route="route('pelanggan.index')" :jumlah="$pelanggan->jumlah" />
    <x-box title="Produk" icon="fas fa-box-open" background="bg-success"
        :route="route('produk.index')" :jumlah="$produk->jumlah" />
    <x-box title="Expired" icon="fas fa-ban" background="bg-danger"
    :route="route('expired.index')" :jumlah="\App\Models\Expired::count()" />
</div>
<div class="card">
    <div class="card-body">
        <canvas id="myChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $cart['labels'] ?>,
            datasets: [{
                label: "{{ $cart['label'] }}",
                data: <?= $cart['data'] ?>,
                borderWidth: 3
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
@endpush