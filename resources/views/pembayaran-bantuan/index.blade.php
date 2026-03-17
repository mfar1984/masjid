<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Bantuan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Bantuan</h1>
                        <p class="text-xs text-gray-600">Pengurusan pembayaran bantuan kebajikan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('pembayaran_bantuan', 'create'))
                            <a href="{{ route('pembayaran-bantuan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Pembayaran
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('pembayaran-bantuan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no pembayaran, nama penerima..."
                        />

                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="kaedah_bayaran"
                                :options="[
                                    'Tunai' => 'Tunai',
                                    'Cek' => 'Cek',
                                    'Bank Transfer' => 'Bank Transfer',
                                    'Barangan' => 'Barangan',
                                    'Baucar' => 'Baucar'
                                ]"
                                :selected="request('kaedah_bayaran')"
                                placeholder="Semua Kaedah"
                            />
                            <x-filter-dropdown
                                name="status_pembayaran"
                                :options="[
                                    'Sudah Bayar' => 'Sudah Bayar',
                                    'Belum Bayar' => 'Belum Bayar',
                                    'Dibatalkan' => 'Dibatalkan'
                                ]"
                                :selected="request('status_pembayaran')"
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
                                onclick="window.location.href='{{ route('pembayaran-bantuan.index') }}'"
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
                                <th class="px-4 py-2 table-header">No. Pembayaran</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Nama Penerima</th>
                                <th class="px-4 py-2 table-header">Program</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pembayaran as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_pembayaran }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_pembayaran->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->penerimaBantuan->nama_penuh ?? '-' }}</div>
                                        <div class="table-data text-gray-500">{{ $item->penerimaBantuan->no_kp ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->programKebajikan->nama_program ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">RM {{ number_format($item->jumlah_bayaran, 2) }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_bayaran }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_pembayaran === 'Sudah Bayar')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Bayar</span>
                                        @elseif($item->status_pembayaran === 'Belum Bayar')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Bayar</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                        @endif
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('pembayaran-bantuan.show', $item)"
                                        :edit-route="route('pembayaran-bantuan.edit', $item)"
                                        module="kebajikan"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">payments</span>
                                        <p class="text-sm">Tiada pembayaran bantuan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($pembayaran as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->no_pembayaran, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->no_pembayaran }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $item->penerimaBantuan->nama_penuh ?? '-' }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('pembayaran-bantuan.show', $item)"
                                :edit-route="route('pembayaran-bantuan.edit', $item)"
                                module="kebajikan"
                                layout="mobile"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_pembayaran->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jumlah</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->jumlah_bayaran, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kaedah</p>
                                <span class="mobile-data text-gray-900">{{ $item->kaedah_bayaran }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status_pembayaran === 'Sudah Bayar')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Bayar</span>
                                @elseif($item->status_pembayaran === 'Belum Bayar')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Bayar</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">payments</span>
                        <p class="text-sm text-gray-500">Tiada pembayaran bantuan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($pembayaran->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $pembayaran->firstItem() }} hingga {{ $pembayaran->lastItem() }} daripada {{ $pembayaran->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $pembayaran->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <x-delete-modal
        title="Padam Pembayaran Bantuan"
        message="Adakah anda pasti mahu memadamkan rekod pembayaran bantuan untuk"
    />

    <!-- Sahkan Modal -->
    <div id="sahkanModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-green-100">
                    <span class="material-icons text-green-600 text-xl">check_circle</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Sahkan Pembayaran</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500" id="sahkanModalNama"></p>
                </div>
                <form id="sahkanForm" method="POST" action="" class="mt-4 px-4">
                    @csrf
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeSahkanModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700">Sahkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Batal Modal -->
    <div id="batalModal" class="fixed inset-0 overflow-y-auto h-full w-full hidden z-50" style="background-color: rgba(0, 0, 0, 0.3);">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center mx-auto h-12 w-12 rounded-full bg-red-100">
                    <span class="material-icons text-red-600 text-xl">cancel</span>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4 text-center">Batal Pembayaran</h3>
                <div class="mt-2 px-4 py-3 text-center">
                    <p class="text-sm text-gray-500" id="batalModalNama"></p>
                </div>
                <form id="batalForm" method="POST" action="" class="mt-4 px-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Pembatalan *</label>
                        <textarea name="sebab_batal" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded text-xs"></textarea>
                    </div>
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <button type="button" onclick="closeBatalModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-xs font-medium rounded hover:bg-gray-400">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sahkan modal functions
        function showSahkanModal(id, nama) {
            document.getElementById('sahkanModalNama').textContent = nama;
            document.getElementById('sahkanForm').action = '/pembayaran-bantuan/' + id + '/sahkan';
            document.getElementById('sahkanModal').classList.remove('hidden');
        }

        function closeSahkanModal() {
            document.getElementById('sahkanModal').classList.add('hidden');
        }

        // Batal modal functions
        function showBatalModal(id, nama) {
            document.getElementById('batalModalNama').textContent = nama;
            document.getElementById('batalForm').action = '/pembayaran-bantuan/' + id + '/batal';
            document.getElementById('batalModal').classList.remove('hidden');
        }

        function closeBatalModal() {
            document.getElementById('batalModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        ['sahkanModal', 'batalModal'].forEach(modalId => {
            document.getElementById(modalId).addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSahkanModal();
                closeBatalModal();
            }
        });
    </script>
</body>
</html>
