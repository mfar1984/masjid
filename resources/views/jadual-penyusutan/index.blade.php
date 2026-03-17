<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Penyusutan - E-Masjid</title>
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
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Jadual Penyusutan</h1>
                        <p class="text-xs text-gray-600">Tetapan kadar susut nilai mengikut kategori aset</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('jadual_penyusutan', 'create'))
                            <a href="{{ route('jadual-penyusutan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Jadual
                            </a>
                        @endif
                    </div>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('jadual-penyusutan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari kategori..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="kaedah_susut" :options="['Garis Lurus' => 'Garis Lurus', 'Baki Berkurangan' => 'Baki Berkurangan']" :selected="request('kaedah_susut')" placeholder="Semua Kaedah" />
                            <x-filter-dropdown name="status" :options="['Aktif' => 'Aktif', 'Tidak Aktif' => 'Tidak Aktif']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('jadual-penyusutan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Kategori Aset</th>
                                <th class="px-4 py-2 table-header">Kadar Susut (%)</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header">Tempoh Guna (Tahun)</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jadualPenyusutan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->kategoriAset->nama_kategori ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ number_format($item->kadar_susut_tahunan, 2) }}%</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_susut }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tempoh_guna_tahun }} tahun</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons module="jadual_penyusutan" :record="$item" route-prefix="jadual-penyusutan" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">trending_down</span>
                                        <p class="text-sm">Tiada jadual penyusutan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($jadualPenyusutan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->kategoriAset->nama_kategori ?? '-' }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->kaedah_susut }}</p>
                            </div>
                            <x-action-icons module="jadual_penyusutan" :record="$item" route-prefix="jadual-penyusutan" :mobile="true" />
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kadar Susut</p>
                                <span class="mobile-data text-gray-900">{{ number_format($item->kadar_susut_tahunan, 2) }}%</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tempoh Guna</p>
                                <span class="mobile-data text-gray-900">{{ $item->tempoh_guna_tahun }} tahun</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">trending_down</span>
                        <p class="text-sm text-gray-500">Tiada jadual penyusutan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                @if($jadualPenyusutan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $jadualPenyusutan->firstItem() }} hingga {{ $jadualPenyusutan->lastItem() }} daripada {{ $jadualPenyusutan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $jadualPenyusutan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
    <x-delete-modal id="deleteModal" title="Padam Jadual Penyusutan" message="Adakah anda pasti ingin memadam jadual ini?" :route="'jadual-penyusutan.destroy'" />
</body>
</html>
