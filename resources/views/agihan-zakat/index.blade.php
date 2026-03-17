<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agihan Zakat - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Agihan Zakat</h1>
                        <p class="text-xs text-gray-600">Pengurusan agihan dan pembayaran zakat kepada asnaf</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('agihan_zakat', 'create'))
                            <a href="{{ route('agihan-zakat.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Agihan
                            </a>
                        @endif
                        <a href="{{ route('agihan-zakat.export') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('agihan-zakat.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no agihan, nama asnaf, no IC..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Belum Bayar' => 'Belum Bayar',
                                    'Sudah Bayar' => 'Sudah Bayar',
                                    'Dibatalkan' => 'Dibatalkan'
                                ]"
                                :selected="request('status')"
                                placeholder="Semua Status"
                            />
                            <x-filter-dropdown
                                name="kaedah_bayaran"
                                :options="[
                                    'Tunai' => 'Tunai',
                                    'Cek' => 'Cek',
                                    'Bank Transfer' => 'Bank Transfer',
                                    'E-Wallet' => 'E-Wallet'
                                ]"
                                :selected="request('kaedah_bayaran')"
                                placeholder="Semua Kaedah"
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
                                onclick="window.location.href='{{ route('agihan-zakat.index') }}'"
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
                                <th class="px-4 py-2 table-header">No Agihan</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Asnaf</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($agihan as $item)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data-important text-gray-900">{{ $item->no_agihan }}</div>
                                    <div class="table-data text-gray-500">{{ $item->tarikh_agihan->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_agihan->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data-important text-gray-900">{{ $item->asnaf->nama }}</div>
                                    <div class="table-data text-gray-500">{{ $item->asnaf->no_ic }}</div>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600 font-semibold">RM {{ number_format($item->jumlah_diagihkan, 2) }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_bayaran }}</td>
                                <td class="px-4 py-2 table-data">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                        @if($item->status == 'Sudah Bayar') bg-green-100 text-green-800
                                        @elseif($item->status == 'Belum Bayar') bg-orange-100 text-orange-800
                                        @elseif($item->status == 'Dibatalkan') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 table-data text-center space-x-1">
                                    <!-- View -->
                                    @if(auth()->user()->hasPermission('agihan_zakat', 'read'))
                                    <a href="{{ route('agihan-zakat.show', $item) }}" class="text-gray-700 hover:text-gray-900 action-icon" title="Lihat">
                                        <span class="material-icons text-[8px]">visibility</span>
                                    </a>
                                    @endif
                                    
                                    <!-- Edit -->
                                    @if($item->canBeEdited() && auth()->user()->hasPermission('agihan_zakat', 'update'))
                                    <a href="{{ route('agihan-zakat.edit', $item) }}" class="text-blue-600 hover:text-blue-800 action-icon" title="Edit">
                                        <span class="material-icons text-[8px]">edit</span>
                                    </a>
                                    @endif
                                    
                                    <!-- Delete -->
                                    @if($item->canBeEdited() && auth()->user()->hasPermission('agihan_zakat', 'delete'))
                                    <button type="button" onclick="showDeleteModal({{ $item->id }}, '{{ $item->no_agihan }}')" class="text-red-600 hover:text-red-800 action-icon" title="Padam">
                                        <span class="material-icons text-[8px]">delete</span>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">payments</span>
                                    <p class="text-sm">Tiada agihan zakat dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($agihan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with No Agihan and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->no_agihan, 0, 2)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->no_agihan }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $item->asnaf->nama }}</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                @if(auth()->user()->hasPermission('agihan_zakat', 'read'))
                                <a href="{{ route('agihan-zakat.show', $item) }}" class="text-gray-700 hover:text-gray-900" title="Lihat">
                                    <span class="material-icons" style="font-size: 20px !important;">visibility</span>
                                </a>
                                @endif
                                @if($item->canBeEdited() && auth()->user()->hasPermission('agihan_zakat', 'update'))
                                <a href="{{ route('agihan-zakat.edit', $item) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                    <span class="material-icons" style="font-size: 20px !important;">edit</span>
                                </a>
                                @endif
                                @if($item->canBeEdited() && auth()->user()->hasPermission('agihan_zakat', 'delete'))
                                <button type="button" onclick="showDeleteModal({{ $item->id }}, '{{ $item->no_agihan }}')" class="text-red-600 hover:text-red-800" title="Padam">
                                    <span class="material-icons" style="font-size: 20px !important;">delete</span>
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_agihan->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jumlah</p>
                                <span class="mobile-data text-gray-900 font-semibold">RM {{ number_format($item->jumlah_diagihkan, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kaedah</p>
                                <span class="mobile-data text-gray-900">{{ $item->kaedah_bayaran }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                    @if($item->status == 'Sudah Bayar') bg-green-100 text-green-800
                                    @elseif($item->status == 'Belum Bayar') bg-orange-100 text-orange-800
                                    @elseif($item->status == 'Dibatalkan') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $item->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">payments</span>
                        <p class="text-sm text-gray-500">Tiada agihan zakat dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($agihan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $agihan->firstItem() }} hingga {{ $agihan->lastItem() }} daripada {{ $agihan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $agihan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Confirmation Modal -->
    <x-delete-modal
        title="Padam Agihan Zakat"
        message="Adakah anda pasti mahu memadamkan agihan"
    />

    <script>
        function generateSecurityCode() {
            return Math.random().toString(36).substring(2, 8).toUpperCase();
        }

        function showDeleteModal(recordId, recordName) {
            const modal = document.getElementById('deleteModal');
            const deleteRecordName = document.getElementById('deleteRecordName');
            const securityCode = document.getElementById('securityCode');
            const confirmCode = document.getElementById('confirmCode');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteForm = document.getElementById('deleteForm');

            if (!modal) return;

            if (deleteRecordName) {
                deleteRecordName.textContent = recordName;
            }

            const code = generateSecurityCode();
            if (securityCode) {
                securityCode.textContent = code;
            }

            if (deleteForm) {
                deleteForm.action = `/agihan-zakat/${recordId}`;
            }

            if (confirmCode) {
                confirmCode.value = '';
                confirmCode.addEventListener('input', function() {
                    if (confirmDeleteBtn) {
                        if (this.value.toUpperCase() === code) {
                            confirmDeleteBtn.disabled = false;
                            confirmDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        } else {
                            confirmDeleteBtn.disabled = true;
                            confirmDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    }
                });
            }

            if (confirmDeleteBtn) {
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            modal.classList.remove('hidden');

            if (confirmCode) {
                setTimeout(() => confirmCode.focus(), 100);
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) modal.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) hideDeleteModal();
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>
