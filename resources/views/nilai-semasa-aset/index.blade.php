<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Semasa Aset - E-Masjid</title>
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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Nilai Semasa Aset</h1>
                    <p class="text-xs text-gray-600">Senarai nilai semasa setiap aset selepas susut nilai</p>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('nilai-semasa-aset.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari no. siri, nama aset..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status_aset" :options="['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif', 'Rosak' => 'Rosak']" :selected="request('status_aset')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('nilai-semasa-aset.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Siri</th>
                                <th class="px-4 py-2 table-header">Nama Aset</th>
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header text-right">Nilai Asal (RM)</th>
                                <th class="px-4 py-2 table-header text-right">Susut Nilai (RM)</th>
                                <th class="px-4 py-2 table-header text-right">Nilai Semasa (RM)</th>
                                <th class="px-4 py-2 table-header text-right">% Susut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($asetList as $aset)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $aset->no_siri }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $aset->nama_aset }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $aset->kategoriAset->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($aset->harga_perolehan, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-right text-red-600">{{ number_format($aset->susut_nilai, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-right font-semibold text-green-600">{{ number_format($aset->nilai_semasa, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $aset->peratus_susut > 50 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $aset->peratus_susut }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">inventory_2</span>
                                        <p class="text-sm">Tiada aset dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($asetList as $aset)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $aset->no_siri }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $aset->nama_aset }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $aset->peratus_susut > 50 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $aset->peratus_susut }}%
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Nilai Asal</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($aset->harga_perolehan, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Susut Nilai</p>
                                <span class="mobile-data text-red-600">RM {{ number_format($aset->susut_nilai, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Nilai Semasa</p>
                                <span class="mobile-data text-green-600 font-semibold">RM {{ number_format($aset->nilai_semasa, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">inventory_2</span>
                        <p class="text-sm text-gray-500">Tiada aset dijumpai</p>
                    </div>
                    @endforelse
                </div>

                @if($asetList->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $asetList->firstItem() }} hingga {{ $asetList->lastItem() }} daripada {{ $asetList->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $asetList->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
</body>
</html>
