<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadual Imam & Bilal - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Jadual Imam & Bilal</h1>
                        <p class="text-xs text-gray-600">Pengurusan jadual tugas imam dan bilal - {{ $masjid->nama ?? 'E-Masjid' }}</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        @if(auth()->user()->hasPermission('jadual_imam_bilal', 'create'))
                            <a href="{{ route('jadual-imam-bilal.auto-generate') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">autorenew</span>
                                Auto-Generate
                            </a>
                            <a href="{{ route('jadual-imam-bilal.create') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">add</span>
                                Tambah Jadual
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-sm text-xs">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-sm text-xs">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Filters & Search -->
                <form method="GET" action="{{ route('jadual-imam-bilal.index') }}" class="mb-4">
                    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center flex-wrap">
                        <x-search-input
                            name="search"
                            :value="request('search')"
                            placeholder="Cari nama imam, bilal..."
                        />

                        @if($isSuperAdmin ?? false)
                        <select name="masjid_id" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Masjid</option>
                            @foreach($masjidList ?? [] as $m)
                                <option value="{{ $m->id }}" {{ request('masjid_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                        @endif

                        <div class="flex gap-2">
                            <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Tahun</option>
                                @foreach($availableYears ?? [] as $year)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>

                            <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Bulan</option>
                                @php
                                    $bulanList = [
                                        1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April',
                                        5 => 'Mei', 6 => 'Jun', 7 => 'Julai', 8 => 'Ogos',
                                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember'
                                    ];
                                @endphp
                                @foreach($bulanList as $num => $nama)
                                    <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <x-action-button type="submit" icon="search" color="blue">
                                Cari
                            </x-action-button>
                            <x-action-button
                                type="button"
                                icon="refresh"
                                color="red"
                                onclick="window.location.href='{{ route('jadual-imam-bilal.index') }}'"
                            >
                                Reset
                            </x-action-button>
                        </div>
                    </div>
                </form>

                <!-- Desktop Table - Grouped by Month -->
                <div class="hidden md:block overflow-x-auto bg-gray-50 rounded-xs border border-gray-200">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-blue-100 text-gray-600">
                            <tr>
                                <th class="px-4 py-2 table-header">Bulan / Tahun</th>
                                <th class="px-4 py-2 table-header">Nama Masjid</th>
                                <th class="px-4 py-2 table-header text-center">Jumlah Jadual</th>
                                <th class="px-4 py-2 table-header text-center">Dijadual</th>
                                <th class="px-4 py-2 table-header text-center">Auto-Generate</th>
                                <th class="px-4 py-2 table-header text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($jadualGroups as $group)
                                @php
                                    $namaBulan = \Carbon\Carbon::createFromDate($group->tahun, $group->bulan, 1)->translatedFormat('F Y');
                                @endphp
                                <tr class="hover:bg-white">
                                    <td class="px-4 py-3 table-data">
                                        <div class="flex items-center">
                                            <span class="material-icons text-blue-500 mr-3" style="font-size: 24px !important;">calendar_month</span>
                                            <div>
                                                <div class="table-data-important text-gray-900 font-semibold">{{ $namaBulan }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 table-data">
                                        <div class="flex items-center">
                                            <span class="material-icons text-green-600 mr-2" style="font-size: 18px !important;">mosque</span>
                                            <span class="text-gray-700">{{ $masjid->nama ?? 'E-Masjid' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 table-data text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                            {{ $group->jumlah_jadual }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ $group->dijadual }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 table-data text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $group->auto_generate }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 table-data text-center space-x-1">
                                        <!-- Cetak Icon -->
                                        <a href="{{ route('jadual-imam-bilal.export-pdf', ['bulan' => $group->bulan, 'tahun' => $group->tahun]) }}" 
                                           class="text-red-600 hover:text-red-800 action-icon" 
                                           title="Cetak Jadual" target="_blank">
                                            <span class="material-icons text-[8px]">print</span>
                                        </a>
                                        <!-- Lihat Icon -->
                                        <a href="{{ route('jadual-imam-bilal.show-month', ['bulan' => $group->bulan, 'tahun' => $group->tahun]) }}" 
                                           class="text-gray-700 hover:text-gray-900 action-icon" 
                                           title="Lihat Jadual">
                                            <span class="material-icons text-[8px]">visibility</span>
                                        </a>
                                        @if(auth()->user()->hasPermission('jadual_imam_bilal', 'update'))
                                        <!-- Edit Icon -->
                                        <a href="{{ route('jadual-imam-bilal.auto-generate') }}?bulan={{ $group->bulan }}&tahun={{ $group->tahun }}" 
                                           class="text-blue-600 hover:text-blue-800 action-icon" 
                                           title="Edit/Tambah Jadual">
                                            <span class="material-icons text-[8px]">edit</span>
                                        </a>
                                        @endif
                                        @if(auth()->user()->hasPermission('jadual_imam_bilal', 'delete'))
                                        <!-- Delete Icon -->
                                        <button type="button" 
                                                onclick="confirmDeleteMonth({{ $group->bulan }}, {{ $group->tahun }}, '{{ $namaBulan }}')"
                                                class="text-red-600 hover:text-red-800 action-icon" 
                                                title="Padam Semua Jadual Bulan Ini">
                                            <span class="material-icons text-[8px]">delete</span>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        <span class="material-icons mb-2" style="font-size: 48px !important;">event_note</span>
                                        <p class="text-sm">Tiada jadual dijumpai</p>
                                        <p class="text-xs text-gray-400 mt-2">Klik "Auto-Generate" untuk menjana jadual baru</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden space-y-3">
                    @forelse($jadualGroups as $group)
                        @php
                            $namaBulan = \Carbon\Carbon::createFromDate($group->tahun, $group->bulan, 1)->translatedFormat('F Y');
                        @endphp
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <span class="material-icons text-blue-500 mr-2" style="font-size: 24px !important;">calendar_month</span>
                                    <div>
                                        <h3 class="mobile-title text-gray-900 font-semibold">{{ $namaBulan }}</h3>
                                        <p class="mobile-subtitle text-gray-500">{{ $masjid->nama ?? 'E-Masjid' }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                    {{ $group->jumlah_jadual }} jadual
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                                <div class="text-center p-2 bg-orange-50 rounded">
                                    <p class="text-orange-600 font-bold">{{ $group->dijadual }}</p>
                                    <p class="text-gray-500">Dijadual</p>
                                </div>
                                <div class="text-center p-2 bg-purple-50 rounded">
                                    <p class="text-purple-600 font-bold">{{ $group->auto_generate }}</p>
                                    <p class="text-gray-500">Auto</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-end space-x-2">
                                <!-- Cetak Icon -->
                                <a href="{{ route('jadual-imam-bilal.export-pdf', ['bulan' => $group->bulan, 'tahun' => $group->tahun]) }}" 
                                   class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" 
                                   title="Cetak" target="_blank">
                                    <span class="material-icons text-sm">print</span>
                                </a>
                                <!-- Lihat Icon -->
                                <a href="{{ route('jadual-imam-bilal.show-month', ['bulan' => $group->bulan, 'tahun' => $group->tahun]) }}" 
                                   class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" 
                                   title="Lihat">
                                    <span class="material-icons text-sm">visibility</span>
                                </a>
                                @if(auth()->user()->hasPermission('jadual_imam_bilal', 'update'))
                                <!-- Edit Icon -->
                                <a href="{{ route('jadual-imam-bilal.auto-generate') }}?bulan={{ $group->bulan }}&tahun={{ $group->tahun }}" 
                                   class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" 
                                   title="Edit">
                                    <span class="material-icons text-sm">edit</span>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('jadual_imam_bilal', 'delete'))
                                <!-- Delete Icon -->
                                <button type="button" 
                                        onclick="confirmDeleteMonth({{ $group->bulan }}, {{ $group->tahun }}, '{{ $namaBulan }}')"
                                        class="p-2 text-red-600 hover:text-red-800 rounded-full hover:bg-red-50" 
                                        title="Padam">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">event_note</span>
                            <p class="text-sm text-gray-500">Tiada jadual dijumpai</p>
                            <p class="text-xs text-gray-400 mt-2">Klik "Auto-Generate" untuk menjana jadual baru</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($jadualGroups->hasPages())
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Menunjukkan {{ $jadualGroups->firstItem() }} hingga {{ $jadualGroups->lastItem() }} daripada {{ $jadualGroups->total() }} bulan
                    </div>
                    <div class="flex space-x-1">
                        {{ $jadualGroups->links('pagination::simple-tailwind') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </main>

    <x-footer />

    <!-- Delete Month Form -->
    <form id="deleteMonthForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
            <div class="text-center">
                <span class="material-icons text-red-500 mb-4" style="font-size: 48px !important;">warning</span>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Padam Jadual?</h3>
                <p class="text-sm text-gray-600 mb-4" id="deleteMessage">Adakah anda pasti mahu memadam semua jadual untuk bulan ini?</p>
                <div class="flex space-x-3 justify-center">
                    <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-700 text-xs rounded hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="button" onclick="executeDelete()" class="px-4 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                        Ya, Padam
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteUrl = '';
        
        function confirmDeleteMonth(bulan, tahun, namaBulan) {
            deleteUrl = '{{ url("jadual-imam-bilal/delete-month") }}/' + bulan + '/' + tahun;
            document.getElementById('deleteMessage').textContent = 'Adakah anda pasti mahu memadam semua jadual untuk ' + namaBulan + '?';
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
        
        function executeDelete() {
            const form = document.getElementById('deleteMonthForm');
            form.action = deleteUrl;
            form.submit();
        }
    </script>
</body>
</html>
