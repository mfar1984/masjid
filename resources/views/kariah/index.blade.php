<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahli Kariah - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Ahli Kariah</h1>
                        <p class="text-xs text-gray-600">Senarai ahli kariah yang berdaftar</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('kariah', 'create'))
                            <a href="{{ route('kariah.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">person_add</span>
                                Tambah Ahli
                            </a>
                        @endif
                        <a href="{{ route('kariah.export') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search (Same pattern as senarai-pengguna) -->
                <form method="GET" action="{{ route('kariah.index') }}" class="mb-4">
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
                                onclick="window.location.href='{{ route('kariah.index') }}'"
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
                                <th class="px-4 py-2 table-header">Umur</th>
                                <th class="px-4 py-2 table-header">No. IC</th>
                                <th class="px-4 py-2 table-header">Telefon</th>
                                <th class="px-4 py-2 table-header">Tarikh Keahlian</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($kariah as $member)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data-important text-gray-900">{{ $member->nama }}</div>
                                    <div class="table-data text-gray-500">{{ $member->no_ic }}</div>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->umur }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->no_ic }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->telefon }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $member->tarikh_keahlian_formatted }}</td>
                                <td class="px-4 py-2 table-data">
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $member->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                                <x-action-icons
                                    :record="$member"
                                    :show-route="route('kariah.show', $member)"
                                    :edit-route="route('kariah.edit', $member)"
                                    module="kariah"
                                    layout="desktop"
                                />
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">people</span>
                                    <p class="text-sm">Tiada ahli kariah dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($kariah as $member)
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
                                <p class="mobile-subtitle text-gray-500">{{ $member->no_ic }}</p>
                            </div>
                            <x-action-icons
                                :record="$member"
                                :show-route="route('kariah.show', $member)"
                                :edit-route="route('kariah.edit', $member)"
                                module="kariah"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Telefon</p>
                                <span class="mobile-data text-gray-900">{{ $member->telefon }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Umur</p>
                                <span class="mobile-data text-gray-900">{{ $member->umur }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh Keahlian</p>
                                <span class="mobile-data text-gray-900">{{ $member->tarikh_keahlian_formatted }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $member->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $member->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">people</span>
                        <p class="text-sm text-gray-500">Tiada ahli kariah dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($kariah->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $kariah->firstItem() }} hingga {{ $kariah->lastItem() }} daripada {{ $kariah->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $kariah->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Ahli Kariah"
        message="Adakah anda pasti mahu memadamkan rekod ahli kariah untuk"
    />

    <script>
        // Override modal actions for kariah
        function showApproveModal(id, nama) {
            document.getElementById('approveMasjidName').textContent = nama;
            document.getElementById('approveForm').action = `/kariah/${id}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function showRejectModal(id, nama) {
            document.getElementById('rejectMasjidName').textContent = nama;
            document.getElementById('rejectForm').action = `/kariah/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function showSuspendModal(id, nama) {
            document.getElementById('suspendMasjidName').textContent = nama;
            document.getElementById('suspendForm').action = `/kariah/${id}/suspend`;
            document.getElementById('suspendModal').classList.remove('hidden');
        }

        function showUnsuspendModal(id, nama) {
            document.getElementById('unsuspendMasjidName').textContent = nama;
            document.getElementById('unsuspendForm').action = `/kariah/${id}/reactivate`;
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

            if (!modal) {
                return;
            }

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
                deleteForm.action = `/kariah/${recordId}`;
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
                setTimeout(() => {
                    confirmCode.focus();
                }, 100);
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Initialize delete modal event listeners when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');

            if (deleteModal) {
                // Close modal when clicking outside
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideDeleteModal();
                    }
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
