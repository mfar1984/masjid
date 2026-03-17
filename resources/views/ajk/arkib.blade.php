<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arkib AJK - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Arkib Ahli Jawatankuasa</h1>
                        <p class="text-xs text-gray-600">Senarai ahli jawatankuasa yang telah tamat tempoh</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('ajk.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali ke Senarai
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('ajk.arkib') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama, nombor IC, telefon, jawatan..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'Aktif' => 'Aktif',
                                    'Menunggu' => 'Menunggu',
                                    'Ditolak' => 'Ditolak',
                                    'Tidak Aktif' => 'Tidak Aktif',
                                    'Digantung' => 'Digantung'
                                ]"
                                :selected="request('status')"
                                placeholder="Semua Status"
                            />
                            <x-filter-dropdown
                                name="jawatan"
                                :options="[
                                    'Pengerusi' => 'Pengerusi',
                                    'Naib Pengerusi' => 'Naib Pengerusi',
                                    'Setiausaha' => 'Setiausaha',
                                    'Bendahari' => 'Bendahari',
                                    'Penolong Setiausaha' => 'Penolong Setiausaha',
                                    'Penolong Bendahari' => 'Penolong Bendahari',
                                    'Ahli Jawatankuasa' => 'Ahli Jawatankuasa',
                                    'Imam' => 'Imam',
                                    'Bilal' => 'Bilal',
                                    'Siak' => 'Siak'
                                ]"
                                :selected="request('jawatan')"
                                placeholder="Semua Jawatan"
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
                                onclick="window.location.href='{{ route('ajk.index') }}'"
                            >
                                Reset
                            </x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-orange-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Nama</th>
                                <th class="px-4 py-2 table-header">Jawatan</th>
                                <th class="px-4 py-2 table-header">No. IC</th>
                                <th class="px-4 py-2 table-header">Telefon</th>
                                <th class="px-4 py-2 table-header">Tarikh Diarkib</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($ajk as $member)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data-important text-gray-900">{{ $member->nama }}</div>
                                    <div class="table-data text-gray-500">{{ $member->no_ic }}</div>
                                </td>
                                <td class="px-4 py-2 table-data">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $member->jawatan_full }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->no_ic }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->telefon }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->archived_at ? $member->archived_at->format('d/m/Y') : '--' }}</td>
                                <td class="px-4 py-2 table-data text-center space-x-1">
                                    <!-- View -->
                                    <a href="{{ route('ajk.show', $member) }}" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" title="Lihat">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>
                                    
                                    <!-- Copy -->
                                    @if(auth()->user()->hasPermission('ajk', 'create'))
                                    <a href="{{ route('ajk.copy', $member) }}" class="p-2 text-green-600 hover:text-green-800 rounded-full hover:bg-green-50" title="Salin Data">
                                        <span class="material-icons text-sm">content_copy</span>
                                    </a>
                                    @endif
                                    
                                    <!-- Restore -->
                                    @if(auth()->user()->hasPermission('ajk', 'update'))
                                    <form action="{{ route('ajk.unarchive', $member) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-orange-600 hover:text-orange-800 rounded-full hover:bg-orange-50" title="Pulihkan dari Arkib" onclick="return confirm('Pulihkan {{ $member->nama }} dari arkib?')">
                                            <span class="material-icons text-sm">unarchive</span>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">archive</span>
                                    <p class="text-sm">Tiada arkib dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($ajk as $member)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($member->nama, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $member->nama }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $member->jawatan }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('ajk.show', $member) }}" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                @if(auth()->user()->hasPermission('ajk', 'create'))
                                <a href="{{ route('ajk.copy', $member) }}" class="p-2 text-green-600 hover:text-green-800 rounded-full hover:bg-green-50">
                                    <span class="material-icons text-sm">content_copy</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">No. IC</p>
                                <span class="mobile-data text-gray-900">{{ $member->no_ic }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Telefon</p>
                                <span class="mobile-data text-gray-900">{{ $member->telefon }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh Diarkib</p>
                                <span class="mobile-data text-gray-900">{{ $member->archived_at ? $member->archived_at->format('d/m/Y') : '--' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tindakan</p>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('ajk.show', $member) }}" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50">
                                        <span class="material-icons text-sm">visibility</span>
                                    </a>
                                    @if(auth()->user()->hasPermission('ajk', 'create'))
                                    <a href="{{ route('ajk.copy', $member) }}" class="p-2 text-green-600 hover:text-green-800 rounded-full hover:bg-green-50">
                                        <span class="material-icons text-sm">content_copy</span>
                                    </a>
                                    @endif
                                    @if(auth()->user()->hasPermission('ajk', 'update'))
                                    <form action="{{ route('ajk.unarchive', $member) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-orange-600 hover:text-orange-800 rounded-full hover:bg-orange-50" onclick="return confirm('Pulihkan dari arkib?')">
                                            <span class="material-icons text-sm">unarchive</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">archive</span>
                        <p class="text-sm text-gray-500">Tiada arkib dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($ajk->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $ajk->firstItem() }} hingga {{ $ajk->lastItem() }} daripada {{ $ajk->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $ajk->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Ahli Jawatankuasa"
        message="Adakah anda pasti mahu memadamkan rekod ahli jawatankuasa untuk"
    />

    <script>
        // Override modal actions for AJK
        function showApproveModal(id, nama) {
            document.getElementById('approveMasjidName').textContent = nama;
            document.getElementById('approveForm').action = `/ajk/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function showRejectModal(id, nama) {
            document.getElementById('rejectMasjidName').textContent = nama;
            document.getElementById('rejectForm').action = `/ajk/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function showSuspendModal(id, nama) {
            document.getElementById('suspendMasjidName').textContent = nama;
            document.getElementById('suspendForm').action = `/ajk/${id}/suspend`;
            document.getElementById('suspendModal').classList.remove('hidden');
        }

        function showUnsuspendModal(id, nama) {
            document.getElementById('unsuspendMasjidName').textContent = nama;
            document.getElementById('unsuspendForm').action = `/ajk/${id}/reactivate`;
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

            // Set record name
            if (deleteRecordName) {
                deleteRecordName.textContent = recordName;
            }

            // Generate and set security code
            const code = generateSecurityCode();
            if (securityCode) {
                securityCode.textContent = code;
            }

            // Set form action
            if (deleteForm) {
                deleteForm.action = `/ajk/${recordId}`;
            }

            // Clear confirm code input and disable button
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

            // Show modal
            modal.classList.remove('hidden');

            // Focus on confirm code input
            if (confirmCode) {
                setTimeout(() => confirmCode.focus(), 100);
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) modal.classList.add('hidden');
        }

        // Initialize delete modal event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) hideDeleteModal();
                });
            }
        });

        // Close modal with Escape key
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
