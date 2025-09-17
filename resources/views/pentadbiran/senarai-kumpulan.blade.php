<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Kumpulan - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Kumpulan</h1>
                        <p class="text-xs text-gray-600">Pengurusan kumpulan akses dan kebenaran pengguna</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('roles', 'create'))
                        <a href="{{ route('senarai-kumpulan.create') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                            Tambah Kumpulan
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('senarai-kumpulan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama kumpulan, penerangan..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="type"
                                :options="[
                                    'system' => 'Sistem',
                                    'custom' => 'Tersuai',
                                    'global' => 'Global',
                                    'masjid' => 'Masjid'
                                ]"
                                :selected="request('type')"
                                placeholder="Semua Jenis"
                            />

                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'active' => 'Aktif',
                                    'inactive' => 'Tidak Aktif'
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
                                onclick="window.location.href='{{ route('senarai-kumpulan.index') }}'"
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
                                <th class="px-4 py-2 table-header">Kumpulan</th>
                                <th class="px-4 py-2 table-header">Skop</th>
                                <th class="px-4 py-2 table-header">Masjid</th>
                                <th class="px-4 py-2 table-header">Kebenaran</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($roles as $role)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="flex items-center">
                                        <span class="text-sm mr-2">{{ $role->type_icon }}</span>
                                        <div>
                                            <div class="table-data-important text-gray-900">{{ $role->name }}</div>
                                            @if($role->description)
                                                <div class="table-data text-gray-500">{{ Str::limit($role->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 table-data">
                                    @if($role->is_system_role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">lock</span>
                                            Sistem
                                        </span>
                                    @elseif($role->masjid_id)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">mosque</span>
                                            Masjid
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">public</span>
                                            Global
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data">
                                    @if($role->masjid_id)
                                        <div class="flex items-center">
                                            <span class="text-sm mr-1">🕌</span>
                                            <span class="table-data text-gray-900">{{ $role->masjid->nama ?? 'N/A' }}</span>
                                        </div>
                                    @else
                                        <span class="table-data text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data">
                                    <span class="table-data text-gray-600">{{ $role->permission_count }} kebenaran</span>
                                </td>
                                <td class="px-4 py-2 table-data">
                                    {!! $role->status_badge !!}
                                </td>
                                <x-action-icons
                                    :record="$role"
                                    :show-route="route('senarai-kumpulan.show', $role)"
                                    :edit-route="route('senarai-kumpulan.edit', $role)"
                                    :delete-route="$role->is_system_role ? null : route('senarai-kumpulan.destroy', $role)"
                                    module="roles"
                                    layout="desktop"
                                />
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">groups</span>
                                    <p class="text-sm">Tiada kumpulan akses dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($roles as $role)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <span class="text-sm mr-2">{{ $role->type_icon }}</span>
                                    <h3 class="mobile-title text-gray-900">{{ $role->name }}</h3>
                                </div>
                                @if($role->description)
                                    <p class="mobile-subtitle text-gray-500">{{ Str::limit($role->description, 60) }}</p>
                                @endif
                            </div>
                            <x-action-icons
                                :record="$role"
                                :show-route="route('senarai-kumpulan.show', $role)"
                                :edit-route="route('senarai-kumpulan.edit', $role)"
                                :delete-route="$role->is_system_role ? null : route('senarai-kumpulan.destroy', $role)"
                                module="roles"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Skop</p>
                                @if($role->is_system_role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                        <span class="material-icons mr-1" style="font-size: 12px !important;">lock</span>
                                        Sistem
                                    </span>
                                @elseif($role->masjid_id)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                        <span class="material-icons mr-1" style="font-size: 12px !important;">mosque</span>
                                        Masjid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="material-icons mr-1" style="font-size: 12px !important;">public</span>
                                        Global
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                {!! $role->status_badge !!}
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex justify-between items-center text-xs">
                                <div>
                                    @if($role->masjid_id)
                                        <span class="mobile-label text-gray-500">Masjid: </span>
                                        <span class="mobile-data text-gray-900">{{ $role->masjid->nama ?? 'N/A' }}</span>
                                    @else
                                        <span class="mobile-data text-gray-500">Global Access</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="mobile-label text-gray-500">Kebenaran: </span>
                                    <span class="mobile-data text-gray-900">{{ $role->permission_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">groups</span>
                        <p class="text-sm text-gray-500">Tiada kumpulan akses dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs text-gray-500 text-center sm:text-left">
                        Menunjukkan {{ $roles->firstItem() }} hingga {{ $roles->lastItem() }} daripada {{ $roles->total() }} rekod
                    </div>
                    @if($roles->hasPages())
                    <div class="flex justify-center sm:justify-end">
                        {{ $roles->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Confirmation Modal -->
    <x-delete-modal
        title="Padam Kumpulan Akses"
        message="Adakah anda pasti mahu memadamkan kumpulan akses"
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

            if (!modal) {
                return;
            }

            // Set record name
            if (deleteRecordName) {
                deleteRecordName.textContent = recordName;
            }

            // Generate and display security code
            const code = generateSecurityCode();

            if (securityCode) {
                securityCode.textContent = code;
            }

            // Set form action
            if (deleteForm) {
                deleteForm.action = `/senarai-kumpulan/${recordId}`;
            }

            // Reset input and button state
            if (confirmCode) {
                confirmCode.value = '';
            }
            if (confirmDeleteBtn) {
                confirmDeleteBtn.disabled = true;
                confirmDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // Show modal
            modal.classList.remove('hidden');

            // Remove existing event listeners to prevent duplicates
            const newConfirmCode = confirmCode.cloneNode(true);
            if (confirmCode && confirmCode.parentNode) {
                confirmCode.parentNode.replaceChild(newConfirmCode, confirmCode);

                // Add fresh event listener
                newConfirmCode.addEventListener('input', function() {
                    if (this.value.toUpperCase() === code) {
                        if (confirmDeleteBtn) {
                            confirmDeleteBtn.disabled = false;
                            confirmDeleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    } else {
                        if (confirmDeleteBtn) {
                            confirmDeleteBtn.disabled = true;
                            confirmDeleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }
                    }
                });
            }
        }

        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            if (modal) {
                modal.classList.add('hidden');
            } else {
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

            // Add form submission debugging
            const deleteForm = document.getElementById('deleteForm');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {

                    // Let the form submit normally
                    return true;
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
            }
        });
    </script>
</body>
</html>
