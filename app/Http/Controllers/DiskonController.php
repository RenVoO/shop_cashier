<?php

// app/Http/Controllers/DiskonController.php
namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Kategori;
use App\Models\Produk;
use App\Http\Requests\DiskonRequest;

class DiskonController extends Controller
{
    public function index()
    {
        $diskons = Diskon::with(['kategori', 'produk'])->latest()->get();
        return view('diskon.index', compact('diskons'));
    }

    public function create()
    {
        return view('diskon.create', [
            'kategoris' => Kategori::all(),
            'produks' => Produk::all()
        ]);
    }

    public function store(DiskonRequest $request)
    {
        Diskon::create($request->validated());
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil ditambahkan');
    }

    public function edit(Diskon $diskon)
    {
        return view('diskon.edit', [
            'diskon' => $diskon,
            'kategoris' => Kategori::all(),
            'produks' => Produk::all()
        ]);
    }

    public function update(DiskonRequest $request, Diskon $diskon)
    {
        $diskon->update($request->validated());
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil diperbarui');
    }

    public function destroy(Diskon $diskon)
    {
        $diskon->delete();
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dihapus');
    }
}

