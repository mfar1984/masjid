<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akaun Bank - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Page Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Akaun Bank</h1>
                        <p class="text-xs text-gray-600">Pengurusan akaun bank masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('akaun_bank', 'create'))
                            <a href="{{ route('akaun-bank.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Akaun Bank
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('akaun-bank.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama bank, no akaun..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
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

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">
                                Cari
                            </x-action-button>
                            <x-action-button
                                type="button"
                                icon="refresh"
                                color="red"
                                onclick="window.location.href='{{ route('akaun-bank.index') }}'"
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
                                <th class="px-4 py-2 table-header">Nama Bank</th>
                                <th class="px-4 py-2 table-header">No. Akaun</th>
                                <th class="px-4 py-2 table-header">Jenis Akaun</th>
                                <th class="px-4 py-2 table-header">Pemegang Akaun</th>
                                <th class="px-4 py-2 table-header">Baki Semasa</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($akaunBank as $akaun)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $akaun->nama_bank }}</div>
                                        <div class="table-data text-gray-500">{{ $akaun->cawangan ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $akaun->no_akaun }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $akaun->jenis_akaun }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $akaun->nama_pemegang_akaun }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="font-semibold text-green-600">RM {{ number_format($akaun->baki_semasa, 2) }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @if($akaun->status === 'Aktif')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$akaun"
                                        :show-route="route('akaun-bank.show', $akaun)"
                                        :edit-route="route('akaun-bank.edit', $akaun)"
                                        module="kewangan"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">account_balance</span>
                                        <p class="text-sm">Tiada akaun bank dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($akaunBank as $akaun)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="material-icons text-sm text-blue-600">account_balance</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $akaun->nama_bank }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $akaun->no_akaun }}</p>
                            </div>
                            <x-action-icons
                                :record="$akaun"
                                :show-route="route('akaun-bank.show', $akaun)"
                                :edit-route="route('akaun-bank.edit', $akaun)"
                                module="kewangan"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jenis Akaun</p>
                                <span class="mobile-data text-gray-900">{{ $akaun->jenis_akaun }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Pemegang</p>
                                <span class="mobile-data text-gray-900">{{ $akaun->nama_pemegang_akaun }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Baki Semasa</p>
                                <span class="mobile-data font-semibold text-green-600">RM {{ number_format($akaun->baki_semasa, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($akaun->status === 'Aktif')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">account_balance</span>
                        <p class="text-sm text-gray-500">Tiada akaun bank dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $akaunBank->links() }}
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Modal -->
    <x-delete-modal
        id="deleteModal"
        title="Padam Akaun Bank"
        message="Adakah anda pasti ingin memadam akaun bank ini?"
        :route="'akaun-bank.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('akaun-bank') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
