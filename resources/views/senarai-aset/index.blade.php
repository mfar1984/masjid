<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Aset</h1>
                        <p class="text-xs text-gray-600">Pengurusan aset masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('senarai_aset', 'create'))
                            <a href="{{ route('senarai-aset.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Aset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('senarai-aset.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center flex-wrap">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no. aset, nama aset, kod aset..."
                        />

                        @if(isset($isSuperAdmin) && $isSuperAdmin && isset($masjidList))
                        <select name="masjid_id" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Masjid</option>
                            @foreach($masjidList as $masjid)
                                <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                    {{ $masjid->nama }}
                                </option>
                            @endforeach
                        </select>
                        @endif

                        <div class="flex gap-2">
                            <select name="kategori_aset_id" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori_aset_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>

                            <x-filter-dropdown
                                name="status_aset"
                                :options="[
                                    'Aktif' => 'Aktif',
                                    'Dalam Penyelenggaraan' => 'Dalam Penyelenggaraan',
                                    'Rosak' => 'Rosak',
                                    'Dilupuskan' => 'Dilupuskan',
                                    'Hilang' => 'Hilang',
                                    'Dipinjam' => 'Dipinjam',
                                    'Disewa' => 'Disewa'
                                ]"
                                :selected="request('status_aset')"
                                placeholder="Semua Status"
                            />

                            <x-filter-dropdown
                                name="kondisi_aset"
                                :options="[
                                    'Baru' => 'Baru',
                                    'Baik' => 'Baik',
                                    'Sederhana' => 'Sederhana',
                                    'Teruk' => 'Teruk',
                                    'Rosak' => 'Rosak'
                                ]"
                                :selected="request('kondisi_aset')"
                                placeholder="Semua Kondisi"
                            />
                        </div>

                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">
                                Cari
                            </x-action-button>
                            <x-action-button
                                type="button"
                                icon="refresh"
                                color="red"
                                onclick="window.location.href='{{ route('senarai-aset.index') }}'"
                            >
                                Reset
                            </x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Kod Aset</th>
                                <th class="px-4 py-2 table-header">Nama Aset</th>
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                <th class="px-4 py-2 table-header">Masjid</th>
                                @endif
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header">Lokasi</th>
                                <th class="px-4 py-2 table-header">Harga</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header">Kondisi</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($senariAset as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-blue-600 font-medium">{{ $item->kod_aset ?: $item->no_aset }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->nama_aset }}</div>
                                    </td>
                                    @if(isset($isSuperAdmin) && $isSuperAdmin)
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->masjid->nama ?? '-' }}</td>
                                    @endif
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kategoriAset->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->lokasi_semasa }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">RM {{ number_format($item->harga_perolehan, 2) }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_aset === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @elseif($item->status_aset === 'Rosak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Rosak</span>
                                        @elseif($item->status_aset === 'Hilang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_aset }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->kondisi_aset === 'Baru' || $item->kondisi_aset === 'Baik')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $item->kondisi_aset }}</span>
                                        @elseif($item->kondisi_aset === 'Sederhana')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $item->kondisi_aset }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $item->kondisi_aset }}</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('senarai-aset.show', $item)"
                                        :edit-route="route('senarai-aset.edit', $item)"
                                        module="aset"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ isset($isSuperAdmin) && $isSuperAdmin ? '9' : '8' }}" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">inventory_2</span>
                                        <p class="text-sm">Tiada aset dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($senariAset as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->nama_aset }}</h3>
                                <p class="mobile-subtitle text-blue-600 font-medium">{{ $item->kod_aset ?: $item->no_aset }}</p>
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                <p class="text-[10px] text-gray-500">{{ $item->masjid->nama ?? '-' }}</p>
                                @endif
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('senarai-aset.show', $item)"
                                :edit-route="route('senarai-aset.edit', $item)"
                                module="aset"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kategori</p>
                                <span class="mobile-data text-gray-900">{{ $item->kategoriAset->nama_kategori ?? '-' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Lokasi</p>
                                <span class="mobile-data text-gray-900">{{ $item->lokasi_semasa }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Harga</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->harga_perolehan, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status_aset === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($item->status_aset === 'Rosak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Rosak</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_aset }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kondisi</p>
                                @if($item->kondisi_aset === 'Baru' || $item->kondisi_aset === 'Baik')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $item->kondisi_aset }}</span>
                                @elseif($item->kondisi_aset === 'Sederhana')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">{{ $item->kondisi_aset }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $item->kondisi_aset }}</span>
                                @endif
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

                <!-- Pagination -->
                @if($senariAset->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $senariAset->firstItem() }} hingga {{ $senariAset->lastItem() }} daripada {{ $senariAset->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $senariAset->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Modal -->
    <x-delete-modal
        id="deleteModal"
        title="Padam Aset"
        message="Adakah anda pasti ingin memadam aset ini?"
        :route="'senarai-aset.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('senarai-aset') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
