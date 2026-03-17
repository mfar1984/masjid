<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Asnaf - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Asnaf</h1>
                        <p class="text-xs text-gray-600">Senarai permohonan dan penerima bantuan zakat</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('asnaf', 'create'))
                            <a href="{{ route('asnaf.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">person_add</span>
                                Tambah Asnaf
                            </a>
                        @endif
                        <a href="{{ route('asnaf.export') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('asnaf.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama, nombor IC, telefon..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="kategori_asnaf"
                                :options="[
                                    'Fakir' => 'Fakir',
                                    'Miskin' => 'Miskin',
                                    'Amil' => 'Amil',
                                    'Muallaf' => 'Muallaf',
                                    'Riqab' => 'Riqab',
                                    'Gharimin' => 'Gharimin',
                                    'Fisabilillah' => 'Fisabilillah',
                                    'Ibnu Sabil' => 'Ibnu Sabil'
                                ]"
                                :selected="request('kategori_asnaf')"
                                placeholder="Semua Kategori"
                            />
                            
                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Menunggu' => 'Menunggu',
                                    'Dalam Semakan' => 'Dalam Semakan',
                                    'Diluluskan' => 'Diluluskan',
                                    'Ditolak' => 'Ditolak',
                                    'Digantung' => 'Digantung'
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
                                onclick="window.location.href='{{ route('asnaf.index') }}'"
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
                                <th class="px-4 py-2 table-header">Nama</th>
                                <th class="px-4 py-2 table-header">No. IC</th>
                                <th class="px-4 py-2 table-header">Telefon</th>
                                <th class="px-4 py-2 table-header">Kategori Asnaf</th>
                                <th class="px-4 py-2 table-header">Pendapatan</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($asnaf as $item)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data-important text-gray-900">{{ $item->nama }}</div>
                                    <div class="table-data text-gray-500">{{ $item->no_ic }}</div>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $item->no_ic }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $item->telefon }}</td>
                                <td class="px-4 py-2 table-data">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $item->kategori_asnaf }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">RM {{ number_format($item->pendapatan_bulanan, 2) }}</td>
                                <td class="px-4 py-2 table-data">
                                    @if($item->status == 'Diluluskan')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $item->status }}</span>
                                    @elseif($item->status == 'Menunggu')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status }}</span>
                                    @elseif($item->status == 'Ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $item->status }}</span>
                                    @elseif($item->status == 'Digantung')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">{{ $item->status }}</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <x-action-icons
                                    :record="$item"
                                    :show-route="route('asnaf.show', $item)"
                                    :edit-route="route('asnaf.edit', $item)"
                                    module="asnaf"
                                    layout="desktop"
                                />
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">people</span>
                                    <p class="text-sm">Tiada asnaf dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($asnaf as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->nama, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->nama }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $item->no_ic }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('asnaf.show', $item)"
                                :edit-route="route('asnaf.edit', $item)"
                                module="asnaf"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Telefon</p>
                                <span class="mobile-data text-gray-900">{{ $item->telefon }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kategori</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $item->kategori_asnaf }}
                                </span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Pendapatan</p>
                                <span class="mobile-data text-gray-900">RM {{ number_format($item->pendapatan_bulanan, 2) }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status == 'Diluluskan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">{{ $item->status }}</span>
                                @elseif($item->status == 'Menunggu')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status }}</span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">{{ $item->status }}</span>
                                @elseif($item->status == 'Digantung')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">{{ $item->status }}</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">{{ $item->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">people</span>
                        <p class="text-sm text-gray-500">Tiada asnaf dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($asnaf->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $asnaf->firstItem() }} hingga {{ $asnaf->lastItem() }} daripada {{ $asnaf->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $asnaf->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Workflow Modals -->
    <x-approve-modal />
    <x-reject-modal />
    <x-suspend-modal />
    <x-unsuspend-modal />

    <!-- Delete Confirmation Modal -->
    <x-delete-modal
        title="Padam Asnaf"
        message="Adakah anda pasti mahu memadamkan rekod asnaf untuk"
    />

    <script>
        // Override modal actions for asnaf
        function showApproveModal(id, nama) {
            document.getElementById('approveMasjidName').textContent = nama;
            document.getElementById('approveForm').action = `/asnaf/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function showRejectModal(id, nama) {
            document.getElementById('rejectMasjidName').textContent = nama;
            document.getElementById('rejectForm').action = `/asnaf/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function showSuspendModal(id, nama) {
            document.getElementById('suspendMasjidName').textContent = nama;
            document.getElementById('suspendForm').action = `/asnaf/${id}/suspend`;
            document.getElementById('suspendModal').classList.remove('hidden');
        }

        function showUnsuspendModal(id, nama) {
            document.getElementById('unsuspendMasjidName').textContent = nama;
            document.getElementById('unsuspendForm').action = `/asnaf/${id}/reactivate`;
            document.getElementById('unsuspendModal').classList.remove('hidden');
        }

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

            if (deleteRecordName) deleteRecordName.textContent = recordName;

            const code = generateSecurityCode();
            if (securityCode) securityCode.textContent = code;

            if (deleteForm) deleteForm.action = `/asnaf/${recordId}`;

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
                closeApproveModal();
                closeRejectModal();
                closeSuspendModal();
                closeUnsuspendModal();
            }
        });
    </script>
</body>
</html>
