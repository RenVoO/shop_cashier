<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Diskon;
use Illuminate\Support\Facades\Session;
use Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::name($request->user()->id);

        $cart->applyTax([
            'id' => 1,
            'rate' => 10,
            'title' => 'Pajak PPN 10%'
        ]);

        $details = $cart->getDetails();
        $subtotal = $details->get('subtotal');

        $diskon = session('diskon');
        $potongan = 0;

        if ($diskon && isset($diskon['kode'])) {
            if ($diskon['tipe'] === 'persen') {
                $potongan = ($diskon['jumlah'] / 100) * $subtotal;
            } else {
                $potongan = $diskon['jumlah'];
            }

            $potongan = min($potongan, $subtotal); // tidak melebihi subtotal
        }

        $details->put('diskon', $potongan);
        $details->put('total', $details->get('total') - $potongan);

        return $details->toJson();
    }

    public function store(Request $request)
    {
    $request->validate([
        'kode_produk' => ['required', 'exists:produks,kode_produk'],
        'quantity' => ['required', 'integer', 'min:1']
    ]);

    $produk = Produk::where('kode_produk', $request->kode_produk)->first();

    // Validasi stok
    if ($produk->stok < $request->quantity) {
        return response()->json([
            'message' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $produk->stok
        ], 400);
    }

    $cart = Cart::name($request->user()->id);

    $cart->addItem([
        'id' => $produk->id,
        'title' => $produk->nama_produk,
        'quantity' => $request->quantity,
        'price' => $produk->harga,
        'options'=>[
            'diskon'=>$produk->diskon,
            'harga_produk'=>$produk->harga_produk,
        ]
    ]);

    return response()->json(['message' => 'Berhasil ditambahkan.']);
}


    public function update(Request $request, $hash)
    {
        $request->validate([
            'qty' => ['required', 'in:-1,1']
        ]);

        $cart = Cart::name($request->user()->id);
        $item = $cart->getItem($hash);

        if (!$item) {
            return abort(404);
        }

        $cart->updateItem($item->getHash(), [
            'quantity' => $item->getQuantity() + $request->qty
        ]);

        return response()->json(['message' => 'Berhasil diupdate.']);
    }

    public function destroy(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $cart->removeItem($hash);

        return response()->json(['message' => 'Berhasil dihapus.']);
    }

    public function clear(Request $request)
    {
        $cart = Cart::name($request->user()->id);
        $cart->destroy();

        // Hapus diskon saat cart dibersihkan
        session()->forget('diskon');

        return back();
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'kode_kupon' => 'required'
        ]);

        $cart = Cart::name($request->user()->id);
        $subtotal = $cart->getDetails()->get('subtotal');

        $kupon = Diskon::where('kode_kupon', $request->kode_kupon)->first();

        if (!$kupon) {
            return response()->json(['error' => 'Kupon tidak ditemukan.'], 404);
        }

        if ($kupon->tanggal_kadaluarsa && now()->gt($kupon->tanggal_kadaluarsa)) {
            return response()->json(['error' => 'Kupon telah kedaluwarsa.'], 400);
        }

        if ($kupon->minimal_belanja && $subtotal < $kupon->minimal_belanja) {
            return response()->json(['error' => 'Minimal belanja tidak mencukupi.'], 400);
        }

        $items = $cart->getItems();
        $eligible = false;

        foreach ($items as $item) {
            $produk = Produk::find($item->get('id'));

            if ($kupon->produk_id && $produk->id == $kupon->produk_id) {
                $eligible = true;
                break;
            }

            if ($kupon->kategori_id && $produk->kategori_id == $kupon->kategori_id) {
                $eligible = true;
                break;
            }

            if (!$kupon->produk_id && !$kupon->kategori_id) {
                $eligible = true;
                break;
            }
        }

        if (!$eligible) {
            return response()->json(['error' => 'Kupon tidak berlaku untuk produk dalam keranjang.'], 400);
        }

        // Simpan ke session dan cart extra info
        Session::put('diskon', [
            'id' => $kupon->id,
            'kode' => $kupon->kode_kupon,
            'tipe' => $kupon->tipe_diskon,
            'jumlah' => $kupon->jumlah_diskon
        ]);

        $cart->setExtraInfo([
            'diskon' => $kupon->tipe_diskon === 'persen'
                ? min(($kupon->jumlah_diskon / 100) * $subtotal, $subtotal)
                : min($kupon->jumlah_diskon, $subtotal)
        ]);

        return response()->json(['message' => 'Kupon berhasil diterapkan.']);
    }
}
