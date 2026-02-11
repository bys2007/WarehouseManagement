@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-blue-500 rounded-xl p-6 text-white shadow-lg shadow-blue-500/30">
      <h3 class="text-lg font-semibold opacity-90">Total Barang</h3>
      <p class="text-4xl font-bold mt-2">{{ $totalBarang }}</p>
      <p class="text-sm opacity-75 mt-1">{{ number_format($totalStok) }} items</p>
    </div>
    <div class="bg-green-500 rounded-xl p-6 text-white shadow-lg shadow-green-500/30">
      <h3 class="text-lg font-semibold opacity-90">Transaksi Masuk</h3>
      <p class="text-4xl font-bold mt-2">{{ $transaksiMasuk }}</p>
    </div>
    <div class="bg-red-500 rounded-xl p-6 text-white shadow-lg shadow-red-500/30">
      <h3 class="text-lg font-semibold opacity-90">Transaksi Keluar</h3>
      <p class="text-4xl font-bold mt-2">{{ $transaksiKeluar }}</p>
    </div>
  </div>

  <div class="mt-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
      {{ auth()->user()->role === 'client' ? 'Riwayat Transaksi Saya' : 'Transaksi Terakhir' }}
    </h3>
    <div class="overflow-x-auto bg-white rounded-lg border border-gray-100">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
          <tr>
            <th class="px-6 py-3">Tanggal</th>
            <th class="px-6 py-3">User</th>
            <th class="px-6 py-3">Jenis</th>
            <th class="px-6 py-3 text-right">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($latestTransaksis as $transaksi)
            <tr>
              <td class="px-6 py-4">{{ $transaksi->tanggal_transaksi }}</td>
              <td class="px-6 py-4">{{ $transaksi->user->name ?? 'User' }}</td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 rounded-full text-xs font-semibold {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                  {{ ucfirst($transaksi->jenis_transaksi) }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-4 text-center text-gray-400">Belum ada transaksi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection