<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempahan Fasiliti - E-Masjid</title>
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
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Tempahan Fasiliti</h1>
                        <p class="text-xs text-gray-600">Senarai tempahan fasiliti masjid</p>
                    </div>
                    @if(auth()->user()->hasPermission('tempahan_fasiliti', 'create'))
                        <a href="{{ route('tempahan-fasiliti.create') }}" class="inline-flex items-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                            Tambah Tempahan
                        </a>
                    @endif
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('tempahan-fasiliti.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no. tempahan, nama penyewa..."
                        />

                        <div class="flex gap-2">
                            <select name="senarai_fasiliti_id" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Fasiliti</option>
                                @foreach($fasilitiList as $fasiliti)
                                    <option value="{{ $fasiliti->id }}" {{ request('senarai_fasiliti_id') == $fasiliti->id ? 'selected' : '' }}>
                                        {{ $fasiliti->nama_fasiliti }}
                                    </option>
                                @endforeach
                            </select>

                            <x-filter-dropdown
                                name="status_tempahan"
                                :options="[
                                    'Baharu' => 'Baharu',
                                    'Dalam Semakan' => 'Dalam Semakan',
                                    'Lulus' => 'Lulus',
                                    'Ditolak' => 'Ditolak',
                                    'Dibatalkan' => 'Dibatalkan',
                                    'Selesai' => 'Selesai'
                                ]"
                                :selected="request('status_tempahan')"
                                placeholder="Semua Status"
                            />

                            <x-filter-dropdown
                                name="status_pemulangan"
                                :options="[
                                    'Belum Pulang' => 'Belum Pulang',
                                    'Sudah Pulang' => 'Sudah Pulang',
                                    'Lewat' => 'Lewat',
                                    'Sebahagian' => 'Sebahagian'
                                ]"
                                :selected="request('status_pemulangan')"
                                placeholder="Status Pulangan"
                            />

                            <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" placeholder="Tarikh Dari" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            
                            <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" placeholder="Tarikh Hingga" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">
                                Cari
                            </x-action-button>
                            <x-action-button
                                type="button"
                                icon="refresh"
                                color="red"
                                onclick="window.location.href='{{ route('tempahan-fasiliti.index') }}'"
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
                                <th class="px-4 py-2 table-header">No. Tempahan</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Nama Penyewa</th>
                                <th class="px-4 py-2 table-header">Fasiliti</th>
                                <th class="px-4 py-2 table-header">Tarikh Mula - Tamat</th>
                                <th class="px-4 py-2 table-header">Jumlah Bayaran</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header">Pulangan</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($tempahanFasiliti as $tempahan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <a href="{{ route('tempahan-fasiliti.show', $tempahan->id) }}" class="text-blue-600 hover:underline font-semibold">
                                        {{ $tempahan->no_tempahan }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">{{ $tempahan->tarikh_tempahan ? \Carbon\Carbon::parse($tempahan->tarikh_tempahan)->format('d/m/Y') : '-' }}</td>
                                <td class="px-4 py-3">{{ $tempahan->nama_penyewa }}</td>
                                <td class="px-4 py-3">{{ $tempahan->senariFasiliti->nama_fasiliti ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    {{ $tempahan->tarikh_mula ? \Carbon\Carbon::parse($tempahan->tarikh_mula)->format('d/m/Y') : '-' }} - 
                                    {{ $tempahan->tarikh_tamat ? \Carbon\Carbon::parse($tempahan->tarikh_tamat)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">RM {{ number_format($tempahan->jumlah_bayaran, 2) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($tempahan->status_tempahan === 'Lulus')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                    @elseif($tempahan->status_tempahan === 'Baharu')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Baharu</span>
                                    @elseif($tempahan->status_tempahan === 'Dalam Semakan')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Dalam Semakan</span>
                                    @elseif($tempahan->status_tempahan === 'Ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                    @elseif($tempahan->status_tempahan === 'Selesai')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Selesai</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $tempahan->status_tempahan }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($tempahan->status_tempahan === 'Lulus')
                                        @if($tempahan->status_pemulangan === 'Sudah Pulang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                        @elseif($tempahan->status_pemulangan === 'Lewat')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                        @elseif($tempahan->status_pemulangan === 'Sebahagian')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Sebahagian</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Belum Pulang</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        @if(auth()->user()->hasPermission('tempahan_fasiliti', 'read'))
                                            <a href="{{ route('tempahan-fasiliti.show', $tempahan) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                                <span class="material-icons" style="font-size: 18px;">visibility</span>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') && in_array($tempahan->status_tempahan, ['Baharu', 'Dalam Semakan']))
                                            <a href="{{ route('tempahan-fasiliti.edit', $tempahan) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                <span class="material-icons" style="font-size: 18px;">edit</span>
                                            </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') && $tempahan->status_tempahan === 'Lulus' && $tempahan->status_pemulangan !== 'Sudah Pulang')
                                            <button type="button" onclick="showPulangModal({{ $tempahan->id }}, '{{ $tempahan->no_tempahan }}')" class="text-green-600 hover:text-green-800" title="Pulangkan">
                                                <span class="material-icons" style="font-size: 18px;">assignment_return</span>
                                            </button>
                                        @endif
                                        @if(auth()->user()->hasPermission('tempahan_fasiliti', 'delete') && in_array($tempahan->status_tempahan, ['Baharu', 'Ditolak', 'Dibatalkan']))
                                            <button type="button" onclick="showDeleteModal({{ $tempahan->id }}, '{{ $tempahan->no_tempahan }}')" class="text-red-600 hover:text-red-800" title="Padam">
                                                <span class="material-icons" style="font-size: 18px;">delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    Tiada tempahan dijumpai
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-4">
                    @forelse($tempahanFasiliti as $tempahan)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <a href="{{ route('tempahan-fasiliti.show', $tempahan->id) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                    {{ $tempahan->no_tempahan }}
                                </a>
                                <p class="text-[10px] text-gray-500">{{ $tempahan->tarikh_tempahan ? \Carbon\Carbon::parse($tempahan->tarikh_tempahan)->format('d/m/Y') : '-' }}</p>
                            </div>
                            <div class="flex flex-col items-end space-y-1">
                                @if($tempahan->status_tempahan === 'Lulus')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                @elseif($tempahan->status_tempahan === 'Baharu')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-blue-100 text-blue-800">Baharu</span>
                                @elseif($tempahan->status_tempahan === 'Dalam Semakan')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Dalam Semakan</span>
                                @elseif($tempahan->status_tempahan === 'Ditolak')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                @elseif($tempahan->status_tempahan === 'Selesai')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-gray-100 text-gray-800">Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $tempahan->status_tempahan }}</span>
                                @endif
                                @if($tempahan->status_tempahan === 'Lulus')
                                    @if($tempahan->status_pemulangan === 'Sudah Pulang')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                    @elseif($tempahan->status_pemulangan === 'Lewat')
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium bg-red-100 text-red-800">Lewat</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-[10px] font-medium bg-orange-100 text-orange-800">Belum Pulang</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2 mb-3">
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Penyewa:</span>
                                <span class="font-semibold text-gray-900">{{ $tempahan->nama_penyewa }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Fasiliti:</span>
                                <span class="font-semibold text-gray-900">{{ $tempahan->senariFasiliti->nama_fasiliti ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Tarikh:</span>
                                <span class="text-gray-900">
                                    {{ $tempahan->tarikh_mula ? \Carbon\Carbon::parse($tempahan->tarikh_mula)->format('d/m/Y') : '-' }} - 
                                    {{ $tempahan->tarikh_tamat ? \Carbon\Carbon::parse($tempahan->tarikh_tamat)->format('d/m/Y') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-gray-600">Jumlah:</span>
                                <span class="font-bold text-gray-900">RM {{ number_format($tempahan->jumlah_bayaran, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2">
                            @if(auth()->user()->hasPermission('tempahan_fasiliti', 'read'))
                                <a href="{{ route('tempahan-fasiliti.show', $tempahan) }}" class="text-blue-600 hover:text-blue-800">
                                    <span class="material-icons" style="font-size: 18px;">visibility</span>
                                </a>
                            @endif
                            @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') && in_array($tempahan->status_tempahan, ['Baharu', 'Dalam Semakan']))
                                <a href="{{ route('tempahan-fasiliti.edit', $tempahan) }}" class="text-yellow-600 hover:text-yellow-800">
                                    <span class="material-icons" style="font-size: 18px;">edit</span>
                                </a>
                            @endif
                            @if(auth()->user()->hasPermission('tempahan_fasiliti', 'update') && $tempahan->status_tempahan === 'Lulus' && $tempahan->status_pemulangan !== 'Sudah Pulang')
                                <button type="button" onclick="showPulangModal({{ $tempahan->id }}, '{{ $tempahan->no_tempahan }}')" class="text-green-600 hover:text-green-800">
                                    <span class="material-icons" style="font-size: 18px;">assignment_return</span>
                                </button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 text-xs">
                        Tiada tempahan dijumpai
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($tempahanFasiliti->hasPages())
                <div class="mt-6">
                    {{ $tempahanFasiliti->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Modal -->
    <x-delete-modal
        title="Padam Tempahan Fasiliti"
        message="Adakah anda pasti mahu memadamkan tempahan"
    />

    <!-- Pulang Modal -->
    <div id="pulangModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-900">Rekod Pemulangan</h3>
                    <button type="button" onclick="hidePulangModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="material-icons" style="font-size: 20px;">close</span>
                    </button>
                </div>
                <p class="text-xs text-gray-600 mb-4">Tempahan: <span id="pulangRecordName" class="font-semibold"></span></p>
                
                <form id="pulangForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="kondisi_selepas" class="block text-xs font-medium text-gray-700 mb-2">Kondisi Selepas Pulang *</label>
                        <select name="kondisi_selepas" id="kondisi_selepas" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs">
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Rosak Ringan">Rosak Ringan</option>
                            <option value="Rosak Teruk">Rosak Teruk</option>
                            <option value="Hilang">Hilang</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="catatan_pulangan" class="block text-xs font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="catatan_pulangan" id="catatan_pulangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs" placeholder="Catatan tambahan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="hidePulangModal()" class="px-4 py-2 bg-gray-200 text-gray-700 text-xs rounded hover:bg-gray-300">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700">Rekod Pulangan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function generateSecurityCode() {
            return Math.random().toString(36).substring(2, 8).toUpperCase();
        }

        function showPulangModal(recordId, recordName) {
            const modal = document.getElementById('pulangModal');
            const pulangRecordName = document.getElementById('pulangRecordName');
            const pulangForm = document.getElementById('pulangForm');

            if (pulangRecordName) {
                pulangRecordName.textContent = recordName;
            }

            if (pulangForm) {
                pulangForm.action = `/tempahan-fasiliti/${recordId}/pulang`;
            }

            modal.classList.remove('hidden');
        }

        function hidePulangModal() {
            const modal = document.getElementById('pulangModal');
            if (modal) {
                modal.classList.add('hidden');
            }
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
                deleteForm.action = `/tempahan-fasiliti/${recordId}`;
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
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideDeleteModal();
                    }
                });
            }

            const pulangModal = document.getElementById('pulangModal');
            if (pulangModal) {
                pulangModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hidePulangModal();
                    }
                });
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideDeleteModal();
                hidePulangModal();
            }
        });
    </script>
</body>
</html>
