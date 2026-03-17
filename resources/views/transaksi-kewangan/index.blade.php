<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Kewangan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Transaksi Kewangan</h1>
                        <p class="text-xs text-gray-600">Senarai semua transaksi pendapatan dan perbelanjaan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('transaksi_kewangan', 'create'))
                            <!-- Dropdown Tambah Pendapatan -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 w-full sm:w-auto">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                    Tambah Pendapatan
                                    <span class="material-icons ml-1" style="font-size: 14px !important;">arrow_drop_down</span>
                                </button>
                                <div x-show="open" x-cloak class="absolute right-0 mt-1 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="{{ route('transaksi-kewangan.kutipan-kariah') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-blue-500">people</span>
                                        Kutipan Kariah
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.derma-sumbangan') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-green-500">volunteer_activism</span>
                                        Derma & Sumbangan
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.kutipan-zakat') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-purple-500">mosque</span>
                                        Kutipan Zakat
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.kutipan-lain') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-orange-500">more_horiz</span>
                                        Kutipan Lain
                                    </a>
                                </div>
                            </div>

                            <!-- Dropdown Tambah Perbelanjaan -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 w-full sm:w-auto">
                                    <span class="material-icons mr-2" style="font-size: 16px !important;">remove</span>
                                    Tambah Perbelanjaan
                                    <span class="material-icons ml-1" style="font-size: 14px !important;">arrow_drop_down</span>
                                </button>
                                <div x-show="open" x-cloak class="absolute right-0 mt-1 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                    <a href="{{ route('transaksi-kewangan.utiliti-bil') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-yellow-500">bolt</span>
                                        Utiliti & Bil
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.penyelenggaraan') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-blue-500">build</span>
                                        Penyelenggaraan
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.gaji-elaun') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-green-500">payments</span>
                                        Gaji & Elaun
                                    </a>
                                    <a href="{{ route('transaksi-kewangan.perbelanjaan-lain') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-gray-100">
                                        <span class="material-icons text-sm mr-2 align-middle text-gray-500">receipt</span>
                                        Perbelanjaan Lain
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('transaksi-kewangan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no transaksi, keterangan..."
                        />

                        <div class="flex flex-wrap gap-2">
                            <x-filter-dropdown
                                name="jenis_transaksi"
                                :options="[
                                    'Pendapatan' => 'Pendapatan',
                                    'Perbelanjaan' => 'Perbelanjaan'
                                ]"
                                :selected="request('jenis_transaksi')"
                                placeholder="Semua Jenis"
                            />
                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Selesai' => 'Selesai',
                                    'Pending' => 'Pending'
                                ]"
                                :selected="request('status')"
                                placeholder="Semua Status"
                            />
                            <select name="kategori_kewangan_id" class="h-[32px] px-3 py-1 border border-gray-300 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 bg-white min-w-[140px]">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori_kewangan_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('transaksi-kewangan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Transaksi</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header">Akaun Bank</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($transaksi as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_transaksi }}</div>
                                        <div class="table-data text-gray-500">{{ Str::limit($item->keterangan, 30) }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_transaksi->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->jenis_transaksi === 'Pendapatan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Pendapatan</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Perbelanjaan</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kategoriKewangan->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->akaunBank->nama_bank ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <span class="font-semibold {{ $item->jenis_transaksi === 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                            RM {{ number_format($item->jumlah, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Pending</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('transaksi-kewangan.show', $item)"
                                        :edit-route="route('transaksi-kewangan.edit', $item)"
                                        module="kewangan"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">receipt_long</span>
                                        <p class="text-sm">Tiada transaksi dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($transaksi as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 {{ $item->jenis_transaksi === 'Pendapatan' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mr-2">
                                        <span class="material-icons text-sm {{ $item->jenis_transaksi === 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $item->jenis_transaksi === 'Pendapatan' ? 'trending_up' : 'trending_down' }}
                                        </span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->no_transaksi }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ Str::limit($item->keterangan, 40) }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('transaksi-kewangan.show', $item)"
                                :edit-route="route('transaksi-kewangan.edit', $item)"
                                module="kewangan"
                                layout="mobile"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_transaksi->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jenis</p>
                                @if($item->jenis_transaksi === 'Pendapatan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Pendapatan</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Perbelanjaan</span>
                                @endif
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jumlah</p>
                                <span class="mobile-data font-semibold {{ $item->jenis_transaksi === 'Pendapatan' ? 'text-green-600' : 'text-red-600' }}">
                                    RM {{ number_format($item->jumlah, 2) }}
                                </span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status === 'Selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">receipt_long</span>
                        <p class="text-sm text-gray-500">Tiada transaksi dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($transaksi->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $transaksi->firstItem() }} hingga {{ $transaksi->lastItem() }} daripada {{ $transaksi->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $transaksi->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <x-delete-modal
        id="deleteModal"
        title="Padam Transaksi"
        message="Adakah anda pasti ingin memadam transaksi ini?"
        :route="'transaksi-kewangan.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('transaksi-kewangan') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
