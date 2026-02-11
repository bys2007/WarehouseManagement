<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warehouse Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 text-white flex flex-col">
            <div class="p-6 border-b border-slate-800">
                <h1 class="text-2xl font-bold tracking-tight">Warehouse</h1>
                <div class="mt-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-lg font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-medium text-sm">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-4 space-y-2 mt-6">
                <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2 rounded-lg hover:bg-slate-800 transition {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-blue-400' : '' }}">
                    Dashboard
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'officer', 'client']))
                    <a href="{{ route('barang.index') }}"
                        class="block px-4 py-2 rounded-lg hover:bg-slate-800 transition {{ request()->routeIs('barang.*') ? 'bg-slate-800 text-blue-400' : '' }}">
                        Barang
                    </a>
                    <a href="{{ route('transaksi.index') }}"
                        class="block px-4 py-2 rounded-lg hover:bg-slate-800 transition {{ request()->routeIs('transaksi.*') ? 'bg-slate-800 text-blue-400' : '' }}">
                        Transaksi
                    </a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('users.index') }}"
                        class="block px-4 py-2 rounded-lg hover:bg-slate-800 transition {{ request()->routeIs('users.*') ? 'bg-slate-800 text-blue-400' : '' }}">
                        Kelola User
                    </a>
                @endif
            </nav>
            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-gray-400 hover:text-white transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50 p-8">
            <header class="mb-8 flex justify-between items-center">
                <h2 class="text-3xl font-bold text-gray-800">@yield('title')</h2>
            </header>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>