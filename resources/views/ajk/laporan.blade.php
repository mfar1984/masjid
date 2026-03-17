<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan AJK - E-Masjid</title>
    <x-favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Ahli Jawatankuasa</h1>
                        <p class="text-xs text-gray-600">Statistik dan analisis ahli jawatankuasa masjid</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('ajk.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">print</span>
                            Cetak
                        </button>
                        <a href="{{ route('ajk.laporan.export', request()->query()) }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport Excel
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 print:hidden">
                    <!-- Pie Chart: By Jawatan -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">pie_chart</span>
                            Taburan Mengikut Jawatan
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="jawatanChart"></canvas>
                        </div>
                    </div>

                    <!-- Bar Chart: By Status -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">bar_chart</span>
                            Taburan Mengikut Status
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('ajk.laporan') }}" class="mb-6 print:hidden">
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Penapis Laporan</h3>
                        
                        <div class="flex flex-col md:flex-row gap-3">
                            <!-- Jawatan Filter -->
                            <select name="jawatan" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Jawatan</option>
                                <option value="Pengerusi" {{ request('jawatan') == 'Pengerusi' ? 'selected' : '' }}>Pengerusi</option>
                                <option value="Naib Pengerusi" {{ request('jawatan') == 'Naib Pengerusi' ? 'selected' : '' }}>Naib Pengerusi</option>
                                <option value="Setiausaha" {{ request('jawatan') == 'Setiausaha' ? 'selected' : '' }}>Setiausaha</option>
                                <option value="Bendahari" {{ request('jawatan') == 'Bendahari' ? 'selected' : '' }}>Bendahari</option>
                                <option value="Penolong Setiausaha" {{ request('jawatan') == 'Penolong Setiausaha' ? 'selected' : '' }}>Penolong Setiausaha</option>
                                <option value="Penolong Bendahari" {{ request('jawatan') == 'Penolong Bendahari' ? 'selected' : '' }}>Penolong Bendahari</option>
                                <option value="Ahli Jawatankuasa" {{ request('jawatan') == 'Ahli Jawatankuasa' ? 'selected' : '' }}>Ahli Jawatankuasa</option>
                                <option value="Imam" {{ request('jawatan') == 'Imam' ? 'selected' : '' }}>Imam</option>
                                <option value="Bilal" {{ request('jawatan') == 'Bilal' ? 'selected' : '' }}>Bilal</option>
                                <option value="Siak" {{ request('jawatan') == 'Siak' ? 'selected' : '' }}>Siak</option>
                            </select>

                            <!-- Status Filter -->
                            <select name="status" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="Digantung" {{ request('status') == 'Digantung' ? 'selected' : '' }}>Digantung</option>
                            </select>

                            <!-- Date From -->
                            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Tarikh Dari" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            <!-- Date To -->
                            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Tarikh Hingga" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            @if(auth()->user()->isSuperAdmin())
                            <!-- Masjid Filter (Super Admin only) -->
                            <select name="masjid_id" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Masjid</option>
                                @foreach(\App\Models\Masjid::orderBy('nama')->get() as $masjid)
                                <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>{{ $masjid->nama }}</option>
                                @endforeach
                            </select>
                            @endif

                            <!-- Tapis Button -->
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 whitespace-nowrap">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">search</span>
                                Tapis
                            </button>

                            <!-- Reset Button -->
                            <a href="{{ route('ajk.laporan') }}" class="inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Summary Tables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- By Jawatan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">work</span>
                            Ringkasan Mengikut Jawatan
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-blue-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jawatan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700 text-right">Bilangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($byJawatan as $item)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $item->jawatan }}</td>
                                        <td class="px-3 py-2 text-gray-900 text-right font-medium">{{ $item->count }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- By Status -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">check_circle</span>
                            Ringkasan Mengikut Status
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Status</th>
                                        <th class="px-3 py-2 font-medium text-gray-700 text-right">Bilangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($byStatus as $item)
                                    <tr>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-sm text-xs font-medium 
                                                @if($item->status == 'Aktif') bg-green-100 text-green-800
                                                @elseif($item->status == 'Menunggu') bg-orange-100 text-orange-800
                                                @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                                @elseif($item->status == 'Digantung') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 text-gray-900 text-right font-medium">{{ $item->count }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent AJK & Tamat Tempoh -->

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Recent AJK (30 days) -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">new_releases</span>
                            AJK Baru (30 Hari Lepas)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-purple-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Nama</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jawatan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Tarikh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($recentAjk as $ajk)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $ajk->nama }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $ajk->jawatan }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $ajk->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-4 text-center text-gray-500">Tiada AJK baru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tamat Tempoh (3 months) -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">event</span>
                            Akan Tamat Tempoh (3 Bulan)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-orange-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Nama</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jawatan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Tarikh Tamat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($tamatTempoh as $ajk)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $ajk->nama }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $ajk->jawatan }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $ajk->tarikh_tamat->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-3 py-4 text-center text-gray-500">Tiada AJK akan tamat tempoh</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Demographics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- By Jantina -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-indigo-600" style="font-size: 18px !important;">people</span>
                            Ringkasan Mengikut Jantina
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-indigo-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jantina</th>
                                        <th class="px-3 py-2 font-medium text-gray-700 text-right">Bilangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($byJantina as $item)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $item->jantina }}</td>
                                        <td class="px-3 py-2 text-gray-900 text-right font-medium">{{ $item->count }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="px-3 py-4 text-center text-gray-500">Tiada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Average Tempoh -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-teal-600" style="font-size: 18px !important;">schedule</span>
                            Purata Tempoh Perkhidmatan
                        </h3>
                        <div class="flex items-center justify-center h-32">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-teal-600">{{ number_format($avgTempoh, 1) }}</div>
                                <div class="text-xs text-gray-600 mt-2">Tahun</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print Info -->
                <div class="hidden print:block mt-6 pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-600">
                        <p>Laporan dijana pada: {{ now()->format('d/m/Y H:i:s') }}</p>
                        <p>Dijana oleh: {{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <style>
        @media print {
            body {
                background: white;
            }
            .print\:hidden {
                display: none !important;
            }
            .print\:block {
                display: block !important;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>

    <script>
        // Chart.js Configuration
        Chart.defaults.font.family = 'Poppins, sans-serif';
        Chart.defaults.font.size = 11;

        // Pie Chart: By Jawatan
        const jawatanCtx = document.getElementById('jawatanChart');
        if (jawatanCtx) {
            const jawatanData = {
                labels: [
                    @foreach($byJawatan as $item)
                        '{{ $item->jawatan }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($byJawatan as $item)
                            {{ $item->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#3b82f6', // blue
                        '#10b981', // green
                        '#f59e0b', // orange
                        '#ef4444', // red
                        '#8b5cf6', // purple
                        '#06b6d4', // cyan
                        '#ec4899', // pink
                        '#14b8a6', // teal
                        '#f97316', // orange-600
                        '#6366f1', // indigo
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            };

            new Chart(jawatanCtx, {
                type: 'pie',
                data: jawatanData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 10,
                                font: {
                                    size: 10
                                },
                                boxWidth: 12,
                                boxHeight: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Bar Chart: By Status
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = {
                labels: [
                    @foreach($byStatus as $item)
                        '{{ $item->status }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Bilangan AJK',
                    data: [
                        @foreach($byStatus as $item)
                            {{ $item->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($byStatus as $item)
                            @if($item->status == 'Aktif')
                                '#10b981',
                            @elseif($item->status == 'Menunggu')
                                '#f59e0b',
                            @elseif($item->status == 'Ditolak')
                                '#ef4444',
                            @elseif($item->status == 'Digantung')
                                '#8b5cf6',
                            @else
                                '#6b7280',
                            @endif
                        @endforeach
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            };

            new Chart(statusCtx, {
                type: 'bar',
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Bilangan: ${context.parsed.y}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 10
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
