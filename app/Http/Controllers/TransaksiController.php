<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetilPenjualan;
use App\Models\Pelanggan;
use App\Models\Diskon;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Cart;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $penjualans = Penjualan::join('users', 'users.id', 'penjualans.user_id')
            ->join('pelanggans', 'pelanggans.id', 'penjualans.pelanggan_id')
            ->select('penjualans.*', 'users.nama as nama_kasir', 'pelanggans.nama as nama_pelanggan')
            ->orderBy('id', 'desc')
            ->when($search, function ($q, $search) {
                return $q->where('nomor_transaksi', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $penjualans->appends(['search' => $search]);

        return view('transaksi.index', [
            'penjualans' => $penjualans
        ]);
    }

    public function create(Request $request)
    {
        return view('transaksi.create', [
            'nama_kasir' => $request->user()->nama,
            'tanggal' => date('d F Y')
        ]);
    }
    
public function store(Request $request)
    {
    $request->validate([
        'pelanggan_id' => ['required', 'exists:pelanggans,id'],
        'cash' => ['required']
    ], [], [
        'pelanggan_id' => 'pelanggan'
    ]);

    $user = $request->user();
    $cart = Cart::name($user->id);
    $cartDetails = $cart->getDetails();

    // Bersihkan format cash dari titik/koma
    $cash = preg_replace('/[^\d]/', '', $request->cash);

    $total = $cartDetails->get('total');
    $diskonSession = session('diskon');
    $discount = 0;

    // 1. Hitung total diskon global
    if ($diskonSession && isset($diskonSession['jumlah'])) {
        if ($diskonSession['tipe'] === 'persen') {
            $discount = ($diskonSession['jumlah'] / 100) * $cartDetails->get('subtotal');
        } elseif ($diskonSession['tipe'] === 'nominal') {
            $discount = $diskonSession['jumlah'];
        }
    }

    // 2. Generate nomor transaksi
    $lastPenjualan = Penjualan::orderBy('id', 'desc')->first();
    $no = $lastPenjualan ? $lastPenjualan->id + 1 : 1;
    $no = sprintf("%04d", $no);

    $fixtotal = $total - $discount;
    $kembalian = $cash - $fixtotal;

    // 3. Cek cash
    if ($cash < $fixtotal) {
        return redirect()->back()
            ->withErrors(['cash' => 'Cash kurang dari total yang harus dibayar.'])
            ->withInput();
    }

    // 4. Cek stok semua produk sebelum membuat Penjualan
    $stokKurang = [];
    foreach ($cartDetails->get('items') as $item) {
        $produk = Produk::find($item->id);
        if (!$produk || $produk->stok < $item->quantity) {
            $stokKurang[] = $produk ? $produk->nama_produk : "Produk tidak ditemukan";
        }
    }

    if (!empty($stokKurang)) {
        return redirect()
            ->route('transaksi.create')
            ->with('store', 'gagal')
            ->with('stok_kurang', $stokKurang);
    }

    // 5. Simpan data Penjualan
    $penjualan = Penjualan::create([
        'user_id' => $user->id,
        'pelanggan_id' => $cart->getExtraInfo('pelanggan.id'),
        'nomor_transaksi' => date('Ymd') . $no,
        'tanggal' => now(),
        'subtotal' => $cartDetails->get('subtotal'),
        'pajak' => $cartDetails->get('tax_amount'),
        'diskon' => $discount,
        'total' => $fixtotal,
        'tunai' => $cash,
        'kembalian' => $kembalian
    ]);

    // 6. Simpan detail penjualan & kurangi stok
    foreach ($cartDetails->get('items') as $item) {
        $produk = Produk::find($item->id);

        $diskonNominal = 0;
        if ($diskonSession && isset($diskonSession['jumlah'])) {
            if (isset($diskonSession['produk_id']) && $diskonSession['produk_id'] == $produk->id) {
                if ($diskonSession['tipe'] === 'persen') {
                    $diskonNominal = ($item->subtotal * $diskonSession['jumlah']) / 100;
                } elseif ($diskonSession['tipe'] === 'nominal') {
                    $diskonNominal = $diskonSession['jumlah'] * $item->quantity;
                }
            }
        }

        DetilPenjualan::create([
            'penjualan_id' => $penjualan->id,
            'produk_id' => $item->id,
            'harga_produk' => $item->price,
            'jumlah' => $item->quantity,
            'subtotal' => $item->subtotal,
            'diskon' => $diskonNominal
        ]);

        if ($produk) {
            $produk->stok -= $item->quantity;
            $produk->save();
        }
    }

    // 7. Bersihkan keranjang & session diskon
    $cart->destroy();
    session()->forget('diskon');

    return redirect()->route('transaksi.show', ['transaksi' => $penjualan->id]);
    }





    public function show(Request $request, Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detilPenjualan = DetilPenjualan::join('produks', 'produks.id', 'detil_penjualans.produk_id')
            ->select('detil_penjualans.*', 'nama_produk')
            ->where('penjualan_id', $transaksi->id)->get();

        return view('transaksi.invoice', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detilPenjualan' => $detilPenjualan
        ]);
    }

    public function destroy(Request $request, Penjualan $transaksi)
    {
        $transaksi->update([
            'status' => 'batal'
        ]);
        
        $detail =  DetilPenjualan::where('penjualan_id', $transaksi->id)->get();

        foreach($detail as $item){
            $produk = Produk::find($item->produk_id);
            if($produk){
                $produk->stok += $item->jumlah;
                $produk->save();
            }
        }

        return back()->with('destroy', 'success');
    }

    public function produk(Request $request)
    {
        $search = $request->search;
        $produks = Produk::select('id', 'kode_produk', 'nama_produk')
            ->when($search, function ($q, $search) {
                return $q->where('nama_produk', 'like', "%{$search}%");
            })
            ->orderBy('nama_produk')
            ->take(15)
            ->get();

        return response()->json($produks);
    }

    public function pelanggan(Request $request)
    {
        $search = $request->search;
        $pelanggans = Pelanggan::select('id', 'nama')
            ->when($search, function ($q, $search) {
                return $q->where('nama', 'like', "%{$search}%");
            })
            ->orderBy('nama')
            ->take(15)
            ->get();

        return response()->json($pelanggans);
    }

    public function addPelanggan(Request $request)
    {
        $request->validate([
            'id' => ['required', 'exists:pelanggans']
        ]);

        $pelanggan = Pelanggan::find($request->id);
        $cart = Cart::name($request->user()->id);

        $cart->setExtraInfo([
            'pelanggan' => [
                'id' => $pelanggan->id,
                'nama' => $pelanggan->nama
            ]
        ]);

        return response()->json(['message' => 'Berhasil.']);
    }

    public function cetak(Penjualan $transaksi)
    {
        $pelanggan = Pelanggan::find($transaksi->pelanggan_id);
        $user = User::find($transaksi->user_id);
        $detilPenjualan = DetilPenjualan::join('produks', 'produks.id', 'detil_penjualans.produk_id')
            ->select('detil_penjualans.*', 'nama_produk')
            ->where('penjualan_id', $transaksi->id)->get();

        return view('transaksi.cetak', [
            'penjualan' => $transaksi,
            'pelanggan' => $pelanggan,
            'user' => $user,
            'detilPenjualan' => $detilPenjualan
        ]);
    }
}