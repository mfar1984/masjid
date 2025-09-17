<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'E-Masjid - Sistem Pengurusan Masjid' }}</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Senarai Masjid</h1>
                        <p class="text-xs text-gray-600">Senarai masjid, surau dan musolla yang berdaftar</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('masjids', 'create'))
                        <a href="{{ route('senarai-masjid.create') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                            Tambah Masjid
                        </a>
                        @endif
                        <a href="{{ route('senarai-masjid.export') }}" class="inline-flex items-center justify-center h-[32px] px-3 py-1 bg-green-100 text-gray-700 text-xs rounded hover:bg-green-200">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('senarai-masjid.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <!-- Search Input -->
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama masjid, lokasi, telefon..."
                        />

                        <!-- Dropdowns -->
                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="negeri"
                                :options="$negeriList->mapWithKeys(fn($negeri) => [$negeri => $negeri])"
                                :selected="request('negeri')"
                                placeholder="Semua Negeri"
                            />

                            <x-filter-dropdown
                                name="status"
                                :options="[
                                    'active' => 'Aktif',
                                    'pending' => 'Menunggu',
                                    'rejected' => 'Ditolak',
                                    'inactive' => 'Tidak Aktif',
                                    'suspended' => 'Digantung'
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
                                onclick="window.location.href='{{ route('senarai-masjid.index') }}'"
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
                                <th class="px-4 py-2 table-header">Kod</th>
                                <th class="px-4 py-2 table-header">Nama Masjid</th>
                                <th class="px-4 py-2 table-header">Kategori</th>
                                <th class="px-4 py-2 table-header">Lokasi</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header">Telefon</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($masjids as $masjid)
                            <tr class="hover:bg-white">
                                <td class="px-4 py-1">
                                    <div class="font-mono-poppins text-gray-900">{{ $masjid->kod_masjid }}</div>
                                </td>
                                <td class="px-4 py-2 table-data">
                                    <div class="flex items-center">
                                        <span class="text-sm mr-2">{{ $masjid->kategori_icon }}</span>
                                        <div>
                                            <div class="table-data-important text-gray-900">{{ $masjid->nama }}</div>
                                            @if($masjid->email)
                                                <div class="table-data text-gray-500">{{ $masjid->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ ucfirst($masjid->kategori) }}</td>
                                <td class="px-4 py-2 table-data text-gray-600">
                                    @if($masjid->latitude && $masjid->longitude)
                                        <a href="https://www.google.com/maps?q={{ $masjid->latitude }},{{ $masjid->longitude }}"
                                           target="_blank"
                                           class="hover:underline hover:text-blue-600 transition-colors">
                                            <div>{{ $masjid->bandar }}</div>
                                            <div class="text-gray-500">{{ $masjid->negeri }}</div>
                                        </a>
                                    @else
                                        <div>
                                            <div>{{ $masjid->bandar }}</div>
                                            <div class="text-gray-500">{{ $masjid->negeri }}</div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 table-data text-gray-600">{!! $masjid->status_badge !!}</td>
                                <td class="px-4 py-2 table-data text-gray-600">{{ $masjid->telefon ?? '-' }}</td>
                                <x-action-icons
                                    :record="$masjid"
                                    :show-route="route('senarai-masjid.show', $masjid)"
                                    :edit-route="route('senarai-masjid.edit', $masjid)"
                                    module="masjids"
                                    layout="desktop"
                                />
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Tiada data masjid dijumpai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($masjids as $masjid)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <span class="text-sm mr-2">{{ $masjid->kategori_icon }}</span>
                                    <h3 class="mobile-title text-gray-900">{{ $masjid->nama }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $masjid->kod_masjid }} • {{ ucfirst($masjid->kategori) }}</p>
                            </div>
                            <x-action-icons
                                :record="$masjid"
                                :show-route="route('senarai-masjid.show', $masjid)"
                                :edit-route="route('senarai-masjid.edit', $masjid)"
                                module="masjids"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <span class="mobile-subtitle text-gray-500">Status:</span>
                                <div class="mobile-data text-gray-900">{!! $masjid->status_badge !!}</div>
                            </div>
                            <div>
                                <span class="mobile-subtitle text-gray-500">Telefon:</span>
                                <p class="mobile-data text-gray-900">{{ $masjid->telefon ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="mobile-subtitle text-gray-500">Lokasi:</span>
                                @if($masjid->latitude && $masjid->longitude)
                                    <a href="https://www.google.com/maps?q={{ $masjid->latitude }},{{ $masjid->longitude }}"
                                       target="_blank"
                                       class="mobile-data text-gray-900 hover:underline hover:text-blue-600 transition-colors">
                                        {{ $masjid->bandar }}, {{ $masjid->negeri }}
                                    </a>
                                @else
                                    <p class="mobile-data text-gray-900">{{ $masjid->bandar }}, {{ $masjid->negeri }}</p>
                                @endif
                            </div>
                        </div>

                        @if($masjid->email)
                        <!-- Email -->
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center text-blue-600 hover:text-blue-800 text-xs">
                                <span class="material-icons text-sm mr-1">email</span>
                                <span>{{ $masjid->email }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                        <span class="material-icons text-gray-400 text-4xl mb-2">mosque</span>
                        <p class="text-gray-500 text-sm">Tiada data masjid dijumpai.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($masjids->hasPages())
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div class="text-xs text-gray-500 text-center sm:text-left">
                        Menunjukkan {{ $masjids->firstItem() }} hingga {{ $masjids->lastItem() }} daripada {{ $masjids->total() }} rekod
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        {{ $masjids->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Beautiful Approve Modal -->
    <x-approve-modal />

    <!-- Beautiful Reject Modal -->
    <x-reject-modal />

    <!-- Delete Confirmation Modal -->
    <x-delete-modal
        title="Padam Rekod Masjid"
        message="Adakah anda pasti mahu memadamkan rekod masjid untuk"
    />

    <!-- Suspend Confirmation Modal -->
    <x-suspend-modal />

    <!-- Unsuspend Confirmation Modal -->
    <x-unsuspend-modal />

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
                deleteForm.action = `/senarai-masjid/${recordId}`;
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

            // Focus on input
            if (confirmCode) {
                setTimeout(() => confirmCode.focus(), 100);
            }

            // Remove existing event listeners to prevent duplicates
            if (confirmCode) {
                const newConfirmCode = confirmCode.cloneNode(true);
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
            }
        });


    </script>
</body>
</html>
