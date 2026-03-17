<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Bantuan - E-Masjid</title>
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
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Permohonan Bantuan</h1>
                        <p class="text-xs text-gray-600">Pengurusan permohonan bantuan kebajikan dengan workflow</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('permohonan_bantuan', 'create'))
                            <a href="{{ route('permohonan-bantuan.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Permohonan
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('permohonan-bantuan.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari no permohonan, nama penerima..."
                        />

                        <div class="flex gap-2">
                            <x-filter-dropdown
                                name="status_permohonan"
                                :options="[
                                    'Baharu' => 'Baharu',
                                    'Dalam Semakan' => 'Dalam Semakan',
                                    'Lawatan Rumah' => 'Lawatan Rumah',
                                    'Lulus' => 'Lulus',
                                    'Ditolak' => 'Ditolak',
                                    'Dibatalkan' => 'Dibatalkan'
                                ]"
                                :selected="request('status_permohonan')"
                                placeholder="Semua Status"
                            />
                            <x-filter-dropdown
                                name="keutamaan"
                                :options="[
                                    'Kecemasan' => 'Kecemasan',
                                    'Tinggi' => 'Tinggi',
                                    'Sederhana' => 'Sederhana',
                                    'Biasa' => 'Biasa'
                                ]"
                                :selected="request('keutamaan')"
                                placeholder="Semua Keutamaan"
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
                                onclick="window.location.href='{{ route('permohonan-bantuan.index') }}'"
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
                                <th class="px-4 py-2 table-header">No. Permohonan</th>
                                <th class="px-4 py-2 table-header">Tarikh</th>
                                <th class="px-4 py-2 table-header">Nama Penerima</th>
                                <th class="px-4 py-2 table-header">Program</th>
                                <th class="px-4 py-2 table-header">Jumlah</th>
                                <th class="px-4 py-2 table-header">Keutamaan</th>
                                <th class="px-4 py-2 table-header">Status</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($permohonan as $item)
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->no_permohonan }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->tarikh_permohonan->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 table-data">
                                        <div class="table-data-important text-gray-900">{{ $item->penerimaBantuan->nama_penuh ?? '-' }}</div>
                                        <div class="table-data text-gray-500">{{ $item->penerimaBantuan->no_kp ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-2 table-data text-gray-600">{{ $item->programKebajikan->nama_program ?? '-' }}</td>
                                    <td class="px-4 py-2 table-data text-gray-600">
                                        @if($item->jumlah_dipohon)
                                            RM {{ number_format($item->jumlah_dipohon, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @php
                                            $keutamaanColor = match($item->keutamaan) {
                                                'Kecemasan' => 'bg-red-100 text-red-800',
                                                'Tinggi' => 'bg-orange-100 text-orange-800',
                                                'Sederhana' => 'bg-yellow-100 text-yellow-800',
                                                'Biasa' => 'bg-blue-100 text-blue-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $keutamaanColor }}">{{ $item->keutamaan }}</span>
                                    </td>
                                    <td class="px-4 py-2 table-data">
                                        @php
                                            $statusColor = match($item->status_permohonan) {
                                                'Baharu' => 'bg-blue-100 text-blue-800',
                                                'Dalam Semakan' => 'bg-yellow-100 text-yellow-800',
                                                'Lawatan Rumah' => 'bg-purple-100 text-purple-800',
                                                'Lulus' => 'bg-green-100 text-green-800',
                                                'Ditolak' => 'bg-red-100 text-red-800',
                                                'Dibatalkan' => 'bg-gray-100 text-gray-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $statusColor }}">{{ $item->status_permohonan }}</span>
                                    </td>
                                    <x-action-icons
                                        :record="$item"
                                        :show-route="route('permohonan-bantuan.show', $item)"
                                        :edit-route="in_array($item->status_permohonan, ['Baharu', 'Dalam Semakan']) ? route('permohonan-bantuan.edit', $item) : null"
                                        module="kebajikan"
                                        layout="desktop"
                                    />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">description</span>
                                        <p class="text-sm">Tiada permohonan bantuan dijumpai</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($permohonan as $item)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <!-- Header with Name and Actions -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center mb-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-medium text-blue-600">{{ strtoupper(substr($item->no_permohonan, 0, 1)) }}</span>
                                    </div>
                                    <h3 class="mobile-title text-gray-900">{{ $item->no_permohonan }}</h3>
                                </div>
                                <p class="mobile-subtitle text-gray-500">{{ $item->penerimaBantuan->nama_penuh ?? '-' }}</p>
                            </div>
                            <x-action-icons
                                :record="$item"
                                :show-route="route('permohonan-bantuan.show', $item)"
                                :edit-route="in_array($item->status_permohonan, ['Baharu', 'Dalam Semakan']) ? route('permohonan-bantuan.edit', $item) : null"
                                module="kebajikan"
                                layout="mobile"
                            />
                        </div>

                        <!-- Details -->
                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Tarikh</p>
                                <span class="mobile-data text-gray-900">{{ $item->tarikh_permohonan->format('d/m/Y') }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Jumlah</p>
                                <span class="mobile-data text-gray-900">
                                    @if($item->jumlah_dipohon)
                                        RM {{ number_format($item->jumlah_dipohon, 2) }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Program</p>
                                <span class="mobile-data text-gray-900">{{ $item->programKebajikan->nama_program ?? '-' }}</span>
                            </div>
                            <div>
                                <p class="mobile-label text-gray-500 mb-1">Keutamaan</p>
                                @php
                                    $keutamaanColor = match($item->keutamaan) {
                                        'Kecemasan' => 'bg-red-100 text-red-800',
                                        'Tinggi' => 'bg-orange-100 text-orange-800',
                                        'Sederhana' => 'bg-yellow-100 text-yellow-800',
                                        'Biasa' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $keutamaanColor }}">{{ $item->keutamaan }}</span>
                            </div>
                            <div class="col-span-2">
                                <p class="mobile-label text-gray-500 mb-1">Status</p>
                                @php
                                    $statusColor = match($item->status_permohonan) {
                                        'Baharu' => 'bg-blue-100 text-blue-800',
                                        'Dalam Semakan' => 'bg-yellow-100 text-yellow-800',
                                        'Lawatan Rumah' => 'bg-purple-100 text-purple-800',
                                        'Lulus' => 'bg-green-100 text-green-800',
                                        'Ditolak' => 'bg-red-100 text-red-800',
                                        'Dibatalkan' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $statusColor }}">{{ $item->status_permohonan }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">description</span>
                        <p class="text-sm text-gray-500">Tiada permohonan bantuan dijumpai</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($permohonan->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $permohonan->firstItem() }} hingga {{ $permohonan->lastItem() }} daripada {{ $permohonan->total() }} rekod
                    </div>
                    <div class="flex space-x-1">
                        {{ $permohonan->appends(request()->query())->links('pagination::simple-tailwind') }}
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
        title="Padam Permohonan Bantuan"
        message="Adakah anda pasti ingin memadam permohonan bantuan ini?"
        :route="'permohonan-bantuan.destroy'"
    />

    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = modal.querySelector('form');
            form.action = '{{ url('permohonan-bantuan') }}/' + id;
            modal.classList.remove('hidden');
        }
    </script>
</body>
</html>
