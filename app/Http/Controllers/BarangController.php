<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $barangs = $query->paginate(10)->withQueryString();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        if (auth()->user()->role === 'client')
            abort(403);
        return view('barang.form');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'client')
            abort(403);
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        if (auth()->user()->role === 'client')
            abort(403);
        return view('barang.form', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        if (auth()->user()->role === 'client')
            abort(403);
        $validated = $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang,' . $barang->id_barang . ',id_barang',
            'nama_barang' => 'required',
            'stok' => 'required|integer|min:0',
            'harga_satuan' => 'required|integer|min:0',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if (auth()->user()->role === 'client')
            abort(403);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
