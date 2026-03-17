<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerja Penyelenggaraan - E-Masjid</title>
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
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Kerja Penyelenggaraan</h1>
                        <p class="text-xs text-gray-600">Rekod kerja penyelenggaraan aset dan fasiliti</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <div class="text-xs text-gray-600 bg-green-50 px-3 py-2 rounded">
                            Jumlah Kos: <span class="font-bold text-green-700">RM {{ number_format($jumlahKos, 2) }}</span>
                        </div>
                        @if(auth()->user()->hasPermission('kerja_penyelenggaraan', 'create'))
                            <a href="{{ route('kerja-penyelenggaraan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Kerja
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('kerja-penyelenggaraan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari no. kerja, vendor..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="jenis_kerja" :options="['Penyelenggaraan Berkala' => 'Berkala', 'Pembaikan' => 'Pembaikan', 'Pemeriksaan' => 'Pemeriksaan', 'Servis' => 'Servis', 'Kecemasan' => 'Kecemasan']" :selected="request('jenis_kerja')" placeholder="Semua Jenis" />
                            <x-filter-dropdown name="status" :options="['Dirancang' => 'Dirancang', 'Sedang Berjalan' => 'Sedang Berjalan', 'Selesai' => 'Selesai', 'Dibatalkan' => 'Dibatalkan']" :selected="request('status')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('kerja-penyelenggaraan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Kerja</th>
                                <th class="px-4 py-2 table-header">Item</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header">Vendor</th>
                                <th class="px-4 py-2 table-header text-right">Kos (RM)</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($kerjaPenyelenggaraan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_kerja }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $item->item_nama }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->jenis_item }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_kerja->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->jenis_kerja }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->vendor_nama ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-right text-gray-900">{{ number_format($item->kos, 2) }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                        @elseif($item->status === 'Sedang Berjalan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Sedang Berjalan</span>
                                        @elseif($item->status === 'Dirancang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Dirancang</span>
                                        @elseif($item->status === 'Dibatalkan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">{{ $item->status }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <x-action-icons module="kerja_penyelenggaraan" :record="$item" route-prefix="kerja-penyelenggaraan" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">build</span>
                                        <p class="text-sm">Tiada kerja penyelenggaraan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($kerjaPenyelenggaraan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->no_kerja }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->item_nama }}</p>
                            </div>
                            <x-action-icons module="kerja_penyelenggaraan" :record="$item" route-prefix="kerja-penyelenggaraan" :mobile="true" />
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_kerja->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kos</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->kos, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">build</span>
                        <p class="text-sm text-gray-500">Tiada kerja penyelenggaraan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($kerjaPenyelenggaraan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $kerjaPenyelenggaraan->firstItem() }} hingga {{ $kerjaPenyelenggaraan->lastItem() }} daripada {{ $kerjaPenyelenggaraan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $kerjaPenyelenggaraan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />
    <x-delete-modal id="deleteModal" title="Padam Kerja Penyelenggaraan" message="Adakah anda pasti ingin memadam rekod kerja ini?" :route="'kerja-penyelenggaraan.destroy'" />
</body>
</html>
