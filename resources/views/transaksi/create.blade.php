@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                    <option value="masuk">Barang Masuk (Stok Bertambah)</option>
                    <option value="keluar">Barang Keluar (Stok Berkurang)</option>
                </select>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Pilih User</h3>
            <select name="id_user" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required>
                <option value="">-- Pilih User --</option>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
            <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Item Barang</h3>
            <div id="items-container" class="space-y-3">
                <div class="item-row flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Barang</label>
                        <select name="items[0][id_barang]" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"
                            required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach(\App\Models\Barang::all() as $barang)
                                <option value="{{ $barang->id_barang }}" data-harga="{{ $barang->harga_satuan }}">
                                    {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-24">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah</label>
                        <input type="number" name="items[0][jumlah]"
                            class="w-full rounded-lg border-gray-300 shadow-sm text-sm" min="1" required>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addItem()" class="mt-4 text-sm text-blue-600 hover:text-blue-800 font-medium">+
                Tambah Baris Item</button>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                Simpan Transaksi
            </button>
            <a href="{{ route('transaksi.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
        </div>
    </form>

    <script>
        let itemIndex = 1;
        function addItem() {
            const container = document.getElementById('items-container');
            const row = document.querySelector('.item-row').cloneNode(true);

            // Update names
            const select = row.querySelector('select');
            select.name = `items[${itemIndex}][id_barang]`;
            select.value = "";

            const input = row.querySelector('input');
            input.name = `items[${itemIndex}][jumlah]`;
            input.value = "";

            // Add remove button if not exists
            if (!row.querySelector('.remove-btn')) {
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'remove-btn text-red-500 hover:text-red-700 text-sm font-bold px-2 py-2';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = function () { row.remove(); };
                row.appendChild(removeBtn);
            }

            container.appendChild(row);
            itemIndex++;
        }
    </script>
@endsection