<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelulusan Pelupusan - E-Masjid</title>
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
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Kelulusan Pelupusan</h1>
                    <p class="text-xs text-gray-600">Proses kelulusan permohonan pelupusan aset</p>
                </div>

                <x-statistics-grid :stats="$stats" />

                <form method="GET" action="{{ route('kelulusan-pelupusan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input name="search" :value="request('search')" placeholder="Cari no. rujukan, aset..." />
                        <div class="flex gap-2">
                            <x-filter-dropdown name="status" :options="['Menunggu' => 'Menunggu', 'Diluluskan' => 'Diluluskan', 'Ditolak' => 'Ditolak', 'Selesai' => 'Selesai']" :selected="request('status', 'Menunggu')" placeholder="Semua Status" />
                        </div>
                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">Cari</x-action-button>
                            <x-action-button type="button" icon="refresh" color="red" onclick="window.location.href='{{ route('kelulusan-pelupusan.index') }}'">Reset</x-action-button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-orange-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">No. Rujukan</th>
                                <th class="px-4 py-2 table-header">Aset</th>
                                <th class="px-4 py-2 table-header">Pemohon</th>
                                <th class="px-4 py-2 table-header">Kaedah</th>
                                <th class="px-4 py-2 table-header text-right">Nilai (RM)</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($permohonanPelupusan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_rujukan }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->tarikh_permohonan->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $item->senariAset->nama_aset ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->senariAset->no_siri ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->createdBy->name ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->kaedah_pelupusan }}</td>
                                    <td class="px-4 py-2 table-data text-right text-gray-600">{{ number_format($item->nilai_pelupusan, 2) }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status === 'Menunggu')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Menunggu</span>
                                        @elseif($item->status === 'Diluluskan')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Diluluskan</span>
                                        @elseif($item->status === 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            <a href="{{ route('kelulusan-pelupusan.show', $item) }}" class="p-1 text-blue-600 hover:text-blue-800" title="Lihat">
                                                <span class="material-icons" style="font-size: 18px !important;">visibility</span>
                                            </a>
                                            @if($item->status === 'Menunggu' && auth()->user()->hasPermission('kelulusan_pelupusan', 'approve'))
                                                <button type="button" onclick="showApproveModal({{ $item->id }})" class="p-1 text-green-600 hover:text-green-800" title="Luluskan">
                                                    <span class="material-icons" style="font-size: 18px !important;">check_circle</span>
                                                </button>
                                                <button type="button" onclick="showRejectModal({{ $item->id }})" class="p-1 text-red-600 hover:text-red-800" title="Tolak">
                                                    <span class="material-icons" style="font-size: 18px !important;">cancel</span>
                                                </button>
                                            @endif
                                            @if($item->status === 'Diluluskan' && auth()->user()->hasPermission('kelulusan_pelupusan', 'approve'))
                                                <button type="button" onclick="showCompleteModal({{ $item->id }})" class="p-1 text-purple-600 hover:text-purple-800" title="Selesaikan">
                                                    <span class="material-icons" style="font-size: 18px !important;">task_alt</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">approval</span>
                                        <p class="text-sm">Tiada permohonan menunggu kelulusan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-3">
                    @forelse($permohonanPelupusan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->no_rujukan }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->senariAset->nama_aset ?? '-' }}</p>
                            </div>
                            @if($item->status === 'Menunggu')
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">Menunggu</span>
                            @endif
                        </div>
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('kelulusan-pelupusan.show', $item) }}" class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded">Lihat</a>
                            @if($item->status === 'Menunggu')
                                <button onclick="showApproveModal({{ $item->id }})" class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded">Lulus</button>
                                <button onclick="showRejectModal({{ $item->id }})" class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded">Tolak</button>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">approval</span>
                        <p class="text-sm text-gray-500">Tiada permohonan menunggu kelulusan</p>
                    </div>
                    @endforelse
                </div>

                @if($permohonanPelupusan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $permohonanPelupusan->firstItem() }} hingga {{ $permohonanPelupusan->lastItem() }} daripada {{ $permohonanPelupusan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $permohonanPelupusan->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Luluskan Permohonan</h3>
            <form id="approveForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Catatan (Pilihan)</label>
                    <textarea name="catatan_kelulusan" rows="3" class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs text-white bg-green-600 rounded hover:bg-green-700">Luluskan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Permohonan</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sebab Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan_kelulusan" rows="3" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs text-white bg-red-600 rounded hover:bg-red-700">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Complete Modal -->
    <div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Selesaikan Pelupusan</h3>
            <form id="completeForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Tarikh Pelupusan <span class="text-red-500">*</span></label>
                    <input type="date" name="tarikh_pelupusan" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCompleteModal()" class="px-4 py-2 text-xs text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs text-white bg-purple-600 rounded hover:bg-purple-700">Selesaikan</button>
                </div>
            </form>
        </div>
    </div>

    <x-footer />

    <script>
        function showApproveModal(id) {
            document.getElementById('approveForm').action = '/kelulusan-pelupusan/' + id + '/approve';
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }
        function showRejectModal(id) {
            document.getElementById('rejectForm').action = '/kelulusan-pelupusan/' + id + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
        function showCompleteModal(id) {
            document.getElementById('completeForm').action = '/kelulusan-pelupusan/' + id + '/complete';
            document.getElementById('completeModal').classList.remove('hidden');
            document.getElementById('completeModal').classList.add('flex');
        }
        function closeCompleteModal() {
            document.getElementById('completeModal').classList.add('hidden');
            document.getElementById('completeModal').classList.remove('flex');
        }
    </script>
</body>
</html>
