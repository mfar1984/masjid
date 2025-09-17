<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pengguna - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Pengguna</h1>
                        <p class="text-xs text-gray-600">Senarai pengguna yang berdaftar dalam sistem</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('users', 'create'))
                        <a href="{{ route('senarai-pengguna.create') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">person_add</span>
                            Tambah Pengguna
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('senarai-pengguna.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama, email, telefon..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="role"
                                :options="$roleOptions"
                                :selected="request('role')"
                                placeholder="Semua Peranan"
                            />

                            <x-filter-dropdown
                                name="masjid"
                                :options="$masjidOptions"
                                :selected="request('masjid')"
                                placeholder="Semua Masjid"
                            />

                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'verified' => 'Disahkan',
                                    'unverified' => 'Belum Disahkan'
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
                                onclick="window.location.href='{{ route('senarai-pengguna.index') }}'"
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
                                <th class="px-4 py-2 table-header">Email</th>
                                <th class="px-4 py-2 table-header">Telefon</th>
                                <th class="px-4 py-2 table-header">Peranan</th>
                                <th class="px-4 py-2 table-header">Masjid</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-2 table-data">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div class="table-data-important text-gray-900">{{ $user->name }}</div>
                                            <div class="table-data text-gray-500">Bergabung {{ $user->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 table-data">
                                    <div class="table-data text-gray-900">{{ $user->email }}</div>
                                    @if($user->email_verified_at)
                                        <div class="table-data text-green-600">✓ Disahkan</div>
                                    @else
                                        <div class="table-data text-orange-600">⚠ Belum disahkan</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $user->phone ?? '-' }}</td>
                                <td class="px-4 py-2 table-data">
                                    @if($user->role)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                            @if($user->role->name === 'Super Admin') bg-purple-100 text-purple-800
                                            @elseif($user->role->name === 'Admin Masjid') bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            @if($user->role->name === 'Super Admin')
                                                <span class="material-icons mr-1" style="font-size: 12px !important;">admin_panel_settings</span>
                                            @elseif($user->role->name === 'Admin Masjid')
                                                <span class="material-icons mr-1" style="font-size: 12px !important;">mosque</span>
                                            @else
                                                <span class="material-icons mr-1" style="font-size: 12px !important;">person</span>
                                            @endif
                                            {{ $user->role->name }}
                                        </span>
                                    @else
                                        <span class="table-data text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data">
                                    @if($user->masjid)
                                        <div class="flex items-center">
                                            <span class="text-sm mr-1">🕌</span>
                                            <span class="table-data text-gray-900">{{ $user->masjid->nama }}</span>
                                        </div>
                                    @else
                                        <span class="table-data text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">verified_user</span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">
                                            <span class="material-icons mr-1" style="font-size: 12px !important;">pending</span>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <!-- User Actions with Verify/Unverify -->
                                <td class="px-4 py-2 table-data text-center space-x-1">
                                    <!-- Verify/Unverify Actions -->
                                    @if(!$user->email_verified_at)
                                        @if(auth()->user()->hasPermission('users', 'reactivate'))
                                            <button type="button"
                                                    class="text-green-600 hover:text-green-800 action-icon"
                                                    title="Sahkan Pengguna"
                                                    onclick="showVerifyModal({{ $user->id }}, '{{ $user->name }}')">
                                                <span class="material-icons text-[8px]">check</span>
                                            </button>
                                        @endif
                                    @else
                                        @if(auth()->user()->hasPermission('users', 'suspend') && $user->id !== auth()->id())
                                            <button type="button"
                                                    class="text-orange-600 hover:text-orange-800 action-icon"
                                                    title="Nyahaktifkan Pengguna"
                                                    onclick="showUnverifyModal({{ $user->id }}, '{{ $user->name }}')">
                                                <span class="material-icons text-[8px]">close</span>
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Standard Actions -->
                                    @if(auth()->user()->hasPermission('users', 'read'))
                                        <x-icons.view-icon :route="route('senarai-pengguna.show', $user)" size="desktop" />
                                    @endif

                                    @if(auth()->user()->hasPermission('users', 'update'))
                                        <x-icons.edit-icon :route="route('senarai-pengguna.edit', $user)" size="desktop" />
                                    @endif

                                    @if(auth()->user()->hasPermission('users', 'delete') && $user->id !== auth()->id())
                                        <x-icons.delete-icon :id="$user->id" :nama="$user->name" size="desktop" />
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <span class="material-icons mb-2" style="font-size: 48px !important;">people</span>
                                    <p class="text-sm">Tiada pengguna dijumpai</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($users as $user)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $user->name }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $user->email }}</p>
                            </div>
                            <!-- Mobile User Actions -->
                            <div class="flex items-center space-x-2">
                                <!-- Verify/Unverify Actions -->
                                @if(!$user->email_verified_at)
                                    @if(auth()->user()->hasPermission('users', 'reactivate'))
                                        <button type="button"
                                                class="p-2 text-green-600 hover:text-green-800 rounded-full hover:bg-green-50"
                                                title="Sahkan Pengguna"
                                                onclick="showVerifyModal({{ $user->id }}, '{{ $user->name }}')">
                                            <span class="material-icons text-sm">check</span>
                                        </button>
                                    @endif
                                @else
                                    @if(auth()->user()->hasPermission('users', 'suspend') && $user->id !== auth()->id())
                                        <button type="button"
                                                class="p-2 text-orange-600 hover:text-orange-800 rounded-full hover:bg-orange-50"
                                                title="Nyahaktifkan Pengguna"
                                                onclick="showUnverifyModal({{ $user->id }}, '{{ $user->name }}')">
                                            <span class="material-icons text-sm">close</span>
                                        </button>
                                    @endif
                                @endif

                                <!-- Standard Actions -->
                                @if(auth()->user()->hasPermission('users', 'read'))
                                    <x-icons.view-icon :route="route('senarai-pengguna.show', $user)" size="mobile" />
                                @endif

                                @if(auth()->user()->hasPermission('users', 'update'))
                                    <x-icons.edit-icon :route="route('senarai-pengguna.edit', $user)" size="mobile" />
                                @endif

                                @if(auth()->user()->hasPermission('users', 'delete') && $user->id !== auth()->id())
                                    <x-icons.delete-icon :id="$user->id" :nama="$user->name" size="mobile" />
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Peranan</p>
                                @if($user->role)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                        @if($user->role->name === 'Super Admin') bg-purple-100 text-purple-800
                                        @elseif($user->role->name === 'Admin Masjid') bg-green-100 text-green-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="mobile-data text-gray-500">-</span>
                                @endif
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex justify-between items-center text-xs">
                                <div>
                                    <span class="mobile-label text-gray-500">Telefon: </span>
                                    <span class="mobile-data text-gray-900">{{ $user->phone ?? '-' }}</span>
                                </div>
                                <div>
                                    @if($user->masjid)
                                        <span class="mobile-label text-gray-500">Masjid: </span>
                                        <span class="mobile-data text-gray-900">{{ Str::limit($user->masjid->nama, 20) }}</span>
                                    @else
                                        <span class="mobile-data text-gray-500">Global Access</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">people</span>
                        <p class="text-sm text-gray-500">Tiada pengguna dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs text-gray-500 text-center sm:text-left">
                        Menunjukkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} daripada {{ $users->total() }} rekod
                    </div>
                    @if($users->hasPages())
                    <div class="flex justify-center sm:justify-end">
                        {{ $users->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Verify User Modal -->
    <x-verify-modal />

    <!-- Unverify User Modal -->
    <x-unverify-modal />

    <!-- Delete Confirmation Modal -->
    <x-delete-modal
        title="Padam Pengguna"
        message="Adakah anda pasti mahu memadamkan pengguna"
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
                deleteForm.action = `/senarai-pengguna/${recordId}`;
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
                closeVerifyModal();
                closeUnverifyModal();
            }
        });
    </script>
</body>
</html>
