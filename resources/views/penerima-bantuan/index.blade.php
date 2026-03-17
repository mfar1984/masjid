<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerima Bantuan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Penerima Bantuan</h1>
                        <p class="text-xs text-gray-600">Pengurusan data penerima bantuan kebajikan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('penerima_bantuan', 'create'))
                            <a href="{{ route('penerima-bantuan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Penerima
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('penerima-bantuan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no pendaftaran, nama, no IC..."
                        />

                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="status_penerima"
                                :options="[
                                    'Aktif' => 'Aktif',
                                    'Tidak Aktif' => 'Tidak Aktif',
                                    'Tamat' => 'Tamat'
                                ]"
                                :selected="request('status_penerima')"
                                placeholder="Semua Status"
                            />
                            <x-filter-dropdown
                                name="status_oku"
                                :options="[
                                    'Ya' => 'OKU',
                                    'Tidak' => 'Bukan OKU'
                                ]"
                                :selected="request('status_oku')"
                                placeholder="Status OKU"
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
                                onclick="window.location.href='{{ route('penerima-bantuan.index') }}'"
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
                                <th class="px-4 py-2 table-header">No. Pendaftaran</th>
                                <th class="px-4 py-2 table-header">Nama Penuh</th>
                                <th class="px-4 py-2 table-header">No. KP</th>
                                <th class="px-4 py-2 table-header">No. Telefon</th>
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($penerima as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_pendaftaran }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->nama_penuh }}</div>
                                        <div class="table-data text-gray-500">{{ $item->no_kp }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->no_kp }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->no_telefon }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="flex flex-wrap gap-1">
                                            @if($item->status_oku === 'Ya')
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">OKU</span>
                                            @endif
                                            @if($item->status_yatim === 'Ya')
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Yatim</span>
                                            @endif
                                            @if($item->status_ibu_tunggal === 'Ya')
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-pink-100 text-pink-800">Ibu Tunggal</span>
                                            @endif
                                            @if($item->status_warga_emas === 'Ya')
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Warga Emas</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_penerima === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @elseif($item->status_penerima === 'Tidak Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tamat</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('penerima-bantuan.show', $item)"
                                        :edit-route="route('penerima-bantuan.edit', $item)"
                                        module="kebajikan"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">people</span>
                                        <p class="text-sm">Tiada penerima bantuan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($penerima as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->nama_penuh, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->nama_penuh }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $item->no_pendaftaran }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('penerima-bantuan.show', $item)"
                                :edit-route="route('penerima-bantuan.edit', $item)"
                                module="kebajikan"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">No. KP</p>
                                <span class="mobile-data text-gray-900">{{ $item->no_kp }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Telefon</p>
                                <span class="mobile-data text-gray-900">{{ $item->no_telefon }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kategori</p>
                                <div class="flex flex-wrap gap-1">
                                    @if($item->status_oku === 'Ya')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">OKU</span>
                                    @endif
                                    @if($item->status_yatim === 'Ya')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Yatim</span>
                                    @endif
                                    @if($item->status_ibu_tunggal === 'Ya')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-pink-100 text-pink-800">Ibu Tunggal</span>
                                    @endif
                                    @if($item->status_warga_emas === 'Ya')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Warga Emas</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status_penerima === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @elseif($item->status_penerima === 'Tidak Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tamat</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">people</span>
                        <p class="text-sm text-gray-500">Tiada penerima bantuan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($penerima->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $penerima->firstItem() }} hingga {{ $penerima->lastItem() }} daripada {{ $penerima->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $penerima->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Penerima Bantuan"
        message="Adakah anda pasti ingin memadam penerima bantuan ini?"
        :route="'penerima-bantuan.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('penerima-bantuan') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
