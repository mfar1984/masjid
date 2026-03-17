<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kewangan - E-Masjid</title>
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
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Kewangan</h1>
                    <p class="text-xs text-gray-600">Statistik dan laporan kewangan masjid</p>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('laporan-kewangan.index') }}" class="mb-6">
                    <div class="flex flex-wrap gap-3">
                        @if($isSuperAdmin)
                        <select name="masjid_id" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Pilih Masjid</option>
                            @foreach($masjids as $masjid)
                                <option value="{{ $masjid->id }}" {{ $masjidId == $masjid->id ? 'selected' : '' }}>
                                    {{ $masjid->nama }}
                                </option>
                            @endforeach
                        </select>
                        @endif
                        
                        <input type="date" name="tarikh_dari" value="{{ request('tarikh_dari') }}" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Dari">
                        <input type="date" name="tarikh_hingga" value="{{ request('tarikh_hingga') }}" class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded text-xs" placeholder="Tarikh Hingga">
                        
                        <select name="akaun_bank_id" class="flex-1 min-w-[200px] px-3 py-2 border border-gray-300 rounded text-xs">
                            <option value="">Semua Akaun Bank</option>
                            @foreach($akaunBank as $akaun)
                                <option value="{{ $akaun->id }}" {{ request('akaun_bank_id') == $akaun->id ? 'selected' : '' }}>
                                    {{ $akaun->nama_bank }} - {{ $akaun->no_akaun }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                            Cari
                        </button>
                        
                        <a href="{{ route('laporan-kewangan.index') }}" class="inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                            Reset
                        </a>
                    </div>
                </form>

                <!-- Tabs -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        @if($tabPermissions['penyata'])
                        <button onclick="switchTab('penyata')" id="tab-penyata" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Penyata Kewangan
                        </button>
                        @endif
                        
                        @if($tabPermissions['pendapatan'])
                        <button onclick="switchTab('pendapatan')" id="tab-pendapatan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Pendapatan
                        </button>
                        @endif
                        
                        @if($tabPermissions['perbelanjaan'])
                        <button onclick="switchTab('perbelanjaan')" id="tab-perbelanjaan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Perbelanjaan
                        </button>
                        @endif
                        
                        @if($tabPermissions['aliran_tunai'])
                        <button onclick="switchTab('aliran-tunai')" id="tab-aliran-tunai" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Aliran Tunai
                        </button>
                        @endif
                        
                        @if($tabPermissions['imbangan_duga'])
                        <button onclick="switchTab('imbangan-duga')" id="tab-imbangan-duga" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Penyata P&P
                        </button>
                        @endif
                        
                        @if($tabPermissions['perbandingan'])
                        <button onclick="switchTab('perbandingan')" id="tab-perbandingan" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Perbandingan Bulanan
                        </button>
                        @endif
                        
                        @if($tabPermissions['kategori'])
                        <button onclick="switchTab('kategori')" id="tab-kategori" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Laporan Mengikut Kategori
                        </button>
                        @endif
                        
                        @if($tabPermissions['baki_bank'])
                        <button onclick="switchTab('baki-bank')" id="tab-baki-bank" class="tab-button px-4 py-2 text-xs font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Baki Bank
                        </button>
                        @endif
                    </nav>
                </div>

                <!-- Tab 1: Penyata Kewangan -->
                @if($tabPermissions['penyata'])
                <div id="content-penyata" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-50 border border-gray-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Baki Awal</p>
                                    <p class="text-xl font-bold text-gray-900">RM {{ number_format($stats['baki_awal'], 2) }}</p>
                                </div>
                                <span class="material-icons text-gray-600" style="font-size: 32px !important;">account_balance_wallet</span>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 mb-1">Jumlah Pendapatan</p>
                                    <p class="text-xl font-bold text-green-900">RM {{ number_format($stats['total_pendapatan'], 2) }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px !important;">arrow_downward</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-red-600 mb-1">Jumlah Perbelanjaan</p>
                                    <p class="text-xl font-bold text-red-900">RM {{ number_format($stats['total_perbelanjaan'], 2) }}</p>
                                </div>
                                <span class="material-icons text-red-600" style="font-size: 32px !important;">arrow_upward</span>
                            </div>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 mb-1">Baki Bersih</p>
                                    <p class="text-xl font-bold {{ $stats['baki_bersih'] >= 0 ? 'text-blue-900' : 'text-red-900' }}">
                                        RM {{ number_format($stats['baki_bersih'], 2) }}
                                    </p>
                                </div>
                                <span class="material-icons text-blue-600" style="font-size: 32px !important;">account_balance</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Ringkasan Penyata</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Perkara</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr class="bg-gray-50">
                                        <td class="px-4 py-2 text-xs font-semibold text-gray-700">BAKI AWAL</td>
                                        <td class="px-4 py-2 text-xs font-semibold text-gray-700 text-right">{{ number_format($stats['baki_awal'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 text-xs font-semibold text-green-700">PENDAPATAN</td>
                                        <td class="px-4 py-2 text-xs font-semibold text-green-700 text-right">{{ number_format($stats['total_pendapatan'], 2) }}</td>
                                    </tr>
                                    @foreach($pendapatanByKategori as $kategori => $jumlah)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-600 pl-8">{{ $kategori }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600 text-right">{{ number_format($jumlah, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="px-4 py-2 text-xs font-semibold text-red-700">PERBELANJAAN</td>
                                        <td class="px-4 py-2 text-xs font-semibold text-red-700 text-right">{{ number_format($stats['total_perbelanjaan'], 2) }}</td>
                                    </tr>
                                    @foreach($perbelanjaanByKategori as $kategori => $jumlah)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-600 pl-8">{{ $kategori }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600 text-right">{{ number_format($jumlah, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="bg-blue-50">
                                        <td class="px-4 py-2 text-xs font-bold text-blue-900">BAKI BERSIH (Baki Awal + Pendapatan - Perbelanjaan)</td>
                                        <td class="px-4 py-2 text-xs font-bold {{ $stats['baki_bersih'] >= 0 ? 'text-blue-900' : 'text-red-900' }} text-right">
                                            {{ number_format($stats['baki_bersih'], 2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 2: Laporan Pendapatan -->
                @if($tabPermissions['pendapatan'])
                <div id="content-pendapatan" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">pie_chart</span>
                                Pendapatan Mengikut Kategori
                            </h3>
                            <div class="flex items-center justify-center" style="height: 300px;">
                                <canvas id="pendapatanChart"></canvas>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Senarai Pendapatan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($pendapatanByKategori as $kategori => $jumlah)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-gray-900">{{ $kategori }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($jumlah, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-green-50">
                                            <td class="px-4 py-2 text-xs font-bold text-green-900">JUMLAH</td>
                                            <td class="px-4 py-2 text-xs font-bold text-green-900 text-right">{{ number_format($stats['total_pendapatan'], 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 3: Laporan Perbelanjaan -->
                @if($tabPermissions['perbelanjaan'])
                <div id="content-perbelanjaan" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-red-600" style="font-size: 18px !important;">pie_chart</span>
                                Perbelanjaan Mengikut Kategori
                            </h3>
                            <div class="flex items-center justify-center" style="height: 300px;">
                                <canvas id="perbelanjaanChart"></canvas>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4">Senarai Perbelanjaan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-red-100">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($perbelanjaanByKategori as $kategori => $jumlah)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-gray-900">{{ $kategori }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($jumlah, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="bg-red-50">
                                            <td class="px-4 py-2 text-xs font-bold text-red-900">JUMLAH</td>
                                            <td class="px-4 py-2 text-xs font-bold text-red-900 text-right">{{ number_format($stats['total_perbelanjaan'], 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 4: Aliran Tunai -->
                @if($tabPermissions['aliran_tunai'])
                <div id="content-aliran-tunai" class="tab-content">
                    <div class="bg-white border border-gray-200 rounded p-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">show_chart</span>
                            Trend Aliran Tunai Bulanan
                        </h3>
                        <div class="flex items-center justify-center" style="height: 400px;">
                            <canvas id="aliranTunaiChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Ringkasan Bulanan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Bulan</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Pendapatan (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Perbelanjaan (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Baki (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($aliranTunaiBulanan as $data)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $data['bulan'] }}</td>
                                        <td class="px-4 py-2 text-xs text-green-700 text-right">{{ number_format($data['pendapatan'], 2) }}</td>
                                        <td class="px-4 py-2 text-xs text-red-700 text-right">{{ number_format($data['perbelanjaan'], 2) }}</td>
                                        <td class="px-4 py-2 text-xs {{ $data['baki'] >= 0 ? 'text-blue-700' : 'text-red-700' }} text-right font-medium">
                                            {{ number_format($data['baki'], 2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 5: Penyata Pendapatan & Perbelanjaan -->
                @if($tabPermissions['imbangan_duga'])
                <div id="content-imbangan-duga" class="tab-content">
                    <div class="bg-white border border-gray-200 rounded p-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">account_balance</span>
                            Penyata Pendapatan & Perbelanjaan (Income & Expenditure Statement)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-purple-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Butiran</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @if(count($imbanganDuga) > 0)
                                        <!-- BAHAGIAN A: PENDAPATAN -->
                                        <tr class="bg-green-50">
                                            <td colspan="2" class="px-4 py-2 text-xs font-bold text-green-900">A. PENDAPATAN</td>
                                        </tr>
                                        @php $hasPendapatan = false; @endphp
                                        @foreach($imbanganDuga as $item)
                                            @if($item['jenis'] == 'Pendapatan')
                                                @php $hasPendapatan = true; @endphp
                                                <tr>
                                                    <td class="px-4 py-2 text-xs text-gray-900 pl-8">{{ $item['kategori'] }}</td>
                                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($item['jumlah'], 2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if(!$hasPendapatan)
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-xs text-gray-500 text-center italic">Tiada pendapatan</td>
                                        </tr>
                                        @endif
                                        <tr class="bg-green-100">
                                            <td class="px-4 py-2 text-xs font-bold text-green-900">JUMLAH PENDAPATAN</td>
                                            <td class="px-4 py-2 text-xs font-bold text-green-900 text-right">{{ number_format($totalKredit, 2) }}</td>
                                        </tr>
                                        
                                        <!-- BAHAGIAN B: PERBELANJAAN -->
                                        <tr class="bg-red-50">
                                            <td colspan="2" class="px-4 py-2 text-xs font-bold text-red-900">B. PERBELANJAAN</td>
                                        </tr>
                                        @php $hasPerbelanjaan = false; @endphp
                                        @foreach($imbanganDuga as $item)
                                            @if($item['jenis'] == 'Perbelanjaan')
                                                @php $hasPerbelanjaan = true; @endphp
                                                <tr>
                                                    <td class="px-4 py-2 text-xs text-gray-900 pl-8">{{ $item['kategori'] }}</td>
                                                    <td class="px-4 py-2 text-xs text-gray-900 text-right">{{ number_format($item['jumlah'], 2) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if(!$hasPerbelanjaan)
                                        <tr>
                                            <td colspan="2" class="px-4 py-2 text-xs text-gray-500 text-center italic">Tiada perbelanjaan</td>
                                        </tr>
                                        @endif
                                        <tr class="bg-red-100">
                                            <td class="px-4 py-2 text-xs font-bold text-red-900">JUMLAH PERBELANJAAN</td>
                                            <td class="px-4 py-2 text-xs font-bold text-red-900 text-right">{{ number_format($totalDebit, 2) }}</td>
                                        </tr>
                                        
                                        <!-- LEBIHAN/KURANGAN -->
                                        <tr class="bg-purple-100">
                                            <td class="px-4 py-2 text-xs font-bold text-purple-900">
                                                {{ $lebihan >= 0 ? 'LEBIHAN (SURPLUS)' : 'KURANGAN (DEFICIT)' }}
                                            </td>
                                            <td class="px-4 py-2 text-xs font-bold {{ $lebihan >= 0 ? 'text-green-900' : 'text-red-900' }} text-right">
                                                {{ number_format(abs($lebihan), 2) }}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2" class="px-4 py-8 text-center text-gray-500">
                                                <span class="material-icons mb-2" style="font-size: 48px !important;">account_balance</span>
                                                <p class="text-sm">Tiada data penyata</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    @if(count($imbanganDuga) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-green-50 border border-green-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 mb-1">Jumlah Pendapatan</p>
                                    <p class="text-xl font-bold text-green-900">RM {{ number_format($totalKredit, 2) }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px !important;">arrow_downward</span>
                            </div>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-red-600 mb-1">Jumlah Perbelanjaan</p>
                                    <p class="text-xl font-bold text-red-900">RM {{ number_format($totalDebit, 2) }}</p>
                                </div>
                                <span class="material-icons text-red-600" style="font-size: 32px !important;">arrow_upward</span>
                            </div>
                        </div>
                        <div class="bg-{{ $lebihan >= 0 ? 'blue' : 'orange' }}-50 border border-{{ $lebihan >= 0 ? 'blue' : 'orange' }}-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-{{ $lebihan >= 0 ? 'blue' : 'orange' }}-600 mb-1">{{ $lebihan >= 0 ? 'Lebihan' : 'Kurangan' }}</p>
                                    <p class="text-xl font-bold text-{{ $lebihan >= 0 ? 'blue' : 'orange' }}-900">RM {{ number_format(abs($lebihan), 2) }}</p>
                                </div>
                                <span class="material-icons text-{{ $lebihan >= 0 ? 'blue' : 'orange' }}-600" style="font-size: 32px !important;">{{ $lebihan >= 0 ? 'trending_up' : 'trending_down' }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Tab 6: Perbandingan Bulanan -->
                @if($tabPermissions['perbandingan'])
                <div id="content-perbandingan" class="tab-content">
                    <div class="bg-white border border-gray-200 rounded p-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">compare_arrows</span>
                            Perbandingan Bulanan
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-orange-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Bulan</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Pendapatan (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Perbelanjaan (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Baki (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">% Perbelanjaan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($perbandinganBulanan as $data)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-900 font-medium">{{ $data['bulan'] }}</td>
                                        <td class="px-4 py-2 text-xs text-green-700 text-right">{{ number_format($data['pendapatan'], 2) }}</td>
                                        <td class="px-4 py-2 text-xs text-red-700 text-right">{{ number_format($data['perbelanjaan'], 2) }}</td>
                                        <td class="px-4 py-2 text-xs {{ $data['baki'] >= 0 ? 'text-blue-700' : 'text-red-700' }} text-right font-medium">
                                            {{ number_format($data['baki'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-xs text-gray-900 text-right">
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium {{ $data['peratus_perbelanjaan'] > 100 ? 'bg-red-100 text-red-800' : ($data['peratus_perbelanjaan'] > 80 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                                {{ number_format($data['peratus_perbelanjaan'], 1) }}%
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">compare_arrows</span>
                                            <p class="text-sm">Tiada data perbandingan</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 7: Laporan Mengikut Kategori -->
                @if($tabPermissions['kategori'])
                <div id="content-kategori" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Top 5 Pendapatan -->
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">trending_up</span>
                                Top 5 Pendapatan Mengikut Kategori
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-green-100">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Bil.</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($topPendapatan as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-gray-900">{{ $item['kategori'] }}</td>
                                            <td class="px-4 py-2 text-xs text-green-700 text-right font-medium">{{ number_format($item['total'], 2) }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-600 text-right">{{ $item['bilangan'] }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-900 text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">
                                                    {{ number_format($item['peratus'], 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                <span class="material-icons mb-2" style="font-size: 48px !important;">trending_up</span>
                                                <p class="text-sm">Tiada data pendapatan</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Top 5 Perbelanjaan -->
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <span class="material-icons mr-2 text-red-600" style="font-size: 18px !important;">trending_down</span>
                                Top 5 Perbelanjaan Mengikut Kategori
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-left text-sm">
                                    <thead class="bg-red-100">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700">Kategori</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Jumlah (RM)</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Bil.</th>
                                            <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">%</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse($topPerbelanjaan as $item)
                                        <tr>
                                            <td class="px-4 py-2 text-xs text-gray-900">{{ $item['kategori'] }}</td>
                                            <td class="px-4 py-2 text-xs text-red-700 text-right font-medium">{{ number_format($item['total'], 2) }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-600 text-right">{{ $item['bilangan'] }}</td>
                                            <td class="px-4 py-2 text-xs text-gray-900 text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">
                                                    {{ number_format($item['peratus'], 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                <span class="material-icons mb-2" style="font-size: 48px !important;">trending_down</span>
                                                <p class="text-sm">Tiada data perbelanjaan</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Tab 8: Baki Bank -->
                @if($tabPermissions['baki_bank'])
                <div id="content-baki-bank" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-blue-600 mb-1">Jumlah Akaun</p>
                                    <p class="text-xl font-bold text-blue-900">{{ $akaunBank->count() }}</p>
                                </div>
                                <span class="material-icons text-blue-600" style="font-size: 32px !important;">account_balance</span>
                            </div>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded p-4 md:col-span-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-green-600 mb-1">Jumlah Baki Semua Akaun</p>
                                    <p class="text-xl font-bold text-green-900">RM {{ number_format($akaunBank->sum('baki_sebenar'), 2) }}</p>
                                </div>
                                <span class="material-icons text-green-600" style="font-size: 32px !important;">account_balance_wallet</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Senarai Akaun Bank</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Nama Bank</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">No. Akaun</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Jenis Akaun</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700 text-right">Baki Semasa (RM)</th>
                                        <th class="px-4 py-2 text-xs font-medium text-gray-700">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($akaunBank as $akaun)
                                    <tr>
                                        <td class="px-4 py-2 text-xs text-gray-900">{{ $akaun->nama_bank }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $akaun->no_akaun }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-600">{{ $akaun->jenis_akaun }}</td>
                                        <td class="px-4 py-2 text-xs text-gray-900 text-right font-medium">{{ number_format($akaun->baki_sebenar, 2) }}</td>
                                        <td class="px-4 py-2 text-xs">
                                            @if($akaun->status == 'Aktif')
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                            <span class="material-icons mb-2" style="font-size: 48px !important;">account_balance</span>
                                            <p class="text-sm">Tiada akaun bank</p>
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
        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-600', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('content-' + tabName).classList.add('active');
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-blue-600', 'text-blue-600');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-activate first visible TAB on page load
            const firstVisibleTab = document.querySelector('.tab-button');
            if (firstVisibleTab) {
                // Extract tab name from onclick attribute
                const onclickAttr = firstVisibleTab.getAttribute('onclick');
                const tabName = onclickAttr.match(/switchTab\('(.+?)'\)/)[1];
                switchTab(tabName);
            }
            
            // Pendapatan Chart
            const pendapatanCtx = document.getElementById('pendapatanChart');
            if (pendapatanCtx) {
                new Chart(pendapatanCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($pendapatanByKategori)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($pendapatanByKategori)) !!},
                            backgroundColor: ['#10B981', '#3B82F6', '#FBBF24', '#A855F7', '#EC4899', '#14B8A6'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 10,
                                    font: { size: 10 },
                                    boxWidth: 12,
                                    boxHeight: 12
                                }
                            }
                        }
                    }
                });
            }

            // Perbelanjaan Chart
            const perbelanjaanCtx = document.getElementById('perbelanjaanChart');
            if (perbelanjaanCtx) {
                new Chart(perbelanjaanCtx, {
                    type: 'pie',
                    data: {
                        labels: {!! json_encode(array_keys($perbelanjaanByKategori)) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($perbelanjaanByKategori)) !!},
                            backgroundColor: ['#EF4444', '#F97316', '#FBBF24', '#A855F7', '#EC4899', '#6B7280'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 10,
                                    font: { size: 10 },
                                    boxWidth: 12,
                                    boxHeight: 12
                                }
                            }
                        }
                    }
                });
            }

            // Aliran Tunai Chart
            const aliranTunaiCtx = document.getElementById('aliranTunaiChart');
            if (aliranTunaiCtx) {
                const aliranData = {!! json_encode($aliranTunaiBulanan) !!};
                new Chart(aliranTunaiCtx, {
                    type: 'line',
                    data: {
                        labels: aliranData.map(d => d.bulan),
                        datasets: [
                            {
                                label: 'Pendapatan',
                                data: aliranData.map(d => d.pendapatan),
                                borderColor: '#10B981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Perbelanjaan',
                                data: aliranData.map(d => d.perbelanjaan),
                                borderColor: '#EF4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Baki',
                                data: aliranData.map(d => d.baki),
                                borderColor: '#3B82F6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    padding: 10,
                                    font: { size: 10 },
                                    boxWidth: 12,
                                    boxHeight: 12
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { font: { size: 10 } }
                            },
                            x: {
                                ticks: { font: { size: 10 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
