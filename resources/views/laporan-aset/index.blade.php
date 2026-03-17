<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aset - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col" style="font-family: 'Poppins', sans-serif;">
    <x-double-navbar :user="auth()->user()" />

    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Aset</h1>
                    <p class="text-xs text-gray-600">Statistik dan laporan aset masjid</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('laporan-aset.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-3">
                        @if(auth()->user()->hasRole('Super Admin'))
                        <select name="masjid_id" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Pilih Masjid</option>
                            @foreach($masjids as $masjid)
                                <option value="{{ $masjid->id }}" {{ $masjidId == $masjid->id ? 'selected' : '' }}>
                                    {{ $masjid->nama }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                        
                        <select name="kategori_id" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>

                        <select name="status_aset" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status_aset') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Disewa" {{ request('status_aset') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                            <option value="Dipinjam" {{ request('status_aset') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Rosak" {{ request('status_aset') == 'Rosak' ? 'selected' : '' }}>Rosak</option>
                            <option value="Hilang" {{ request('status_aset') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        </select>

                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                            Cari
                        </button>
                        
                        <a href="{{ route('laporan-aset.index') }}" class="inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @if($tabPermissions['dashboard'])
                        <button onclick="switchTab('dashboard')" id="tab-dashboard" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Dashboard Aset
                        </button>
                        @endif
                        
                        @if($tabPermissions['inventori'])
                        <button onclick="switchTab('inventori')" id="tab-inventori" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Inventori
                        </button>
                        @endif
                        
                        @if($tabPermissions['lokasi'])
                        <button onclick="switchTab('lokasi')" id="tab-lokasi" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Lokasi
                        </button>
                        @endif
                        
                        @if($tabPermissions['penyelenggaraan'])
                        <button onclick="switchTab('penyelenggaraan')" id="tab-penyelenggaraan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Penyelenggaraan
                        </button>
                        @endif
                        
                        @if($tabPermissions['pergerakan'])
                        <button onclick="switchTab('pergerakan')" id="tab-pergerakan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Pergerakan
                        </button>
                        @endif
                        
                        @if($tabPermissions['pemindahan'])
                        <button onclick="switchTab('pemindahan')" id="tab-pemindahan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Pemindahan
                        </button>
                        @endif
                    </nav>
                </div>

                <!-- Tab 1: Dashboard Aset -->
                @if($tabPermissions['dashboard'])
                <div id="content-dashboard" class="tab-content">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 mb-1">Jumlah Aset</p>
                                    <p class="text-xl font-bold text-blue-900">{{ number_format($totalAset) }}</p>
                                </div>
                                <span class="material-icons text-blue-600" style="font-size: 32px !important;">inventory_2</span>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 mb-1">Aset Aktif</p>
                                    <p class="text-xl font-bold text-green-900">{{ number_format($asetAktif) }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-yellow-600 mb-1">Dipinjam/Disewa</p>
                                    <p class="text-xl font-bold text-yellow-900">{{ number_format($asetDipinjam + $asetDisewa) }}</p>
                                </div>
                                <span class="material-icons text-yellow-600" style="font-size: 32px !important;">swap_horiz</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-red-600 mb-1">Rosak/Hilang</p>
                                    <p class="text-xl font-bold text-red-900">{{ number_format($asetRosak + $asetHilang) }}</p>
                                </div>
                                <span class="material-icons text-red-600" style="font-size: 32px !important;">warning</span>
                            </div>
                        </div>
                    </div>

                    <!-- Value & Pergerakan Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">account_balance_wallet</span>
                                Nilai Aset
                            </h3>
                            <div class="text-center py-4">
                                <p class="text-3xl font-bold text-blue-900">RM {{ number_format($totalNilaiAset, 2) }}</p>
                                <p class="text-xs text-gray-500 mt-2">Jumlah nilai perolehan aset aktif</p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">swap_horiz</span>
                                Status Pergerakan
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="text-center p-2 bg-gray-50 rounded">
                                    <p class="text-lg font-bold text-gray-900">{{ $totalPergerakan }}</p>
                                    <p class="text-xs text-gray-500">Jumlah</p>
                                </div>
                                <div class="text-center p-2 bg-yellow-50 rounded">
                                    <p class="text-lg font-bold text-yellow-700">{{ $pergerakanBelumPulang }}</p>
                                    <p class="text-xs text-gray-500">Belum Pulang</p>
                                </div>
                                <div class="text-center p-2 bg-orange-50 rounded">
                                    <p class="text-lg font-bold text-orange-700">{{ $pergerakanLewat }}</p>
                                    <p class="text-xs text-gray-500">Lewat</p>
                                </div>
                                <div class="text-center p-2 bg-red-50 rounded">
                                    <p class="text-lg font-bold text-red-700">{{ $pergerakanHilang }}</p>
                                    <p class="text-xs text-gray-500">Hilang</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart: Aset by Kategori -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">pie_chart</span>
                            Aset Mengikut Kategori
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="kategoriChart"></canvas>
                        </div>
                    </div>
                </div>
                @endif


                <!-- Tab 2: Laporan Inventori -->
                @if($tabPermissions['inventori'])
                <div id="content-inventori" class="tab-content">
                    <!-- Summary by Status -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                        @foreach($inventoriSummary as $summary)
                        <div class="bg-gray-50 border border-gray-200 rounded p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1">{{ $summary->status_aset }}</p>
                            <p class="text-lg font-bold text-gray-900">{{ $summary->total }}</p>
                            <p class="text-xs text-gray-500">RM {{ number_format($summary->nilai ?? 0, 2) }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Inventori Table -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Senarai Inventori Aset</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">No. Siri</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Nama Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kuantiti</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Lokasi</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kondisi</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Nilai (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($inventoriAset as $aset)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->no_siri ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->nama_aset }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->kategoriAset->nama_kategori ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->kuantiti ?? 1 }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->lokasi_semasa ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($aset->status_aset == 'Aktif') bg-green-100 text-green-800
                                                @elseif($aset->status_aset == 'Disewa') bg-blue-100 text-blue-800
                                                @elseif($aset->status_aset == 'Dipinjam') bg-yellow-100 text-yellow-800
                                                @elseif($aset->status_aset == 'Rosak') bg-orange-100 text-orange-800
                                                @elseif($aset->status_aset == 'Hilang') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $aset->status_aset }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($aset->kondisi_aset == 'Baik') bg-green-100 text-green-800
                                                @elseif($aset->kondisi_aset == 'Sederhana') bg-yellow-100 text-yellow-800
                                                @elseif($aset->kondisi_aset == 'Teruk') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $aset->kondisi_aset ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($aset->harga_perolehan ?? 0, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">inventory_2</span>
                                            <p class="text-sm">Tiada aset dijumpai</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 3: Laporan Lokasi -->
                @if($tabPermissions['lokasi'])
                <div id="content-lokasi" class="tab-content">
                    <!-- Location Summary -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                        @foreach($lokasiAset->take(8) as $lokasi)
                        <div class="bg-gray-50 border border-gray-200 rounded p-3 text-center">
                            <p class="text-xs text-gray-600 mb-1 truncate" title="{{ $lokasi->lokasi_semasa ?? 'Tidak Dinyatakan' }}">
                                {{ $lokasi->lokasi_semasa ?? 'Tidak Dinyatakan' }}
                            </p>
                            <p class="text-lg font-bold text-gray-900">{{ $lokasi->total }}</p>
                            <p class="text-xs text-gray-500">aset</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Aset by Location -->
                    @foreach($asetByLokasi as $lokasi => $asets)
                    <div class="bg-white border border-gray-200 rounded overflow-hidden mb-4">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">location_on</span>
                                {{ $lokasi ?? 'Tidak Dinyatakan' }}
                            </h3>
                            <span class="text-xs text-gray-500">{{ $asets->count() }} aset</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Nama Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kuantiti</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($asets as $aset)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->nama_aset }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->kategoriAset->nama_kategori ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->kuantiti ?? 1 }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($aset->status_aset == 'Aktif') bg-green-100 text-green-800
                                                @elseif($aset->status_aset == 'Disewa') bg-blue-100 text-blue-800
                                                @elseif($aset->status_aset == 'Dipinjam') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $aset->status_aset }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($aset->kondisi_aset == 'Baik') bg-green-100 text-green-800
                                                @elseif($aset->kondisi_aset == 'Sederhana') bg-yellow-100 text-yellow-800
                                                @elseif($aset->kondisi_aset == 'Teruk') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $aset->kondisi_aset ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    @if($asetByLokasi->isEmpty())
                    <div class="bg-white border border-gray-200 rounded p-8 text-center">
                        <span class="material-icons mb-2 text-gray-400" style="font-size: 48px !important;">location_off</span>
                        <p class="text-sm text-gray-500">Tiada data lokasi aset</p>
                    </div>
                    @endif
                </div>
                @endif


                <!-- Tab 4: Laporan Penyelenggaraan -->
                @if($tabPermissions['penyelenggaraan'])
                <div id="content-penyelenggaraan" class="tab-content">
                    <!-- Aset Perlu Penyelenggaraan -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden mb-6">
                        <div class="px-4 py-3 bg-orange-50 border-b border-orange-200">
                            <h3 class="text-sm font-semibold text-orange-900 flex items-center">
                                <span class="material-icons mr-2" style="font-size: 18px !important;">build</span>
                                Aset Perlu Penyelenggaraan ({{ $asetPerluPenyelenggaraan->count() }})
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Nama Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Lokasi</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Kondisi</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($asetPerluPenyelenggaraan as $aset)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $aset->nama_aset }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->kategoriAset->nama_kategori ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->lokasi_semasa ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($aset->kondisi_aset == 'Sederhana') bg-yellow-100 text-yellow-800
                                                @elseif($aset->kondisi_aset == 'Teruk') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $aset->kondisi_aset }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $aset->catatan ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2 text-green-500" style="font-size: 48px !important;">check_circle</span>
                                            <p class="text-sm">Semua aset dalam keadaan baik</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sejarah Penyelenggaraan -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">history</span>
                                Sejarah Penyelenggaraan
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tujuan</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($pergerakanPenyelenggaraan as $pergerakan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ \Carbon\Carbon::parse($pergerakan->tarikh_pergerakan)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $pergerakan->senariAset->nama_aset ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800">
                                                {{ $pergerakan->jenis_pergerakan }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $pergerakan->tujuan ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($pergerakan->status_pulangan == 'Sudah Pulang') bg-green-100 text-green-800
                                                @elseif($pergerakan->status_pulangan == 'Belum Pulang') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $pergerakan->status_pulangan }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">build</span>
                                            <p class="text-sm">Tiada rekod penyelenggaraan</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 5: Laporan Pergerakan (Pinjaman/Sewa) -->
                @if($tabPermissions['pergerakan'])
                <div id="content-pergerakan" class="tab-content">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @php
                            $totalPinjaman = $pergerakanByJenis->where('jenis_pergerakan', 'Pinjaman')->first()->total ?? 0;
                            $totalSewa = $pergerakanByJenis->where('jenis_pergerakan', 'Sewa')->first()->total ?? 0;
                            $belumPulang = $pergerakanByStatus->where('status_pulangan', 'Belum Pulang')->first()->total ?? 0;
                            $sudahPulang = $pergerakanByStatus->where('status_pulangan', 'Sudah Pulang')->first()->total ?? 0;
                        @endphp
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 mb-1">Pinjaman</p>
                                    <p class="text-xl font-bold text-blue-900">{{ number_format($totalPinjaman) }}</p>
                                </div>
                                <span class="material-icons text-blue-600" style="font-size: 32px !important;">handshake</span>
                            </div>
                        </div>
                        <div class="bg-purple-50 border border-purple-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-purple-600 mb-1">Sewa</p>
                                    <p class="text-xl font-bold text-purple-900">{{ number_format($totalSewa) }}</p>
                                </div>
                                <span class="material-icons text-purple-600" style="font-size: 32px !important;">payments</span>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-yellow-600 mb-1">Belum Pulang</p>
                                    <p class="text-xl font-bold text-yellow-900">{{ number_format($belumPulang) }}</p>
                                </div>
                                <span class="material-icons text-yellow-600" style="font-size: 32px !important;">pending</span>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 mb-1">Sudah Pulang</p>
                                    <p class="text-xl font-bold text-green-900">{{ number_format($sudahPulang) }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px !important;">check_circle</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pergerakan Table -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">swap_horiz</span>
                                Senarai Pergerakan Aset (Pinjaman/Sewa)
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Peminjam/Penyewa</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh Jangka Pulang</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($pergerakanPinjaman as $pergerakan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ \Carbon\Carbon::parse($pergerakan->tarikh_pergerakan)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $pergerakan->senariAset->nama_aset ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($pergerakan->jenis_pergerakan == 'Pinjaman') bg-blue-100 text-blue-800
                                                @elseif($pergerakan->jenis_pergerakan == 'Sewa') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $pergerakan->jenis_pergerakan }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $pergerakan->nama_peminjam ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">
                                            {{ $pergerakan->tarikh_jangka_pulang ? \Carbon\Carbon::parse($pergerakan->tarikh_jangka_pulang)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($pergerakan->status_pulangan == 'Sudah Pulang') bg-green-100 text-green-800
                                                @elseif($pergerakan->status_pulangan == 'Belum Pulang') bg-yellow-100 text-yellow-800
                                                @elseif($pergerakan->status_pulangan == 'Lewat') bg-orange-100 text-orange-800
                                                @elseif($pergerakan->status_pulangan == 'Hilang') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $pergerakan->status_pulangan ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">swap_horiz</span>
                                            <p class="text-sm">Tiada rekod pergerakan pinjaman/sewa</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 6: Laporan Pemindahan -->
                @if($tabPermissions['pemindahan'])
                <div id="content-pemindahan" class="tab-content">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50 border border-indigo-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-indigo-600 mb-1">Jumlah Pemindahan</p>
                                    <p class="text-xl font-bold text-indigo-900">{{ number_format($totalPemindahan) }}</p>
                                </div>
                                <span class="material-icons text-indigo-600" style="font-size: 32px !important;">local_shipping</span>
                            </div>
                        </div>
                        <div class="bg-cyan-50 border border-cyan-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-cyan-600 mb-1">Pemindahan Dalaman</p>
                                    <p class="text-xl font-bold text-cyan-900">{{ number_format($pemindahanDalaman) }}</p>
                                </div>
                                <span class="material-icons text-cyan-600" style="font-size: 32px !important;">home</span>
                            </div>
                        </div>
                        <div class="bg-teal-50 border border-teal-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-teal-600 mb-1">Pemindahan Luaran</p>
                                    <p class="text-xl font-bold text-teal-900">{{ number_format($pemindahanLuaran) }}</p>
                                </div>
                                <span class="material-icons text-teal-600" style="font-size: 32px !important;">domain</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pemindahan Table -->
                    <div class="bg-white border border-gray-200 rounded overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                                <span class="material-icons mr-2 text-indigo-600" style="font-size: 18px !important;">local_shipping</span>
                                Senarai Pemindahan Aset
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tarikh</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Aset</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Lokasi Asal</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Lokasi Baru</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Tujuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($pemindahanAset as $pemindahan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ \Carbon\Carbon::parse($pemindahan->tarikh_pergerakan)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $pemindahan->senariAset->nama_aset ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($pemindahan->jenis_pergerakan == 'Pemindahan Dalaman') bg-cyan-100 text-cyan-800
                                                @elseif($pemindahan->jenis_pergerakan == 'Pemindahan Luaran') bg-teal-100 text-teal-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $pemindahan->jenis_pergerakan }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $pemindahan->lokasi_asal ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $pemindahan->lokasi_baru ?? '-' }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $pemindahan->tujuan ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">local_shipping</span>
                                            <p class="text-sm">Tiada rekod pemindahan aset</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </main>

    <x-footer />

    <script>
        // Tab switching
        function switchTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remove active state from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            const content = document.getElementById('content-' + tabName);
            if (content) {
                content.classList.add('active');
            }
            
            // Add active state to selected tab button
            const button = document.getElementById('tab-' + tabName);
            if (button) {
                button.classList.remove('border-transparent', 'text-gray-500');
                button.classList.add('border-blue-500', 'text-blue-600');
            }
        }

        // Initialize tab from URL parameter or first available
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL parameter for tab
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            
            if (tabParam && document.getElementById('tab-' + tabParam)) {
                switchTab(tabParam);
            } else {
                const firstTab = document.querySelector('.tab-button');
                if (firstTab) {
                    const tabId = firstTab.id.replace('tab-', '');
                    switchTab(tabId);
                }
            }

            // Initialize Kategori Chart
            const kategoriCtx = document.getElementById('kategoriChart');
            if (kategoriCtx) {
                const kategoriData = @json($asetByKategori);
                new Chart(kategoriCtx, {
                    type: 'doughnut',
                    data: {
                        labels: kategoriData.map(item => item.nama_kategori),
                        datasets: [{
                            data: kategoriData.map(item => item.total),
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                                '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: { size: 11, family: 'Poppins' },
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
