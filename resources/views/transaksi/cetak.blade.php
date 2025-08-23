<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faktur Pembayaran</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        .invoice {
            width: 70mm;
        }

        table {
            width: 100%;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        hr {
            border-top: 1px solid #8c8b8b;
        }
    </style>
</head>

<body onload="javascript:window.print()">
    <div class="invoice">
        <h3 class="center">{{ config('app.name') }}</h3>
        <p class="center">
            Jl. Raya Padaherang Km.1, Desa Padaherang <br>
            Kec.Padaherang - Kab.Pangandaran
        </p>
        <hr>
        <p>
    Kode Transaksi : {{ $penjualan->kode }} <br>
    Tanggal : {{ date('d/m/Y H:i:s', strtotime($penjualan->tanggal)) }} <br>
    Pelanggan : {{ $pelanggan->nama ?? 'Pelanggan' }} <br>
    Kasir : {{ $user->nama }}
</p>

        <table>
    @foreach ($detilPenjualan as $row)
        @php
            $subtotalSetelahDiskon = $row->subtotal - $row->diskon;
        @endphp
        <tr>
            <td>
                {{ $row->jumlah }} x {{ $row->nama_produk }} x {{ number_format($row->harga_produk, 0, ',', '.') }}
            </td>
            <td class="right">
                {{ number_format($subtotalSetelahDiskon, 0, ',', '.') }}
            </td>
        </tr>
        @if($row->diskon > 0)
            <tr>
                <td colspan="2" class="right">
                    <small style="color:red;">Diskon Kupon: -{{ number_format($row->diskon, 0, ',', '.') }}</small>
                </td>
            </tr>
        @endif
    @endforeach
</table>
        <hr>
        <p class="right">
            Sub Total : {{ number_format($penjualan->subtotal, 0, ',', '.') }} <br>
            Pajak PPN(10%) : {{ number_format($penjualan->pajak, 0, ',', '.') }} <br>
            @if ($penjualan->diskon)
                <p>Diskon : -{{ number_format($penjualan->diskon, 0, ',', '.') }}</p>
            @endif
            Total : {{ number_format($penjualan->total, 0, ',', '.') }} <br>
            Tunai : {{ number_format($penjualan->tunai, 0, ',', '.') }} <br>
            Kembalian : {{ number_format($penjualan->kembalian, 0, ',', '.') }}
        </p>
        <h3 class="center">Terima Kasih</h3>
    </div>
</body>

</html>
