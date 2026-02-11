@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="flex flex-col mb-6 gap-4">
        <div class="flex justify-between items-center">
            <p class="text-gray-500">Daftar riwayat transaksi masuk dan keluar.</p>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('transaksi.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium text-sm">
                    + Tambah Transaksi
                </a>
            @endif
        </div>

        <form action="{{ route('transaksi.index') }}" method="GET"
            class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Jenis Transaksi</label>
                <select name="jenis"
                    class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
            </div>
            @if(auth()->user()->role !== 'client')
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Filter User</label>
                    <select name="user_id"
                        class="w-full rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="sm:col-span-4 flex justify-end">
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition text-sm">Filter
                    Data</button>
                <a href="{{ route('transaksi.index') }}"
                    class="ml-2 px-4 py-2 text-gray-600 hover:text-gray-900 text-sm flex items-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Jenis</th>
                    <th class="px-6 py-3">Detail Barang</th>
                    <th class="px-6 py-3 text-right">Total Harga</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">{{ $transaksi->tanggal_transaksi }}</td>
                        <td class="px-6 py-4">{{ $transaksi->user->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-semibold {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($transaksi->jenis_transaksi) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <ul class="list-disc list-inside text-xs">
                                @foreach($transaksi->item_transaksi as $item)
                                    <li>{{ $item->barang->nama_barang }} ({{ $item->jumlah }}x)</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('transaksi.show', $transaksi) }}"
                                class="text-blue-600 hover:text-blue-800 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">Belum ada transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $transaksis->links() }}
        </div>
    </div>
@endsection