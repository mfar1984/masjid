<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Aset - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Kategori Aset</h1>
                        <p class="text-xs text-gray-600">Pengurusan kategori aset masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('kategori_aset', 'create'))
                            <a href="{{ route('kategori-aset.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Kategori
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('kategori-aset.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center flex-wrap">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari kod kategori, nama kategori..."
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
                            <x-filter-dropdown
                                name="jenis_kategori"
                                :options="[
                                    'Tanah & Bangunan' => 'Tanah & Bangunan',
                                    'Kenderaan' => 'Kenderaan',
                                    'Peralatan' => 'Peralatan',
                                    'Perabot' => 'Perabot',
                                    'Elektronik' => 'Elektronik',
                                    'Lain-lain' => 'Lain-lain'
                                ]"
                                :selected="request('jenis_kategori')"
                                placeholder="Semua Jenis"
                            />
                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Aktif' => 'Aktif',
                                    'Tidak Aktif' => 'Tidak Aktif'
                                ]"
                                :selected="request('status')"
                                placeholder="Semua Status"
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
                                onclick="window.location.href='{{ route('kategori-aset.index') }}'"
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
                                <th class="px-4 py-2 table-header">Kod Kategori</th>
                                <th class="px-4 py-2 table-header">Nama Kategori</th>
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                <th class="px-4 py-2 table-header">Masjid</th>
                                @endif
                                <th class="px-4 py-2 table-header">Jenis Kategori</th>
                                <th class="px-4 py-2 table-header">Jumlah Aset</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($kategoriAset as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->kod_kategori }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->nama_kategori }}</div>
                                    </td>
                                    @if(isset($isSuperAdmin) && $isSuperAdmin)
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->masjid->nama ?? '-' }}</td>
                                    @endif
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->jenis_kategori }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->senari_aset_count ?? 0 }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('kategori-aset.show', $item)"
                                        :edit-route="route('kategori-aset.edit', $item)"
                                        module="aset"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ isset($isSuperAdmin) && $isSuperAdmin ? '7' : '6' }}" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">category</span>
                                        <p class="text-sm">Tiada kategori aset dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($kategoriAset as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->nama_kategori }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->kod_kategori }}</p>
                                @if(isset($isSuperAdmin) && $isSuperAdmin)
                                <p class="text-[10px] text-gray-500">{{ $item->masjid->nama ?? '-' }}</p>
                                @endif
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('kategori-aset.show', $item)"
                                :edit-route="route('kategori-aset.edit', $item)"
                                module="aset"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jenis Kategori</p>
                                <span class="mobile-data text-gray-900">{{ $item->jenis_kategori }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jumlah Aset</p>
                                <span class="mobile-data text-gray-900">{{ $item->senari_aset_count ?? 0 }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">category</span>
                        <p class="text-sm text-gray-500">Tiada kategori aset dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($kategoriAset->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $kategoriAset->firstItem() }} hingga {{ $kategoriAset->lastItem() }} daripada {{ $kategoriAset->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $kategoriAset->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Kategori Aset"
        message="Adakah anda pasti ingin memadam kategori aset ini?"
        :route="'kategori-aset.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('kategori-aset') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
