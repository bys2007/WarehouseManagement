@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="mb-6 flex justify-between items-center no-print">
        <a href="{{ route('transaksi.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
            &larr; Kembali
        </a>
        <button onclick="window.print()"
            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition shadow-sm">
            Cetak Invoice
        </button>
    </div>

    <div class="border border-gray-200 rounded-xl p-8 bg-white" id="invoice">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">INVOICE</h1>
                <p class="text-gray-500">#{{ str_pad($transaksi->id_transaksi, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-gray-900">Warehouse Simulation</p>
                <p class="text-gray-500 text-sm">Jl. Pendidikan No. 1</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8 border-t border-b border-gray-100 py-6">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal</p>
                <p class="text-gray-900 font-medium">
                    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Jenis Transaksi</p>
                <span
                    class="px-2 py-1 rounded-full text-xs font-bold {{ $transaksi->jenis_transaksi == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ strtoupper($transaksi->jenis_transaksi) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">User</p>
                <p class="text-gray-900 font-medium">{{ $transaksi->user->name ?? '-' }}</p>
            </div>
        </div>

        <table class="w-full text-left text-sm text-gray-600 mb-8">
            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold">
                <tr>
                    <th class="px-4 py-3">Barang</th>
                    <th class="px-4 py-3 text-right">Harga Satuan</th>
                    <th class="px-4 py-3 text-right">Jumlah</th>
                    <th class="px-4 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($transaksi->item_transaksi as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->barang->nama_barang }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">{{ $item->jumlah }}</td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="border-t border-gray-200">
                <tr>
                    <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-900">TOTAL</td>
                    <td class="px-4 py-4 text-right font-bold text-blue-600 text-lg">Rp
                        {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center text-xs text-gray-400 mt-12">
            Dicetak pada {{ date('d/m/Y H:i') }}
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            #invoice {
                border: none;
                padding: 0;
            }
        }
    </style>
@endsection