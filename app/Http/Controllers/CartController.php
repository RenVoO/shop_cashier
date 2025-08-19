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
        $diskon = session('diskon');
        $potongan = 0;

        // Hitung diskon per item, bukan global
        if ($diskon && isset($diskon['kode'])) {
            $items = $cart->getItems();
            
            foreach ($items as $item) {
                $produk = Produk::find($item->get('id'));
                
                // Cek apakah produk ini memenuhi syarat kupon
                $isEligible = $this->checkProductEligibility($produk, $diskon);
                
                if ($isEligible) {
                    if ($diskon['tipe'] === 'persen') {
                        $itemDiscount = ($item->getSubTotal() * $diskon['jumlah']) / 100;
                    } else {
                        // Untuk diskon nominal, bagi rata sesuai quantity
                        $itemDiscount = $diskon['jumlah'] * $item->getQuantity();
                    }
                    
                    $potongan += $itemDiscount;
                }
            }
            
            // Pastikan diskon tidak melebihi subtotal
            $potongan = min($potongan, $details->get('subtotal'));
        }

        // Set diskon dan hitung ulang total yang benar
        $details->put('diskon', $potongan);
        
        // Total setelah pajak dan diskon
        $subtotal = $details->get('subtotal');
        $pajak = $details->get('tax_amount');
        $totalSetelahPajak = $subtotal + $pajak;
        $totalFinal = $totalSetelahPajak - $potongan;
        
        $details->put('total', $totalFinal);

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
                'kategori_id' => $produk->kategori_id,
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

        $newQuantity = $item->getQuantity() + $request->qty;
        
        // Validasi jika quantity menjadi 0 atau kurang
        if ($newQuantity <= 0) {
            $cart->removeItem($hash);
            return response()->json(['message' => 'Item dihapus dari keranjang.']);
        }

        // Validasi stok sebelum update
        $produk = Produk::find($item->get('id'));
        if ($produk && $produk->stok < $newQuantity) {
            return response()->json([
                'message' => 'Stok produk tidak mencukupi. Stok tersedia: ' . $produk->stok
            ], 400);
        }

        $cart->updateItem($item->getHash(), [
            'quantity' => $newQuantity
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

        // Validasi minimal belanja
        if ($kupon->minimal_belanja && $subtotal < $kupon->minimal_belanja) {
            return response()->json(['error' => 'Minimal belanja tidak mencukupi untuk kupon ini. Minimal: ' . number_format($kupon->minimal_belanja, 0, ',', '.')], 400);
        }

        $items = $cart->getItems();
        $eligibleProducts = [];
        $eligibleSubtotal = 0;

        // Cek produk mana saja yang memenuhi syarat kupon
        foreach ($items as $item) {
            $produk = Produk::find($item->get('id'));
            
            if ($this->checkProductEligibility($produk, [
                'produk_id' => $kupon->produk_id,
                'kategori_id' => $kupon->kategori_id
            ])) {
                $eligibleProducts[] = $produk;
                $eligibleSubtotal += $item->getSubTotal();
            }
        }

        if (empty($eligibleProducts)) {
            return response()->json(['error' => 'Kupon tidak berlaku untuk produk dalam keranjang.'], 400);
        }

        // Validasi minimal belanja untuk produk yang memenuhi syarat
        if ($kupon->minimal_belanja && $eligibleSubtotal < $kupon->minimal_belanja) {
            return response()->json(['error' => 'Subtotal produk yang memenuhi syarat kupon belum mencukupi minimal belanja. Minimal: ' . number_format($kupon->minimal_belanja, 0, ',', '.')], 400);
        }

        // Simpan informasi kupon ke session dengan data lengkap
        Session::put('diskon', [
            'id' => $kupon->id,
            'kode' => $kupon->kode_kupon,
            'tipe' => $kupon->tipe_diskon,
            'jumlah' => $kupon->jumlah_diskon,
            'produk_id' => $kupon->produk_id,
            'kategori_id' => $kupon->kategori_id,
            'minimal_belanja' => $kupon->minimal_belanja
        ]);

        return response()->json(['message' => 'Kupon berhasil diterapkan untuk produk yang memenuhi syarat.']);
    }

    /**
     * Cek apakah produk memenuhi syarat untuk mendapat diskon
     */
    private function checkProductEligibility($produk, $diskonData)
    {
        // Jika kupon spesifik untuk produk tertentu
        if (isset($diskonData['produk_id']) && $diskonData['produk_id']) {
            return $produk->id == $diskonData['produk_id'];
        }

        // Jika kupon untuk kategori tertentu
        if (isset($diskonData['kategori_id']) && $diskonData['kategori_id']) {
            return $produk->kategori_id == $diskonData['kategori_id'];
        }

        // Jika kupon berlaku umum (tidak ada pembatasan produk/kategori)
        if ((!isset($diskonData['produk_id']) || !$diskonData['produk_id']) && 
            (!isset($diskonData['kategori_id']) || !$diskonData['kategori_id'])) {
            return true;
        }

        return false;
    }
}