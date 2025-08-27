<?php

namespace App\Http\Controllers;

use App\Models\Expired;
use App\Models\Produk;
use Illuminate\Http\Request;

class ExpiredController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $expireds = Expired::with('produk')
            ->orderBy('id', 'desc')
            ->when($search, function ($q, $search) {
                return $q->whereDate('tanggal', $search);
            })
            ->paginate();

        return view('expired.index', compact('expireds'));
    }

    public function create()
    {
        return view('expired.create');
    }

    public function produk(Request $request)
    {
        $produks = Produk::select('id', 'nama_produk', 'stok')
            ->where('nama_produk', 'like', "%{$request->search}%")
            ->take(15)
            ->get();

        return response()->json($produks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
            'jumlah' => ['required', 'numeric', 'min:1']
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        if ($request->jumlah > $produk->stok) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia anjay']);
        }

        $expired = Expired::create([
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal' => now()->toDateString(),
        ]);

        $produk->update([
            'stok' => $produk->stok - $request->jumlah
        ]);

        return redirect()->route('expired.index')->with('store', 'success');
    }

    public function destroy(Expired $expired)
    {
        // kalau dihapus, stok barang dikembalikan lagi
        $produk = $expired->produk;
        $produk->update([
            'stok' => $produk->stok + $expired->jumlah
        ]);

        $expired->delete();

        return back()->with('destroy', 'success');
    }
}
