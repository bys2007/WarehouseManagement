<?php

namespace App\Http\Controllers;

use App\Models\transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = transaksi::with(['user', 'item_transaksi.barang'])->latest();

        if (auth()->user()->role === 'client') {
            $query->where('id_user', auth()->id());
        } elseif ($request->filled('user_id')) {
            $query->where('id_user', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_transaksi', $request->jenis);
        }

        $transaksis = $query->paginate(10)->withQueryString();
        $users = \App\Models\User::all(); // Populating users for filter dropdown
        return view('transaksi.index', compact('transaksis', 'users'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin')
            abort(403);
        return view('transaksi.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin')
            abort(403);
        $request->validate([
            'tanggal_transaksi' => 'required|date',
            'id_user' => 'required|exists:users,id',
            'jenis_transaksi' => 'required|in:masuk,keluar',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $transaksi = transaksi::create([
                    'id_user' => $request->id_user,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'jenis_transaksi' => $request->jenis_transaksi,
                    'total_harga' => 0,
                ]);

                $totalHarga = 0;

                foreach ($request->items as $itemData) {
                    $barang = \App\Models\Barang::findOrFail($itemData['id_barang']);
                    $jumlah = $itemData['jumlah'];

                    // Check stock for outgoing goods
                    if ($request->jenis_transaksi == 'keluar' && $barang->stok < $jumlah) {
                        throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi. (Stok: {$barang->stok}, Permintaan: {$jumlah})");
                    }

                    $subtotal = $barang->harga_satuan * $jumlah;
                    $totalHarga += $subtotal;

                    \App\Models\ItemTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_barang' => $barang->id_barang,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $barang->harga_satuan,
                        'subtotal' => $subtotal,
                    ]);

                    // Update stock
                    if ($request->jenis_transaksi == 'masuk') {
                        $barang->increment('stok', $jumlah);
                    } else {
                        $barang->decrement('stok', $jumlah);
                    }
                }

                $transaksi->update(['total_harga' => $totalHarga]);
            });

            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(transaksi $transaksi)
    {
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(transaksi $transaksi)
    {
        // Not implemented (complex to edit transaction that affects stock history)
        return back()->with('error', 'Edit transaksi tidak didukung.');
    }

    public function update(Request $request, transaksi $transaksi)
    {
        // Not implemented
    }

    public function destroy(transaksi $transaksi)
    {
        // Implementing delete would require rolling back stock changes
        // For simplicity, we might skip or implement naive rollback
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($transaksi) {
                foreach ($transaksi->item_transaksi as $item) {
                    $barang = $item->barang;
                    if ($transaksi->jenis_transaksi == 'masuk') {
                        if ($barang->stok < $item->jumlah) {
                            throw new \Exception("Gagal hapus: Stok {$barang->nama_barang} sudah berkurang dibawah jumlah masuk.");
                        }
                        $barang->decrement('stok', $item->jumlah);
                    } else {
                        $barang->increment('stok', $item->jumlah);
                    }
                }
                $transaksi->delete();
            });
            return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
