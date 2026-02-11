<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Total Barang is always global context (available stock in warehouse)
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok');

        if ($user->role === 'client') {
            // Client sees THEIR OWN transactions
            $transaksiMasuk = transaksi::where('id_user', $user->id)->where('jenis_transaksi', 'masuk')->count();
            $transaksiKeluar = transaksi::where('id_user', $user->id)->where('jenis_transaksi', 'keluar')->count();
            $latestTransaksis = transaksi::with('user')->where('id_user', $user->id)->latest()->take(5)->get();
        } else {
            // Admin/Officer see GLOBAL transactions
            $transaksiMasuk = transaksi::where('jenis_transaksi', 'masuk')->count();
            $transaksiKeluar = transaksi::where('jenis_transaksi', 'keluar')->count();
            $latestTransaksis = transaksi::with('user')->latest()->take(5)->get();
        }

        return view('dashboard', compact('totalBarang', 'totalStok', 'transaksiMasuk', 'transaksiKeluar', 'latestTransaksis'));
    }
}
