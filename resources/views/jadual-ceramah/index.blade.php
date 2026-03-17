<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Ceramah - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Jadual Ceramah</h1>
                        <p class="text-xs text-gray-600">Pengurusan jadual dan bayaran ceramah</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('jadual_ceramah', 'create'))
                            <a href="{{ route('jadual-ceramah.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Jadual
                            </a>
                        @endif
                    </div>
                </div>
                <x-statistics-grid :stats="$stats" />
                <form method="GET" action="{{ route('jadual-ceramah.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari tajuk, penceramah..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status" :options="['Dijadual' => 'Dijadual', 'Selesai' => 'Selesai', 'Batal' => 'Batal']" :selected="request('status')" placeholder="Semua Status" />
                            <x-filter-dropdown name="status_bayaran" :options="['Belum Bayar' => 'Belum Bayar', 'Sudah Bayar' => 'Sudah Bayar']" :selected="request('status_bayaran')" placeholder="Status Bayaran" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('jadual-ceramah.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>
                @if(session('success'))<div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-xs">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-xs">{{ session('error') }}</div>@endif
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Penceramah</th>
                                <th class="px-4 py-2 table-header">Tajuk</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header text-right">Bayaran (RM)</th>
                                <th class="px-4 py-2 table-header text-center">Status Bayaran</th>
                                <th class="px-4 py-2 table-header text-center">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jadualList as $jadual)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important">{{ $jadual->tarikh->format('d/m/Y') }}</div>
                                        <div class="text-2xs text-gray-500">{{ $jadual->masa_mula }} - {{ $jadual->masa_tamat }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-900">{{ $jadual->penceramah->nama ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ Str::limit($jadual->tajuk_ceramah, 30) }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $jadual->jenis_ceramah }}</td>
                                    <td class="px-4 py-2 table-data text-right font-medium">{{ number_format($jadual->jumlah_kos, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status_bayaran === 'Sudah Bayar' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ $jadual->status_bayaran }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $jadual->status === 'Selesai' ? 'bg-green-100 text-green-800' : ($jadual->status === 'Batal' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $jadual->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if($jadual->status_bayaran === 'Belum Bayar')
                                            <form action="{{ route('jadual-ceramah.bayar', $jadual) }}" method="POST" class="inline" onsubmit="return confirm('Sahkan bayaran?')">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800" title="Bayar">
                                                    <span class="material-icons text-sm">payments</span>
                                                </button>
                                            </form>
                                            @endif
                                            <x-action-icons 
                                                :showUrl="route('jadual-ceramah.show', $jadual)"
                                                :editUrl="route('jadual-ceramah.edit', $jadual)"
                                                :deleteUrl="route('jadual-ceramah.destroy', $jadual)"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500"><span class="material-icons mb-2" style="font-size: 48px !important;">event</span><p class="text-sm">Tiada jadual dijumpai</p></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($jadualList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">Menunjukkan {{ $jadualList->firstItem() }} hingga {{ $jadualList->lastItem() }} daripada {{ $jadualList->total() }} rekod</div>
                    <div class="flex space-x-1">{{ $jadualList->appends(request()->query())->links('pagination::simple-tailwind') }}</div>
                </div>
                @endif
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
