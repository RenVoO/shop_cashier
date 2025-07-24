<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kategoris = Kategori::orderBy('id')
            ->when($search, function ($q, $search) {
                return $q->where('nama_kategori', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $kategoris->appends(['search' => $search]);

        return view('kategori.index', [
            'kategoris' => $kategoris
        ]);
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => ['required', 'max:100'],
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')->with('store', 'success');
    }

    public function show(Kategori $kategori)
    {
        abort(404);
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', [
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => ['required', 'max:100'],
        ]);

        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('update', 'success');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return back()->with('destroy', 'success');
    }
}
