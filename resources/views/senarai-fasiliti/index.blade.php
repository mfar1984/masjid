<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Fasiliti - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Fasiliti</h1>
                        <p class="text-xs text-gray-600">Pengurusan fasiliti masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('senarai_fasiliti', 'create'))
                            <a href="{{ route('senarai-fasiliti.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Fasiliti
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('senarai-fasiliti.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari kod fasiliti, nama fasiliti..."
                        />

                        <div class="flex gap-2">
                            <select name="jenis_fasiliti" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Jenis</option>
                                <option value="Dewan" {{ request('jenis_fasiliti') == 'Dewan' ? 'selected' : '' }}>Dewan</option>
                                <option value="Bilik" {{ request('jenis_fasiliti') == 'Bilik' ? 'selected' : '' }}>Bilik</option>
                                <option value="Padang" {{ request('jenis_fasiliti') == 'Padang' ? 'selected' : '' }}>Padang</option>
                                <option value="Tempat Letak Kereta" {{ request('jenis_fasiliti') == 'Tempat Letak Kereta' ? 'selected' : '' }}>Tempat Letak Kereta</option>
                                <option value="Aset" {{ request('jenis_fasiliti') == 'Aset' ? 'selected' : '' }}>Aset</option>
                                <option value="Lain-lain" {{ request('jenis_fasiliti') == 'Lain-lain' ? 'selected' : '' }}>Lain-lain</option>
                            </select>

                            <x-filter-dropdown
                                name="status_fasiliti"
                                :options="[
                                    'Tersedia' => 'Tersedia',
                                    'Tidak Tersedia' => 'Tidak Tersedia',
                                    'Dalam Penyelenggaraan' => 'Dalam Penyelenggaraan'
                                ]"
                                :selected="request('status_fasiliti')"
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
                                onclick="window.location.href='{{ route('senarai-fasiliti.index') }}'"
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
                                <th class="px-4 py-2 table-header">Kod Fasiliti</th>
                                <th class="px-4 py-2 table-header">Nama Fasiliti</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header">Kapasiti</th>
                                <th class="px-4 py-2 table-header">Harga Sewa</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($senariFasiliti as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->kod_fasiliti }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->nama_fasiliti }}</div>
                                        @if($item->kategori_fasiliti)
                                        <div class="text-[10px] text-gray-500">{{ $item->kategori_fasiliti }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->jenis_fasiliti }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">
                                        {{ $item->kapasiti_maksimum ? $item->kapasiti_maksimum . ' orang' : '-' }}
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">
                                        @if($item->harga_sewa_sehari)
                                            RM {{ number_format($item->harga_sewa_sehari, 2) }}/hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_fasiliti === 'Tersedia')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                        @elseif($item->status_fasiliti === 'Tidak Tersedia')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Tersedia</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_fasiliti }}</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('senarai-fasiliti.show', $item)"
                                        :edit-route="route('senarai-fasiliti.edit', $item)"
                                        module="fasiliti"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">business</span>
                                        <p class="text-sm">Tiada fasiliti dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($senariFasiliti as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->nama_fasiliti }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->kod_fasiliti }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('senarai-fasiliti.show', $item)"
                                :edit-route="route('senarai-fasiliti.edit', $item)"
                                module="fasiliti"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jenis</p>
                                <span class="mobile-data text-gray-900">{{ $item->jenis_fasiliti }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kapasiti</p>
                                <span class="mobile-data text-gray-900">{{ $item->kapasiti_maksimum ? $item->kapasiti_maksimum . ' orang' : '-' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Harga Sewa</p>
                                <span class="mobile-data text-gray-900">
                                    @if($item->harga_sewa_sehari)
                                        RM {{ number_format($item->harga_sewa_sehari, 2) }}/hari
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status_fasiliti === 'Tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                @elseif($item->status_fasiliti === 'Tidak Tersedia')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Tersedia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_fasiliti }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">business</span>
                        <p class="text-sm text-gray-500">Tiada fasiliti dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($senariFasiliti->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $senariFasiliti->firstItem() }} hingga {{ $senariFasiliti->lastItem() }} daripada {{ $senariFasiliti->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $senariFasiliti->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Fasiliti"
        message="Adakah anda pasti ingin memadam fasiliti ini?"
        :route="'senarai-fasiliti.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('senarai-fasiliti') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
