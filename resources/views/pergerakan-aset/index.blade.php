<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pergerakan Aset - E-Masjid</title>
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
                <!-- Header -->
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Pergerakan Aset</h1>
                        <p class="text-xs text-gray-600">Pengurusan pergerakan dan pemindahan aset</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('pergerakan_aset', 'create'))
                            <a href="{{ route('pergerakan-aset.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Pergerakan
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('pergerakan-aset.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no. pergerakan, nama peminjam..."
                        />

                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="jenis_pergerakan"
                                :options="[
                                    'Pemindahan Dalaman' => 'Pemindahan Dalaman',
                                    'Pemindahan Luaran' => 'Pemindahan Luaran',
                                    'Pinjaman' => 'Pinjaman',
                                    'Sewa' => 'Sewa',
                                    'Penyelenggaraan' => 'Penyelenggaraan',
                                    'Pulangan' => 'Pulangan'
                                ]"
                                :selected="request('jenis_pergerakan')"
                                placeholder="Semua Jenis"
                            />
                            <x-filter-dropdown
                                name="status_pulangan"
                                :options="[
                                    'Belum Pulang' => 'Belum Pulang',
                                    'Sebahagian' => 'Sebahagian',
                                    'Sudah Pulang' => 'Sudah Pulang',
                                    'Lewat' => 'Lewat',
                                    'Hilang' => 'Hilang'
                                ]"
                                :selected="request('status_pulangan')"
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
                                onclick="window.location.href='{{ route('pergerakan-aset.index') }}'"
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
                                <th class="px-4 py-2 table-header">No. Pergerakan</th>
                                <th class="px-4 py-2 table-header">Aset</th>
                                <th class="px-4 py-2 table-header">Kuantiti</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Jenis</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($pergerakanAset as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_pergerakan }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->senariAset->nama_aset ?? '-' }}</div>
                                        <div class="text-[10px] text-gray-500">{{ $item->senariAset->no_aset ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="text-xs text-gray-900">{{ $item->kuantiti_dipulangkan }}/{{ $item->kuantiti }}</div>
                                        @if($item->kuantiti_hilang > 0)
                                            <div class="text-[10px] text-red-600">Hilang: {{ $item->kuantiti_hilang }}</div>
                                        @elseif($item->kuantiti - $item->kuantiti_dipulangkan > 0 && !in_array($item->status_pulangan, ['Sudah Pulang', 'Hilang']))
                                            <div class="text-[10px] text-orange-600">Baki: {{ $item->kuantiti - $item->kuantiti_dipulangkan }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_pergerakan ? \Carbon\Carbon::parse($item->tarikh_pergerakan)->format('d/m/Y') : '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->jenis_pergerakan }}</td>
                                    <td class="px-4 py-2 table-data">
                                        @if($item->status_pulangan === 'Sudah Pulang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                        @elseif($item->status_pulangan === 'Sebahagian')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Sebahagian</span>
                                        @elseif($item->status_pulangan === 'Lewat')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                        @elseif($item->status_pulangan === 'Hilang')
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_pulangan }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data text-center">
                                        <div class="flex items-center justify-center space-x-1">
                                            @if(auth()->user()->hasPermission('pergerakan_aset', 'read'))
                                                <a href="{{ route('pergerakan-aset.show', $item) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                                    <span class="material-icons" style="font-size: 18px !important;">visibility</span>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('pergerakan_aset', 'update'))
                                                <a href="{{ route('pergerakan-aset.edit', $item) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                                    <span class="material-icons" style="font-size: 18px !important;">edit</span>
                                                </a>
                                                @if(in_array($item->status_pulangan, ['Belum Pulang', 'Sebahagian', 'Lewat']))
                                                    <button type="button" onclick="openPulanganModal({{ $item->id }})" class="text-green-600 hover:text-green-800" title="Rekod Pulangan">
                                                        <span class="material-icons" style="font-size: 18px !important;">assignment_return</span>
                                                    </button>
                                                @endif
                                            @endif
                                            @if(auth()->user()->hasPermission('pergerakan_aset', 'delete'))
                                                <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800" title="Padam">
                                                    <span class="material-icons" style="font-size: 18px !important;">delete</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">swap_horiz</span>
                                        <p class="text-sm">Tiada pergerakan aset dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($pergerakanAset as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="mobile-title text-gray-900">{{ $item->no_pergerakan }}</h3>
                                <p class="mobile-subtitle text-gray-500">{{ $item->senariAset->nama_aset ?? '-' }}</p>
                            </div>
                            <div class="flex items-center space-x-1">
                                @if(auth()->user()->hasPermission('pergerakan_aset', 'read'))
                                    <a href="{{ route('pergerakan-aset.show', $item) }}" class="text-blue-600 hover:text-blue-800">
                                        <span class="material-icons" style="font-size: 18px !important;">visibility</span>
                                    </a>
                                @endif
                                @if(auth()->user()->hasPermission('pergerakan_aset', 'update'))
                                    <a href="{{ route('pergerakan-aset.edit', $item) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <span class="material-icons" style="font-size: 18px !important;">edit</span>
                                    </a>
                                    @if(in_array($item->status_pulangan, ['Belum Pulang', 'Sebahagian', 'Lewat']))
                                        <button type="button" onclick="openPulanganModal({{ $item->id }})" class="text-green-600 hover:text-green-800">
                                            <span class="material-icons" style="font-size: 18px !important;">assignment_return</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Kuantiti</p>
                                <span class="mobile-data text-gray-900">{{ $item->kuantiti_dipulangkan }}/{{ $item->kuantiti }}</span>
                                @if($item->kuantiti_hilang > 0)
                                    <span class="text-red-600 text-[10px]">(Hilang: {{ $item->kuantiti_hilang }})</span>
                                @endif
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_pergerakan ? \Carbon\Carbon::parse($item->tarikh_pergerakan)->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jenis</p>
                                <span class="mobile-data text-gray-900">{{ $item->jenis_pergerakan }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @if($item->status_pulangan === 'Sudah Pulang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Sudah Pulang</span>
                                @elseif($item->status_pulangan === 'Sebahagian')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-yellow-100 text-yellow-800">Sebahagian</span>
                                @elseif($item->status_pulangan === 'Lewat')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Lewat</span>
                                @elseif($item->status_pulangan === 'Hilang')
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Hilang</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">{{ $item->status_pulangan }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">swap_horiz</span>
                        <p class="text-sm text-gray-500">Tiada pergerakan aset dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($pergerakanAset->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $pergerakanAset->firstItem() }} hingga {{ $pergerakanAset->lastItem() }} daripada {{ $pergerakanAset->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $pergerakanAset->appends(request()->query())->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Modal -->
    <x-delete-modal
        id="deleteModal"
        title="Padam Pergerakan Aset"
        message="Adakah anda pasti ingin memadam rekod pergerakan aset ini?"
        :route="'pergerakan-aset.destroy'"
    />

    <!-- Pulangan Modal -->
    <div id="pulanganModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Rekod Pulangan</h3>
                <button type="button" onclick="closePulanganModal()" class="text-gray-400 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>
            
            <div id="pulanganStats" class="mb-4 p-3 bg-gray-50 rounded-md text-sm">
                <div class="grid grid-cols-2 gap-2">
                    <div><span class="text-gray-500">No. Pergerakan:</span> <span id="statNoPergerakan" class="font-medium">-</span></div>
                    <div><span class="text-gray-500">Aset:</span> <span id="statNamaAset" class="font-medium">-</span></div>
                    <div><span class="text-gray-500">Kuantiti Asal:</span> <span id="statKuantitiAsal" class="font-medium">-</span></div>
                    <div><span class="text-gray-500">Sudah Pulang:</span> <span id="statSudahPulang" class="font-medium">-</span></div>
                    <div class="col-span-2"><span class="text-gray-500">Baki Belum Pulang:</span> <span id="statBakiBelumPulang" class="font-medium text-orange-600">-</span></div>
                </div>
            </div>

            <form id="pulanganForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kuantiti Pulang Kali Ini *</label>
                        <input type="number" name="kuantiti_pulang" id="kuantitiPulang" min="1" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Maksimum: <span id="maxKuantiti">-</span> unit</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Selepas Pulang *</label>
                        <select name="kondisi_selepas" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="Baik">Baik</option>
                            <option value="Sederhana">Sederhana</option>
                            <option value="Teruk">Teruk</option>
                            <option value="Rosak">Rosak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-start">
                        <input type="checkbox" name="selesaikan" id="selesaikan" value="1" class="mt-1 mr-2">
                        <div>
                            <label for="selesaikan" class="text-sm font-medium text-gray-700">Selesaikan pulangan (tutup rekod ini)</label>
                            <p class="text-xs text-red-600">⚠️ Baki yang tidak dipulangkan akan dikira sebagai HILANG dan transaksi ganti rugi akan dicipta</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" onclick="closePulanganModal()" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 rounded-md hover:bg-green-700">
                        Rekod Pulangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('pergerakan-aset') }}/' + id;
            modal.classList.remove('hidden');
        }

        let currentPergerakanId = null;

        function openPulanganModal(id) {
            currentPergerakanId = id;
            const modal = document.getElementById('pulanganModal');
            const form = document.getElementById('pulanganForm');
            form.action = '{{ url('pergerakan-aset') }}/' + id + '/pulang-sebahagian';
            
            // Fetch stats
            fetch('{{ url('pergerakan-aset') }}/' + id + '/return-stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('statNoPergerakan').textContent = data.no_pergerakan;
                    document.getElementById('statNamaAset').textContent = data.nama_aset;
                    document.getElementById('statKuantitiAsal').textContent = data.kuantiti_asal;
                    document.getElementById('statSudahPulang').textContent = data.kuantiti_dipulangkan;
                    document.getElementById('statBakiBelumPulang').textContent = data.baki_belum_pulang;
                    document.getElementById('maxKuantiti').textContent = data.baki_belum_pulang;
                    document.getElementById('kuantitiPulang').max = data.baki_belum_pulang;
                    document.getElementById('kuantitiPulang').value = data.baki_belum_pulang;
                })
                .catch(error => console.error('Error:', error));
            
            modal.classList.remove('hidden');
        }

        function closePulanganModal() {
            document.getElementById('pulanganModal').classList.add('hidden');
            document.getElementById('pulanganForm').reset();
        }
    </script>
</body>
</html>
