<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Agihan Zakat - E-Masjid</title>
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
                        <h1 class="text-xl font-bold text-gray-900 mb-1">Laporan Agihan Zakat</h1>
                        <p class="text-xs text-gray-600">Statistik dan analisis agihan zakat kepada asnaf</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-2">
                        <a href="{{ route('agihan-zakat.index') }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">arrow_back</span>
                            Kembali
                        </a>
                        <button onclick="window.print()" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">print</span>
                            Cetak
                        </button>
                        <a href="{{ route('agihan-zakat.laporan.export', request()->query()) }}" class="inline-flex items-center justify-center h-[32px] px-4 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2" style="flex-shrink: 0 !important;">
                            <span class="material-icons mr-2" style="font-size: 16px !important;">download</span>
                            Eksport Excel
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <x-statistics-grid :stats="$stats" />

                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 print:hidden">
                    <!-- Pie Chart: By Status -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">pie_chart</span>
                            Taburan Mengikut Status
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>

                    <!-- Bar Chart: By Jenis Bantuan -->
                    <div class="bg-white border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">bar_chart</span>
                            Taburan Mengikut Jenis Bantuan
                        </h3>
                        <div class="flex items-center justify-center" style="height: 300px;">
                            <canvas id="jenisBantuanChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('agihan-zakat.laporan') }}" class="mb-6 print:hidden">
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3">Penapis Laporan</h3>
                        
                        <div class="flex flex-col md:flex-row gap-3">
                            <!-- Status Filter -->
                            <select name="status" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="Sudah Bayar" {{ request('status') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>

                            <!-- Jenis Bantuan Filter -->
                            <select name="jenis_bantuan" class="flex-1 px-3 py-2 border border-gray-200 rounded text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Jenis Bantuan</option>
                                <option value="Zakat Fitrah" {{ request('jenis_bantuan') == 'Zakat Fitrah' ? 'selected' : '' }}>Zakat Fitrah</option>
                                <option value="Zakat Harta" {{ request('jenis_bantuan') == 'Zakat Harta' ? 'selected' : '' }}>Zakat Harta</option>
                                <option value="Zakat Perniagaan" {{ request('jenis_bantuan') == 'Zakat Perniagaan' ? 'selected' : '' }}>Zakat Perniagaan</option>
                                <option value="Zakat Pendapatan" {{ request('jenis_bantuan') == 'Zakat Pendapatan' ? 'selected' : '' }}>Zakat Pendapatan</option>
                                <option value="Zakat Emas/Perak" {{ request('jenis_bantuan') == 'Zakat Emas/Perak' ? 'selected' : '' }}>Zakat Emas/Perak</option>
                                <option value="Zakat Saham" {{ request('jenis_bantuan') == 'Zakat Saham' ? 'selected' : '' }}>Zakat Saham</option>
                                <option value="Zakat KWSP" {{ request('jenis_bantuan') == 'Zakat KWSP' ? 'selected' : '' }}>Zakat KWSP</option>
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
                            <a href="{{ route('agihan-zakat.laporan') }}" class="inline-flex items-center justify-center px-6 py-2 bg-red-600 text-white text-xs rounded hover:bg-red-700 whitespace-nowrap">
                                <span class="material-icons mr-2" style="font-size: 16px !important;">refresh</span>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Summary Tables -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- By Status -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-blue-600" style="font-size: 18px !important;">check_circle</span>
                            Ringkasan Mengikut Status
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-blue-100">
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
                                                @if($item->status == 'Sudah Bayar') bg-green-100 text-green-800
                                                @elseif($item->status == 'Belum Bayar') bg-orange-100 text-orange-800
                                                @elseif($item->status == 'Dibatalkan') bg-red-100 text-red-800
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

                    <!-- By Jenis Bantuan -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-green-600" style="font-size: 18px !important;">category</span>
                            Ringkasan Mengikut Jenis Bantuan
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jenis Bantuan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700 text-right">Bilangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($byJenisBantuan as $item)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $item->jenis_bantuan }}</td>
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

                <!-- Recent Agihan & Upcoming Bayaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Recent Agihan (30 days) -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-purple-600" style="font-size: 18px !important;">new_releases</span>
                            Agihan Terkini (30 Hari Lepas)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-purple-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">No Agihan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Nama Asnaf</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jumlah (RM)</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Tarikh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($recentAgihan as $agihan)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $agihan->no_agihan }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $agihan->permohonanZakat->asnaf->nama ?? '-' }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ number_format($agihan->jumlah_diagihkan, 2) }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $agihan->tarikh_agihan->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-4 text-center text-gray-500">Tiada agihan terkini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Upcoming Bayaran (7 days) -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-orange-600" style="font-size: 18px !important;">event</span>
                            Bayaran Akan Datang (7 Hari)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-orange-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">No Agihan</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Nama Asnaf</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Jumlah (RM)</th>
                                        <th class="px-3 py-2 font-medium text-gray-700">Tarikh</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($upcomingBayaran as $agihan)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $agihan->no_agihan }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $agihan->permohonanZakat->asnaf->nama ?? '-' }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ number_format($agihan->jumlah_diagihkan, 2) }}</td>
                                        <td class="px-3 py-2 text-gray-600">{{ $agihan->tarikh_agihan->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-4 text-center text-gray-500">Tiada bayaran akan datang</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Kaedah Bayaran & Average -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- By Kaedah Bayaran -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-indigo-600" style="font-size: 18px !important;">payment</span>
                            Ringkasan Mengikut Kaedah Bayaran
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-xs">
                                <thead class="bg-indigo-100">
                                    <tr>
                                        <th class="px-3 py-2 font-medium text-gray-700">Kaedah Bayaran</th>
                                        <th class="px-3 py-2 font-medium text-gray-700 text-right">Bilangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @forelse($byKaedahBayaran as $item)
                                    <tr>
                                        <td class="px-3 py-2 text-gray-900">{{ $item->kaedah_bayaran }}</td>
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

                    <!-- Average Jumlah -->
                    <div class="bg-gray-50 border border-gray-200 rounded p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                            <span class="material-icons mr-2 text-teal-600" style="font-size: 18px !important;">calculate</span>
                            Purata Jumlah Agihan
                        </h3>
                        <div class="flex items-center justify-center h-32">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-teal-600">RM {{ number_format($avgJumlah, 2) }}</div>
                                <div class="text-xs text-gray-600 mt-2">Per Agihan</div>
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

        // Pie Chart: By Status
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = {
                labels: [
                    @foreach($byStatus as $item)
                        '{{ $item->status }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($byStatus as $item)
                            {{ $item->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        @foreach($byStatus as $item)
                            @if($item->status == 'Sudah Bayar')
                                '#10b981',
                            @elseif($item->status == 'Belum Bayar')
                                '#f59e0b',
                            @elseif($item->status == 'Dibatalkan')
                                '#ef4444',
                            @else
                                '#6b7280',
                            @endif
                        @endforeach
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            };

            new Chart(statusCtx, {
                type: 'pie',
                data: statusData,
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

        // Bar Chart: By Jenis Bantuan
        const jenisBantuanCtx = document.getElementById('jenisBantuanChart');
        if (jenisBantuanCtx) {
            const jenisBantuanData = {
                labels: [
                    @foreach($byJenisBantuan as $item)
                        '{{ $item->jenis_bantuan }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Bilangan Agihan',
                    data: [
                        @foreach($byJenisBantuan as $item)
                            {{ $item->count }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            };

            new Chart(jenisBantuanCtx, {
                type: 'bar',
                data: jenisBantuanData,
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
